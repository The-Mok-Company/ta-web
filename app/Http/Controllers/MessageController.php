<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use App\Mail\ConversationMailManager;
use Auth;
use Mail;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $authUser = Auth::user();
        $message = new Message;
        $message->conversation_id = $request->conversation_id;
        $message->user_id = $authUser->id;
        $message->message = $request->message;
        $message->save();
        $conversation = $message->conversation;

        // Determine recipient for email notification
        $recipientId = null;
        if ($conversation->sender_id == $authUser->id) {
            $conversation->sender_viewed = "1";
            $conversation->receiver_viewed = "0";
            $recipientId = $conversation->receiver_id;
        } elseif ($conversation->receiver_id == $authUser->id || $authUser->user_type == 'staff' || $authUser->user_type == 'admin') {
            $conversation->sender_viewed = "0";
            $conversation->receiver_viewed = "1";
            $recipientId = $conversation->sender_id;
        }
        $conversation->save();

        // Send email notification to the recipient
        if ($recipientId) {
            $this->sendReplyNotification($conversation, $message, $recipientId);
        }

        return back();
    }

    /**
     * Send email notification when a reply is sent
     */
    protected function sendReplyNotification($conversation, $message, $recipientId)
    {
        $recipient = User::find($recipientId);
        if (!$recipient || !$recipient->email) {
            return;
        }

        $authUser = Auth::user();

        $array = [];
        $array['view'] = 'emails.conversation';
        $array['subject'] = translate('New Reply') . ' - ' . $conversation->title;
        $array['from'] = env('MAIL_FROM_ADDRESS');
        $array['content'] = translate('You have received a new reply from') . ' ' . $authUser->name;
        $array['sender'] = $authUser->name;

        // Determine the correct link based on user type
        if ($recipient->user_type == 'admin' || $recipient->user_type == 'staff') {
            $array['link'] = route('conversations.admin_show', encrypt($conversation->id));
        } else {
            $array['link'] = route('conversations.show', encrypt($conversation->id));
        }

        $array['details'] = $message->message;

        try {
            Mail::to($recipient->email)->queue(new ConversationMailManager($array));
        } catch (\Exception $e) {
            // Log error silently
            \Log::error('Failed to send conversation reply email: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
