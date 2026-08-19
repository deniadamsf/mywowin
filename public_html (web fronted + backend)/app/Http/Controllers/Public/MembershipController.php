<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use Illuminate\Support\Facades\Auth;
use stdClass;

class MembershipController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Ambil data user yang login

        // Definisikan tingkatan membership
        $tiers = [
            'bronze'    => ['nama' => 'Bronze',   'min' => 0,          'max' => 5000000],
            'silver'    => ['nama' => 'Silver',   'min' => 5000000,    'max' => 10000000],
            'gold'      => ['nama' => 'Gold',     'min' => 10000000,   'max' => 50000000],
            'platinum'  => ['nama' => 'Platinum', 'min' => 50000000,   'max' => 150000000],
            'diamond'   => ['nama' => 'Diamond',  'min' => 150000000,  'max' => 400000000],
        ];
        $tierOrder = ['bronze', 'silver', 'gold', 'platinum', 'diamond'];

        // --- LOGIKA PROGRES UNTUK USER LOGIN ---
        
        // 1. Hitung total belanja bulan ini
        $totalBelanjaBulanIni = $user->orders()
            ->where('status', 'completed')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('total');

        // 2. Ambil level saat ini
        $currentLevelKey = strtolower($user->membership->level_membership ?? 'bronze');
        $currentTier = $tiers[$currentLevelKey] ?? $tiers['bronze'];

        // 3. Cari tahu level berikutnya
        $nextLevelKey = null;
        $currentKeyIndex = array_search($currentLevelKey, $tierOrder);
        if ($currentKeyIndex !== false && $currentKeyIndex < (count($tierOrder) - 1)) {
            $nextLevelKey = $tierOrder[$currentKeyIndex + 1];
        }

        // 4. Siapkan data progres
        $progressData = [
            'totalBelanja' => $totalBelanjaBulanIni,
            'levelSekarang' => $currentTier['nama'],
            'isMaxLevel' => ($nextLevelKey === null),
            'percentage' => 100, // Default jika max level
            'levelBerikutnya' => '',
            'targetBerikutnya' => 0,
        ];

        if ($nextLevelKey) {
            $nextTier = $tiers[$nextLevelKey];
            $target = $nextTier['min'];
            $awal = $currentTier['min'];
            
            $range = $target - $awal;
            $dicapai = $totalBelanjaBulanIni - $awal;
            
            $percentage = ($range > 0) ? ($dicapai / $range) * 100 : 0;

            $progressData['levelBerikutnya'] = $nextTier['nama'];
            $progressData['targetBerikutnya'] = $target;
            $progressData['percentage'] = max(0, min(100, $percentage));
        }

        // 5. Suntikkan data ke objek user agar Blade bisa membaca $user->progress
        $user->progress = (object)$progressData;

        return view('public.members.member', compact('user'));
    }

    public function show($id)
    {
        $membership = Membership::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return view('public.members.member', compact('membership'));
    }
}
