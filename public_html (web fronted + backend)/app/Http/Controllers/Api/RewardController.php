<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reward;
use App\Models\ClaimedReward;
use Illuminate\Support\Facades\Auth;

class RewardController extends Controller
{
    // Mengirim daftar reward ke Flutter
    public function index(Request $request)
    {
        $query = Reward::query();
        
        if ($request->has('search') && $request->search != '') {
            $query->where('nama_reward', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
        }
        
        // Mengurutkan dari yang terbaru
        $rewards = $query->latest()->get(); 
        
        return response()->json([
            'status' => 'success',
            'data' => $rewards
        ], 200);
    }

    // Memproses klaim reward dari Flutter
    public function claim(Request $request)
    {
        $request->validate(['reward_id' => 'required|exists:rewards,id']);
        
        $user = Auth::user();
        $reward = Reward::findOrFail($request->reward_id);

        // Di tabel users Anda, nama kolomnya adalah total_points (merujuk pada AuthController Anda sebelumnya)
        if ($user->total_points < $reward->points_required) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Poin Anda tidak cukup untuk mengklaim reward ini.'
            ], 400);
        }

        // Simpan riwayat klaim
        ClaimedReward::create([
            'user_id' => $user->id,
            'reward_id' => $reward->id,
            'claimed_at' => now(),
        ]);

        // Potong poin user
        $user->decrement('total_points', $reward->points_required);

        return response()->json([
            'status' => 'success', 
            'message' => 'Hore! Reward berhasil diklaim!'
        ], 200);
    }
}