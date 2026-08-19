<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // <-- TAMBAHAN WAJIB
use App\Models\Chat;
use App\Models\User;

class ChatController extends Controller
{
    // Menampilkan daftar member yang pernah chat
    public function index()
    {
        // Tarik user yang punya relasi ke tabel chats
        $users = User::whereHas('chats')->with('membership')->get();
        return view('superadmin.chats.index', compact('users'));
    }

    // Menampilkan ruang obrolan dengan member tertentu
    public function show($id)
    {
        $user = User::findOrFail($id);
        $chats = Chat::where('user_id', $id)->orderBy('created_at', 'asc')->get();
        
        return view('superadmin.chats.show', compact('user', 'chats'));
    }

    // Membalas pesan member & KIRIM NOTIFIKASI
    public function reply(Request $request, $id)
    {
        $request->validate(['message' => 'required|string']);

        // 1. Simpan pesan ke database
        Chat::create([
            'user_id' => $id,
            'sender_role' => 'admin',
            'message' => $request->message,
        ]);

        // 2. Tarik data user untuk mengambil token
        $user = User::find($id);

        // --- TAMBAHKAN BARIS INI UNTUK MENYIMPAN RIWAYAT KE KOTAK SURAT LONCENG ---
        if ($user) {
            $user->notify(new \App\Notifications\WowinNotification(
                'Balasan dari CS Wowin Food', 
                $request->message, 
                'chat' // Kode ikon chat
            ));
        }

        // 3. Tembakkan notifikasi HTTP v1 jika user punya token FCM
        if ($user && $user->fcm_token) {
            try {
                // Ambil file JSON yang baru saja Anda letakkan di storage/app/
                $credentialsFilePath = storage_path('app/firebase_credentials.json');
                $credentials = json_decode(file_get_contents($credentialsFilePath), true);
                $projectId = $credentials['project_id']; 

                // Proses autentikasi ke server Google
                $client = new \Google_Client();
                $client->setAuthConfig($credentialsFilePath);
                $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
                $client->fetchAccessTokenWithAssertion();
                $token = $client->getAccessToken();
                $access_token = $token['access_token'];

                // Format data JSON khusus HTTP v1
                $data = [
                    "message" => [
                        "token" => $user->fcm_token,
                        "notification" => [
                            "title" => "Balasan dari CS Wowin Food",
                            "body"  => $request->message,
                        ],
                        // --- MASA AKTIF NOTIFIKASI MAKSIMAL (28 HARI) ---
                        "android" => [
                            "ttl" => "2419200s" // 2.419.200 detik = 28 Hari (Batas maksimal dari Google)
                        ],
                        // ------------------------------------------------
                        "data" => [
                            "click_action" => "FLUTTER_NOTIFICATION_CLICK", 
                            "route" => "live_chat"
                        ]
                    ]
                ];

                // Kirim Notifikasi!
                Http::withHeaders([
                    'Authorization' => 'Bearer ' . $access_token,
                    'Content-Type'  => 'application/json',
                ])->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", $data);

            } catch (\Exception $e) {
                // Jika gagal kirim notif, simpan error di log (aplikasi tidak akan crash)
                \Log::error("Gagal mengirim notifikasi FCM: " . $e->getMessage());
            }
        }

        return back()->with('success', 'Balasan terkirim!');
    }

    // Menghapus pesan (Hak istimewa Super Admin)
    public function destroy($id)
    {
        $chat = Chat::findOrFail($id);
        $chat->delete();

        return back()->with('success', 'Pesan berhasil dihapus!');
    }
}