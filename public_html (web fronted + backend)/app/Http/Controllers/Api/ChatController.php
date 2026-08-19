<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;

class ChatController extends Controller
{
    // 1. Menarik semua riwayat chat milik user yang sedang login
    public function index(Request $request)
    {
        $chats = Chat::where('user_id', $request->user()->id)
                     ->orderBy('created_at', 'asc') // Urutkan dari yang terlama ke terbaru (seperti WhatsApp)
                     ->get();

        return response()->json([
            'status' => 'success',
            'data' => $chats
        ], 200);
    }

    // 2. User mengirim pesan baru ke Admin
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $chat = Chat::create([
            'user_id' => $request->user()->id,
            'sender_role' => 'user', // Otomatis ditandai sebagai pesan dari 'user'
            'message' => $request->message,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pesan berhasil dikirim',
            'data' => $chat
        ], 201);
    }
}