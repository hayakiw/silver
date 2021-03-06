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

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $status = ($request->input('status'))? $request->input('status') : Order::ORDER_STATUS_PAID;

        $orders = Order::query()
            ->where('user_id', $user->id)
            ->where('status', $status)
            ->orderBy('id', 'desc')
            ->paginate(100)->setPath('');

        return view('order.index')
            ->with([
            'status' => $status,
            'orders' => $orders,
        ]);
    }

    public function show($id)
    {
        $user = auth()->user();

        $order = Order::query()
            ->where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        return view('order.show')
            ->with([
            'order' => $order
        ]);
    }

    public function payment(ItemRequest\OrderRequest $request)
    {
        $orderData = $request->only([
            'item_id',
            'hours',
            'prefer_date',
            'prefer_hour',
            'prefer_date2',
            'prefer_hour2',
            'prefer_date3',
            'prefer_hour3',
            'comment'
        ]);

        $user = auth()->user();
        $orderData['user_id'] = $user->id;
        $orderData['status'] = Order::ORDER_STATUS_NEW;

        $item = Item::findOrFail($orderData['item_id']);
        $orderData['staff_id'] = $item->staff_id;
        $orderData['price'] = $item->price;
        $orderData['title'] = $item->title;
        $orderData['prefer_at'] = $orderData['prefer_date'] . " " . $orderData['prefer_hour'] . ":00";
        if($orderData['prefer_date2'])
            $orderData['prefer_at2'] = $orderData['prefer_date2'] . " " . $orderData['prefer_hour2'] . ":00";
        if($orderData['prefer_date3'])
            $orderData['prefer_at3'] = $orderData['prefer_date3'] . " " . $orderData['prefer_hour3'] . ":00";

        if ($order = Order::create($orderData)) {
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

    public function destroy(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if ($order->status == Order::ORDER_STATUS_PAID) {
            $order->status = Order::ORDER_STATUS_CANCEL;

            if ($order->update()) {
                // notification
                \App\Notification::create([
                    'staff_id' => $order->item->staff->id,
                    'content' => $order->user->getName() . ' さんから依頼をキャンセルされました。',
                    'event' => 'notify.order',
                    'notifiable_type' => 'order',
                    'notifiable_id' => $order->id,
                ]);

                return redirect()
                    ->back()
                    ->with('message', '依頼をキャンセルしました');
            }
        }

        return redirect()
            ->back()
            ->withError('依頼をキャンセルできませんでした。');
    }
}
