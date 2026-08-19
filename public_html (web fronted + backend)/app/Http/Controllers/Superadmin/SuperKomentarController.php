<?php

namespace App\Http\Controllers\SuperAdmin;
use App\Models\Komentar;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SuperKomentarController extends Controller
{
    public function index(Request $request)
{
    $query = Komentar::query();

    // Search
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('nama_lengkap', 'like', '%' . $search . '%')
              ->orWhere('email', 'like', '%' . $search . '%')
              ->orWhere('no_hp', 'like', '%' . $search . '%')
              ->orWhere('pesan', 'like', '%' . $search . '%');
        });
    }

    // Filter by date
    if ($request->has('filter')) {
        switch ($request->filter) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', Carbon::now()->month);
                break;
        }
    }

    $komentars = $query->latest()->paginate(10);

    return view('superadmin.komentars.index', compact('komentars'));
}

    public function destroy(string $id)
    {
        $komentar = Komentar::findOrFail($id);
    
    
        // Hapus produk dari database
        $komentar->delete();
    
        return redirect()->route('superadmin.komentars.index')->with('komentar', 'Produk berhasil dihapus!');
    }
}
