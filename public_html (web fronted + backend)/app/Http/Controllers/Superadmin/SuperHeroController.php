<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hero;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class SuperHeroController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $dateFilter = $request->input('date_filter');
    
        $heros = Hero::when($search, function ($query, $search) {
            return $query->where('nama_event', 'like', '%' . $search . '%');
        })->when($dateFilter, function ($query, $dateFilter) {
            if ($dateFilter === 'today') {
                return $query->whereDate('created_at', Carbon::today());
            } elseif ($dateFilter === 'week') {
                return $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            } elseif ($dateFilter === 'month') {
                return $query->whereMonth('created_at', Carbon::now()->month);
            }
        })->paginate(10);
    
        return view('superadmin.heros.index', compact('heros', 'search', 'dateFilter'));
    }
    
    
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('superadmin.heros.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_event' => 'required|string',
            'gambar_hero' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);
        // Proses unggah gambar
        $gambar_hero = null;
        if ($request->hasFile('gambar_hero')) {
            $file = $request->file("gambar_hero");
            $fileName = time() . "_" . $file->getClientOriginalName();
            $gambar_hero = $file->storeAs('gambar_hero', $fileName, 'public');
        }
          // Simpan data ke database
         Hero::create([
            'nama_event' => $request->nama_event,
            'gambar_hero' => $gambar_hero,
        ]);
         // Redirect dengan pesan sukses
         return redirect()->route('superadmin.heros.index')->with('success', 'Gambar hero berhasil ditambahkan!');
    
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
        $hero = Hero::findOrFail($id);
    
        // Update data produk
        $hero->update([
            'nama_event' => $request->nama_event,
        ]);
        // Kalau ada upload foto
        if ($request->hasFile('gambar_hero')) {
            $path = $request->file('gambar_hero')->store('gambar_hero', 'public');
            $hero->update(['gambar_hero' => $path]);
        }
        return redirect()->route('superadmin.heros.index')->with('success', 'Data superadmin berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $hero = Hero::findOrFail($id);

        // Hapus gambar terkait jika ada
        if ($hero->gambar_hero) {
            Storage::disk('public')->delete('hero/' . $hero->gambar_hero);
        }
    
        // Hapus produk dari database
        $hero->delete();
    
        return redirect()->route('superadmin.heros.index')->with('success', 'Produk berhasil dihapus!');
    }
}
