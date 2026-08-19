<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Komentar;
use Illuminate\Support\Carbon;

class PublicKomentarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   
    
    public function index(Request $request)
    {
        $search = $request->input('search');
        $dateFilter = $request->input('date_filter');
    
        $komentars = Komentar::query();
    
        // Filter pencarian
        if ($search) {
            $komentars->where('nama_lengkap', 'like', '%' . $search . '%');
        }
    
        // Filter tanggal
        if ($dateFilter === 'today') {
            $komentars->whereDate('created_at', Carbon::today());
        } elseif ($dateFilter === 'week') {
            $komentars->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($dateFilter === 'month') {
            $komentars->whereMonth('created_at', Carbon::now()->month);
        }
    
        $komentars = $komentars->latest()->paginate(10);
    
        return view('admin.komentars.index', compact('komentars', 'search', 'dateFilter'));
    }
    


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $komentar = Komentar::findOrFail($id);
    
    
        // Hapus produk dari database
        $komentar->delete();
    
        return redirect()->route('admin.komentars.index')->with('komentar', 'Produk berhasil dihapus!');
    }
}
