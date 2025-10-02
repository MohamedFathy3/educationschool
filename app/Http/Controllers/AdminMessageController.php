<?php

namespace App\Http\Controllers;

use App\Models\AdminMessage;
use Illuminate\Http\Request;

class AdminMessageController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $msg = AdminMessage::create([
            'message' => $request->message
        ]);

        return response()->json([
            'result' => 'Success',
            'message' => 'Message sent to all teachers',
            'data'    => $msg
        ]);
    }

    public function getMessages()
    {
        return response()->json([
            'result' => 'Success',
            'messages' => AdminMessage::latest()->get()
        ]);
    }

    public function markAsRead($id)
    {
        $message = AdminMessage::find($id);
        if (!$message) {
            return response()->json(['result' => 'Error', 'message' => 'Message not found'], 404);
        }

        $message->is_read = true;
        $message->save();

        return response()->json(['result' => 'Success', 'message' => 'Message marked as read']);
    }
}
