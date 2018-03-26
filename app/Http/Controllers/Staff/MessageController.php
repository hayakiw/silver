<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\Message as MessageRequest;
use App\User;
use App\Message;

class MessageController extends Controller
{
    public function index($userId)
    {
        $user = User::findOrFail($userId);

        return view('staff.message.index',
            compact('user')
        );
    }

    public function store(MessageRequest\StoreRequest $request)
    {
        $user = User::findOrFail($request->input('user_id'));

        $messageData = $request->only([
            'body'
        ]);

        $staff = auth('staff')->user();
        $messageData['from'] = 'staff';
        $messageData['user_id'] = $user->id;
        $messageData['staff_id'] = $staff->id;
        $messageData['subject'] = $user->getName() . 'さんからのメッセージ';

        if ($message = Message::create($messageData)) {
            $request->session()->flash('info', '送信しました。');
            return redirect()
                ->route('staff.messages.index', $user->id)
            ;
        }

        return redirect()
            ->back()
            ->withInput($messageData)
        ;
    }
}