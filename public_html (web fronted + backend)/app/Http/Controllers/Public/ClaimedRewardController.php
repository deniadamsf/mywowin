<?php

namespace App\Http\Controllers\Public;

use App\Models\ClaimedReward;
use App\Models\User;
use App\Models\Reward;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClaimedRewardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Reward::query();
    
        // Cek kalau ada parameter 'search' dari form
        if ($request->has('search')) {
            $query->where('nama_reward', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
        }
    
        // Tetap paginasi hasilnya
        $rewards = $query->paginate(10)->withQueryString(); // withQueryString agar pagination tetap bawa parameter 'search'
    
        return view('public.rewards.index', compact('rewards'));
    }
    

    

    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Optionally show a form if needed
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk mengklaim reward.');
        }

        $rewardId = $request->reward_id ?? $request->id;
        if (!$rewardId) {
            return back()->with('error', 'Pilihan reward tidak valid.');
        }

        $user = Auth::user();
        $reward = Reward::findOrFail($rewardId);

        // Cek apakah total poin cukup
        $userPoints = $user->total_points ?? $user->points_today ?? 0;
        if ($userPoints < $reward->points_required) {
            return back()->with('error', 'Poin Anda tidak cukup untuk mengklaim reward ini.');
        }

        // Simpan klaim reward
        ClaimedReward::create([
            'user_id' => $user->id,
            'reward_id' => $reward->id,
            'status' => 'pending',
            'claimed_at' => now(),
        ]);

        // Kurangi total poin pengguna
        $user->decrement('total_points', $reward->points_required);

        return redirect()->route('rewards.index')->with('success', 'Hore! Reward "' . $reward->nama_reward . '" berhasil diklaim. Silakan hubungi admin cabang untuk pengambilan.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Optionally show a specific claim (if needed)
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Optionally edit a claimed reward (if needed)
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Optionally update a claimed reward (if needed)
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Optionally delete a claimed reward (if needed)
    }
}
