<?php

namespace App\Http\Controllers\Admin;
use App\Models\Reward;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class RewardBundleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $pointFilter = $request->input('point_filter');
        $dateFilter = $request->input('date_filter');
        
        $rewards = Reward::when($search, function ($query, $search) {
                return $query->where('nama_reward', 'like', '%' . $search . '%');
            })
            ->when($pointFilter, function ($query, $pointFilter) {
                if ($pointFilter == 'low') {
                    return $query->where('points_required', '<', 500);
                } elseif ($pointFilter == 'medium') {
                    return $query->whereBetween('points_required', [500, 1000]);
                } elseif ($pointFilter == 'high') {
                    return $query->where('points_required', '>', 1000);
                }
            })
            ->when($dateFilter, function ($query, $dateFilter) {
                if ($dateFilter == 'today') {
                    return $query->whereDate('created_at', now()->toDateString());
                } elseif ($dateFilter == 'week') {
                    return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                } elseif ($dateFilter == 'month') {
                    return $query->whereMonth('created_at', now()->month);
                }
            })
            ->paginate(10);
    
        return view('admin.rewards.index', compact('rewards', 'search', 'pointFilter', 'dateFilter'));
    }
    
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.rewards.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_reward' => 'required|string|max:255',
            'foto_rewards' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',  // Maksimal ukuran gambar 2MB
            'points_required' => 'required|integer|min:1',  // Pastikan poin adalah angka positif
            'deskripsi' => 'required|string',  // Deskripsi wajib diisi
        ]);
    
        // Proses unggah gambar
        $imagePath = null;
        if ($request->hasFile('foto_rewards')) {
            $file = $request->file('foto_rewards');
            $fileName = time() . "_" . $file->getClientOriginalName();
            $imagePath = $file->storeAs('foto_rewards', $fileName, 'public');
        }
    
        // Simpan data ke database
        Reward::create([
            'nama_reward' => $request->nama_reward,  // Menyimpan nama reward
            'deskripsi' => $request->deskripsi,  // Menyimpan deskripsi reward
            'points_required' => $request->points_required,  // Menyimpan poin yang dibutuhkan
            'foto_rewards' => $imagePath,  // Menyimpan path gambar reward
        ]);
    
        // Redirect dengan pesan sukses
        return redirect()->route('admin.rewards.index')->with('success', 'Reward berhasil ditambahkan!');
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(Request $request, string $id)
    {
        $reward = Reward::findOrFail($id);

        // Validasi input
        $request->validate([
            'nama_reward' => 'required|string|max:255',
            'foto_rewards' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',  // Foto opsional saat update
            'points_required' => 'required|integer|min:1',
            'deskripsi' => 'required|string',
        ]);

        // Jika ada file gambar baru, proses upload dan hapus gambar lama
        if ($request->hasFile('foto_rewards')) {
            // Hapus file lama jika ada
            if ($reward->foto_rewards && Storage::disk('public')->exists($reward->foto_rewards)) {
                Storage::disk('public')->delete($reward->foto_rewards);
            }

            // Simpan gambar baru
            $file = $request->file('foto_rewards');
            $fileName = time() . "_" . $file->getClientOriginalName();
            $imagePath = $file->storeAs('foto_rewards', $fileName, 'public');
            $reward->foto_rewards = $imagePath;
        }

        // Update data reward
        $reward->nama_reward = $request->nama_reward;
        $reward->points_required = $request->points_required;
        $reward->deskripsi = $request->deskripsi;
        $reward->save();

        return redirect()->route('admin.rewards.index')->with('success', 'Reward berhasil diperbarui!');
    }


    /**
     * Remove the specified resource from storage.
     */
   public function destroy(string $id)
{
    $reward = Reward::findOrFail($id);

    // Hapus gambar terkait jika ada
    if ($reward->foto_reward) {
        Storage::disk('public')->delete('foto_reward/' . $reward->foto_reward);
    }

    // Hapus produk dari database
    $reward->delete();

    return redirect()->route('admin.rewards.index')->with('success', 'Produk berhasil dihapus!');
}
}
