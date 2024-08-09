<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WorkshopChatController extends Controller
{
    public function index(Workshop $workshop)
    {
        $chats = $workshop->chats()->with('user')->latest()->get();
        return response()->json($chats);
    }

    public function store(Request $request, Workshop $workshop)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $chat = $workshop->chats()->create([
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return response()->json($chat, 201);
    }
}
