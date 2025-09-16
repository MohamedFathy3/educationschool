<?php

namespace App\Http\Controllers;

use App\Http\Resources\MessageResource;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    private function getUserGuard()
    {
        foreach (['admins', 'teachers', 'students'] as $guard) {
            if (auth($guard)->check()) {
                return $guard;
            }
        }
        return null;
    }

    public function sendMessage(Request $request)
    {
        try {
            $request->validate([
                'receiver_id'   => 'required|integer',
                'receiver_type' => 'required|string|in:teacher,student,admin',
                'body'          => 'required|string',
            ]);

            $guard = $this->getUserGuard();
            if (!$guard) {
                return response()->json(['result' => 'Error', 'message' => 'Unauthorized'], 401);
            }

            $sender = auth($guard)->user();

            $message = Message::create([
                'sender_id'    => $sender->id,
                'sender_type'  => rtrim($guard, 's'), // teacher / student / admin
                'receiver_id'  => $request->receiver_id,
                'receiver_type'=> $request->receiver_type,
                'body'         => $request->body,
            ]);

        return response()->json([
            'result'  => 'Success',
            'message' => 'Message sent successfully',
            'data'    => new MessageResource($message->load(['sender', 'receiver'])),
            'status'  => 200
        ]);

        } catch (\Exception $e) {
            return response()->json([
                'result'  => 'Error',
                'message' => $e->getMessage(),
                'status'  => 500
            ]);
        }
    }

    public function getMessages(Request $request)
    {
        try {
            $request->validate([
                'receiver_id'   => 'required|integer',
                'receiver_type' => 'required|string|in:teacher,student,admin',
            ]);

            $guard = $this->getUserGuard();
            if (!$guard) {
                return response()->json(['result' => 'Error', 'message' => 'Unauthorized'], 401);
            }

            $sender = auth($guard)->user();

            $messages = Message::where(function ($q) use ($sender, $guard, $request) {
                    $q->where('sender_id', $sender->id)
                      ->where('sender_type', rtrim($guard, 's'))
                      ->where('receiver_id', $request->receiver_id)
                      ->where('receiver_type', $request->receiver_type);
                })
                ->orWhere(function ($q) use ($sender, $guard, $request) {
                    $q->where('receiver_id', $sender->id)
                      ->where('receiver_type', rtrim($guard, 's'))
                      ->where('sender_id', $request->receiver_id)
                      ->where('sender_type', $request->receiver_type);
                })
                ->with(['sender', 'receiver'])
                ->orderBy('created_at')
                ->get();

                return response()->json([
                    'result'  => 'Success',
                    'message' => 'Chat loaded successfully',
                    'data'    => MessageResource::collection($messages),
                    'status'  => 200
                ]);
        } catch (\Exception $e) {
            return response()->json([
                'result'  => 'Error',
                'message' => $e->getMessage(),
                'status'  => 500
            ]);
        }
    }
}
