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
}
