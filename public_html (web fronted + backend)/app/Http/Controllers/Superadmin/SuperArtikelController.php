<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artikel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class SuperArtikelController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $dateFilter = $request->input('date_filter');
    
        $artikels = Artikel::with('user')
            ->when($search, function ($query, $search) {
                $query->where('judul', 'like', '%' . $search . '%');
                // Catatan: Jika 'isi' berbentuk JSON, pencarian 'like' mungkin tidak akurat
            })
            ->when($dateFilter, function ($query, $dateFilter) {
                if ($dateFilter === 'today') {
                    $query->whereDate('created_at', Carbon::today());
                } elseif ($dateFilter === 'week') {
                    $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                } elseif ($dateFilter === 'month') {
                    $query->whereMonth('created_at', Carbon::now()->month);
                }
            })
            ->latest()
            ->paginate(10);
    
        return view('superadmin.artikels.index', compact('artikels', 'search', 'dateFilter'));
    }
    public function create()
    {
        return view('superadmin.artikels.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'judul' => 'required|string|max:255',
        'slug' => 'nullable|string|max:255',
        'foto_utama' => 'required|image|max:2048',
        'tipe_blok' => 'required|array',
        'konten_isi.*' => 'nullable',
        'konten_gambar.*' => 'nullable|image|max:2048',
    ]);

    // 1. Simpan Foto Utama & Masukkan ke daftar semua foto
    $fotoUtamaPath = $request->file('foto_utama')->store('foto_artikel', 'public');
    $semuaFoto = [$fotoUtamaPath]; // Mulai array dengan foto utama

    // 2. Proses blok konten
    $contentData = [];
    $imageIndex = 0; 

    foreach ($request->tipe_blok as $key => $tipe) {
        if ($tipe === 'teks') {
            $contentData[] = [
                'type' => 'text',
                'value' => $request->konten_isi[$key]
            ];
        } elseif ($tipe === 'gambar') {
            if ($request->hasFile('konten_gambar') && isset($request->file('konten_gambar')[$imageIndex])) {
                $path = $request->file('konten_gambar')[$imageIndex]->store('foto_artikel', 'public');
                
                $contentData[] = [
                    'type' => 'image',
                    'value' => $path
                ];
                
                // PENTING: Tambahkan path gambar sisipan ke array semua foto
                $semuaFoto[] = $path; 
                
                $imageIndex++;
            }
        }
    }

    $slug = $request->filled('slug') ? Str::slug($request->slug) : Str::slug($request->judul);
    $slug = Artikel::generateUniqueSlug($slug);

    Artikel::create([
        'id' => (string) Str::uuid(),
        'user_id' => Auth::id(),
        'judul' => $request->judul,
        'slug' => $slug,
        'isi' => $contentData, 
        'foto_artikel' => $semuaFoto, // Simpan array yang berisi SEMUA gambar
    ]);

    return redirect()->route('superadmin.artikels.index')->with('success', 'Artikel berhasil ditambahkan!');
}
public function update(Request $request, Artikel $artikel)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'foto_artikel.*' => 'nullable|image',
            'replace_image_block.*' => 'nullable|image',
            'konten_isi.*' => 'nullable',
            'tipe_blok' => 'required|array',
        ]);

        $fotoSekarang = is_array($artikel->foto_artikel) ? $artikel->foto_artikel : [];

        // 1. Logika Hapus Gambar via removed_images (Alpine.js)
        if ($request->has('removed_images')) {
            $imagesToDelete = array_filter($request->removed_images);
            $fotoSekarang = array_diff($fotoSekarang, $imagesToDelete);
            
            foreach ($imagesToDelete as $path) {
                if ($path && Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }

        // 2. Proses Blok Konten & Sync Foto Galeri
        $newIsi = [];
        foreach ($request->tipe_blok as $index => $type) {
            $value = $request->konten_isi[$index] ?? '';

            if ($type === 'image' || $type === 'gambar') {
                // Jika ada ganti gambar di blok tertentu
                if ($request->hasFile("replace_image_block.$index")) {
                    // Hapus gambar lama di storage
                    if ($value && Storage::disk('public')->exists($value)) {
                        Storage::disk('public')->delete($value);
                    }
                    // Simpan gambar baru
                    $value = $request->file("replace_image_block.$index")->store('foto_artikel', 'public');
                    $fotoSekarang[] = $value;
                }
            }

            $newIsi[] = [
                'type' => ($type === 'teks' || $type === 'text') ? 'text' : 'image',
                'value' => $value
            ];
        }

        // 3. Tambah Foto Galeri Baru (Upload tambahan)
        if ($request->hasFile('foto_artikel')) {
            foreach ($request->file('foto_artikel') as $file) {
                $fotoSekarang[] = $file->store('foto_artikel', 'public');
            }
        }

        $slug = $request->filled('slug') ? Str::slug($request->slug) : ($artikel->slug ?: Str::slug($request->judul));
        $slug = Artikel::generateUniqueSlug($slug, $artikel->id);

        $artikel->update([
            'judul' => $request->judul,
            'slug' => $slug,
            'isi' => $newIsi,
            'foto_artikel' => array_values(array_unique(array_filter($fotoSekarang))),
        ]);

        return redirect()->route('superadmin.artikels.index')->with('success', 'Artikel berhasil diperbarui!');
    }

    public function destroy(Artikel $artikel)
    {
        // Hapus semua foto di galeri
        if (is_array($artikel->foto_artikel)) {
            foreach ($artikel->foto_artikel as $foto) {
                Storage::disk('public')->delete($foto);
            }
        }

        // Hapus foto di dalam blok isi jika ada
        $isi = is_array($artikel->isi) ? $artikel->isi : json_decode($artikel->isi, true);
        if (is_array($isi)) {
            foreach ($isi as $block) {
                if ($block['type'] === 'image') {
                    Storage::disk('public')->delete($block['value']);
                }
            }
        }

        $artikel->delete();
        return redirect()->route('superadmin.artikels.index')->with('success', 'Artikel berhasil dihapus!');
    }
}