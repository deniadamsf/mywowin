<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artikel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class ArtikelController extends Controller
{
    
    
    public function index(Request $request)
    {
        $search = $request->input('search');
        $dateFilter = $request->input('date_filter');
    
        $artikels = Artikel::with('user')
            ->when($search, function ($query, $search) {
                $query->where('judul', 'like', '%' . $search . '%')
                      ->orWhere('isi', 'like', '%' . $search . '%');
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
    
        return view('admin.artikels.index', compact('artikels', 'search', 'dateFilter'));
    }
    

    public function create()
    {
        return view('admin.artikels.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'foto_artikel' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_artikel')) {
            $fotoPath = $request->file('foto_artikel')->store('foto_artikel', 'public');
        }

        Artikel::create([
            'id' => Str::uuid(),
            'user_id' => Auth::id(),
            'judul' => $request->judul,
            'isi' => $request->isi,
            'foto_artikel' => $fotoPath,
        ]);

        return redirect()->route('admin.artikels.index')->with('success', 'Artikel berhasil ditambahkan!');
    }

    public function edit(Artikel $artikel)
    {
       
    }

    public function update(Request $request, Artikel $artikel)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'foto_artikel' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if ($request->hasFile('foto_artikel')) {
            if ($artikel->foto_artikel) {
                Storage::disk('public')->delete($artikel->foto_artikel);
            }

            $artikel->foto_artikel = $request->file('foto_artikel')->store('foto_artikel', 'public');
        }

        $artikel->update([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'foto_artikel' => $artikel->foto_artikel,
        ]);

        return redirect()->route('admin.artikels.index')->with('success', 'Artikel berhasil diperbarui!');
    }

    public function destroy(Artikel $artikel)
    {
        if ($artikel->foto_artikel) {
            Storage::disk('public')->delete($artikel->foto_artikel);
        }

        $artikel->delete();

        return redirect()->route('admin.artikels.index')->with('success', 'Artikel berhasil dihapus!');
    }
}
