<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::latest()->paginate(10);

        return view('admin.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.category.create');
    }


    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    // Validasi input
    $request->validate([
        'name' => 'required|string',
        'foto_kategori' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
    ]);

    // Simpan file gambar ke storage/app/public/foto_kategori
    $fotoPath = null;
    if ($request->hasFile('foto_kategori')) {
        $fotoPath = $request->file('foto_kategori')->store('foto_kategori', 'public');
    }

    // Simpan data ke database
    Category::create([
        'name' => $request->name,
        'foto_kategori' => $fotoPath,
    ]);

    // Redirect dengan pesan sukses
    return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan!');
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
    $categories = Category::findOrFail($id);

    // Validasi input
    $request->validate([
        'name' => 'required|string',
        'foto_kategori' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
    ]);

    $data = [
        'name' => $request->name,
    ];

    // Jika ada file gambar baru, simpan dan timpa yang lama
    if ($request->hasFile('foto_kategori')) {
        // Optional: hapus file lama dari storage (jika perlu)
        if ($categories->foto_kategori && Storage::disk('public')->exists($categories->foto_kategori)) {
            Storage::disk('public')->delete($categories->foto_kategori);
        }

        // Simpan file baru
        $fotoPath = $request->file('foto_kategori')->store('foto_kategori', 'public');
        $data['foto_kategori'] = $fotoPath;
    }

    // Update data
    $categories->update($data);

    return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui!');
}


    /**
     * Remove the specified resource from storage.
     */
public function destroy(string $id)
{
    $categories = Category::findOrFail($id);

    // Hapus gambar jika ada
    if ($categories->foto_kategori && Storage::disk('public')->exists($categories->foto_kategori)) {
        Storage::disk('public')->delete($categories->foto_kategori);
    }

    // Hapus kategori dari database
    $categories->delete();

    return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus!');
}

public function search(Request $request)
{
    $keyword = $request->name;

    $results = Category::where('name', 'like', "%{$keyword}%")
        ->get(['id', 'name']);

    return response()->json($results);
}


}
    