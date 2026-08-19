<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Mengambil semua notifikasi milik user yang sedang login
    public function index(Request $request)
    {
        // Otomatis mengambil dari tabel 'notifications' milik user tersebut
        $notifications = $request->user()->notifications;

        return response()->json([
            'status' => 'success',
            'data' => $notifications
        ], 200);
    }
}