<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;

class SuperReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product', 'order']);

        if ($request->filled('kantor_cabang')) {
            $query->where('kantor_cabang', $request->kantor_cabang);
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->latest()->paginate(20);

        return view('superadmin.reviews.index', compact('reviews'));
    }

    public function reply(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        $request->validate([
            'balasan_admin' => 'required|string|max:1000',
        ]);

        $review->update([
            'balasan_admin' => $request->balasan_admin,
            'balasan_admin_at' => now(),
        ]);

        return back()->with('success', 'Balasan ulasan berhasil disimpan!');
    }

    public function toggleVisibility($id)
    {
        $review = Review::findOrFail($id);
        $review->update([
            'is_hidden' => !$review->is_hidden,
        ]);

        $status = $review->is_hidden ? 'disembunyikan' : 'ditampilkan ke publik';
        return back()->with('success', "Ulasan berhasil $status.");
    }
}
