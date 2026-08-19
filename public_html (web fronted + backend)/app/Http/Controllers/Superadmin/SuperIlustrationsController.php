<?php

namespace App\Http\Controllers\Superadmin;

use App\Models\Ilustration;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class SuperIlustrationsController extends Controller
{
    public function index()
    {
        $ilustrations = Ilustration::paginate(10); // Ambil semua data bundlings dari database
        return view('superadmin.ilustrations.index', compact('ilustrations')); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('superadmin.ilustrations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_event' => 'required|string',
            'gambar_login' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);
        // Proses unggah gambar
        $gambar_login = null;
        if ($request->hasFile('gambar_login')) {
            $file = $request->file("gambar_login");
            $fileName = time() . "_" . $file->getClientOriginalName();
            $gambar_login = $file->storeAs('gambar_login', $fileName, 'public');
        }
          // Simpan data ke database
         Ilustration::create([
            'nama_event' => $request->nama_event,
            'gambar_login' => $gambar_login,
        ]);
         // Redirect dengan pesan sukses
         return redirect()->route('superadmin.ilustrations.index')->with('success', 'Gambar Ilustrations berhasil ditambahkan!');
    
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
        $ilustration = Ilustration::findOrFail($id);
    
        // Update data produk
        $ilustration->update([
            'nama_event' => $request->nama_event,
        ]);
        // Kalau ada upload foto
        if ($request->hasFile('gambar_login')) {
            $path = $request->file('gambar_login')->store('gambar_login', 'public');
            $ilustration->update(['gambar_login' => $path]);
        }
        return redirect()->route('superadmin.ilustrations.index')->with('success', 'Data Superadmin berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ilustration = Ilustration::findOrFail($id);

        // Hapus gambar terkait jika ada
        if ($ilustration->gambar_login) {
            Storage::disk('public')->delete('ilustration/' . $ilustration->gambar_login);
        }
    
        // Hapus produk dari database
        $ilustration->delete();
    
        return redirect()->route('superadmin.ilustrations.index')->with('success', 'Produk berhasil dihapus!');
    }
}
