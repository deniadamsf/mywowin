<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class AdminReviewController extends Controller
{
    public function index(Request $request)
    {
        $admin = Auth::user();
        $kantorCabang = $admin->kantor_cabang;

        $query = Review::with(['user', 'product', 'order'])
            ->where('kantor_cabang', $kantorCabang);

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->latest()->paginate(15);

        return view('admin.reviews.index', compact('reviews', 'kantorCabang'));
    }

    public function reply(Request $request, $id)
    {
        $admin = Auth::user();
        $review = Review::where('id', $id)->where('kantor_cabang', $admin->kantor_cabang)->firstOrFail();

        $request->validate([
            'balasan_admin' => 'required|string|max:1000',
        ]);

        $review->update([
            'balasan_admin' => $request->balasan_admin,
            'balasan_admin_at' => now(),
        ]);

        return back()->with('success', 'Balasan ulasan berhasil dikirim!');
    }
}
