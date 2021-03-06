<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Mail;
use Carbon\Carbon;
use DB;

use App\Http\Requests\Item as ItemRequest;
use App\Item;
use App\Order;
use App\Pay;
use App\Category;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->only([
            'category'
        ]);

        $items = Item::query();

        if (isset($search['category']) && $search['category'] != ''){
            $category = Category::find($search['category']);

            if(!$category->parent_id) {
                // 子カテゴリ全て
                $childIds = $category->childIds();
                if (!empty($childIds))
                    $items = $items->whereIn('category_id', $childIds);
            } else {
                $items = $items->where('category_id', '=', $search['category']);
            }
        }

        if (isset($search['area']) && $search['area'] != ''){
            $items = $items->where('area', '=', $search['area']);
        }

        $items = $items->orderBy('id', 'desc');

        $items = $items->paginate(100)->setPath('');

        return view('item.index')
            ->with([
            'items' => $items,
        ]);
    }

    public function show($id)
    {
        $item = Item::findOrFail($id);
        return view('item.show')
            ->with([
            'item' => $item,
            'order' => new Order()
        ]);
    }

    public function pay(ItemRequest\OrderRequest $request)
    {
        $orderData = $request->only([
            'item_id',
            'hours',
            'prefer_date',
            'prefer_hour'
        ]);

        $user = auth()->user();
        $orderData['user_id'] = $user->id;
        $orderData['status'] = Order::ORDER_STATUS_NEW;

        $token = hash_hmac('sha256', Str::random(40), config('app.key'));
        $orderData['ordered_token'] = $token;

        $item = Item::findOrFail($orderData['item_id']);
        $orderData['total_price'] = $item->price * $orderData['hours'];
        $orderData['fee'] = ceil($orderData['total_price'] * config('my.order.fee') / 100);

        $orderData['staff_id'] = $item->staff_id;
        $orderData['price'] = $item->price;
        $orderData['title'] = $item->title;
        $orderData['prefer_at'] = $orderData['prefer_date'] . " " . $orderData['prefer_hour'] . ":00";

        if ($order = Order::create($orderData)) {
            return view('item.pay')
                ->with([
                'order' => $order
            ]);
        }

        return redirect()
            ->back()
            ->withInput($orderData)
            ;
    }

    public function order(Request $request)
    {
        $token = $request->input('ordered_token');
        // pay api
        //$payCard = $request->input('card');
        $payToken = $request->input('payjp-token');

        $user = auth()->user();
        $order = Order::where('ordered_token', $token)->firstOrFail();

        $amount = $order->item->price * $order->hours;

        // pay api
        //config('my.pay.public_key')
        $params = [
            'amount' => $amount,
            'currency' => 'jpy',
            'capture' => 'false',
            'card' => $payToken,
        ];

        $curl=curl_init(config('my.pay.charge_url'));
        curl_setopt($curl,CURLOPT_POST, TRUE);
        curl_setopt($curl, CURLOPT_USERPWD, config('my.pay.private_key'));
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $json= curl_exec($curl);
        curl_close($curl);

        $res = json_decode($json, true);

        // TODO respons validation
        if (isset($res['error'])) {
            $message = '支払処理に失敗しました。';
            if ($res['error']['code'] == 'token_already_used') {
                $message = '再度初めからやり直してください。';
            }

            $request->session()->flash('error', $message);
            return redirect()
                ->route('root.index')
                ;
        }

        // create pay
        $payData = [
            'user_id' => $user->id,
            'order_id' => $order->id,
            'token' => $payToken,
            'amount' => $amount,
            'credit_id' => $res['id'],
            'status' => 'new',
            'error_message' => '',
        ];

        if ($pay = Pay::create($payData)) {
            $order->status = Order::ORDER_STATUS_PAID;
            $order->ordered_token = null;
            $order->save();

            // send mail for user
            Mail::send(
                ['text' => 'mail.order_created'],
                compact('order'),
                function ($m) use ($order) {
                    $m->from(
                        config('my.mail.from'),
                        config('my.mail.name')
                    );
                    $m->to($order->user->email, $order->user->getName());
                    $m->subject(
                        config('my.order.created.mail_subject')
                    );
                }
            );
            // send mail for staff
            Mail::send(
                ['text' => 'staff.mail.order_created'],
                compact('order'),
                function ($m) use ($order) {
                    $m->from(
                        config('my.mail.from'),
                        config('my.mail.name')
                    );
                    $m->to($order->item->staff->email, $order->item->staff->getName());
                    $m->subject(
                        config('my.order.created_for_staff.mail_subject')
                    );
                }
            );

            // notification
            \App\Notification::create([
                'staff_id' => $order->item->staff->id,
                'content' => $order->user->getName() . ' さんから申請がありました。',
                'event' => 'notify.order',
                'notifiable_type' => 'order',
                'notifiable_id' => $order->id,
            ]);

            $request->session()->flash('info', '依頼しました。結果がくるまでしばらくお待ちください。');
            return redirect()
                ->route('root.index')
            ;
        }
        return redirect()
            ->back()
            ->withInput($orderData)
        ;
    }
}
