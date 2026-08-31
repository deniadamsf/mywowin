<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Review;
use App\Models\BranchSetting;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReviewController extends Controller
{
    /**
     * Submit reviews for items in an order
     */
    public function store(Request $request, $orderId)
    {
        $user = Auth::user();

        // Cari pesanan milik user
        $order = Order::with(['orderItems', 'user'])
            ->where('id', $orderId)
            ->where('user_id', $user->id)
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan atau bukan milik Anda.',
            ], 404);
        }

        // Cek status pesanan (harus selesai / completed / lunas / dikirim)
        $allowedStatuses = ['selesai', 'completed', 'lunas', 'dikirim'];
        if (!in_array(strtolower($order->status), $allowedStatuses)) {
            return response()->json([
                'success' => false,
                'message' => 'Ulasan hanya dapat diberikan setelah pesanan diterima / selesai.',
            ], 422);
        }

        // Cek apakah pesanan sudah pernah diulas
        $existingReviews = Review::where('order_id', $order->id)->where('user_id', $user->id)->count();
        if ($existingReviews > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan ini sudah pernah diberi ulasan.',
            ], 422);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:1000',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'is_anonymous' => 'nullable|boolean',
            'foto' => 'nullable|array',
            'foto.*' => 'nullable|string', // base64 string or url
            'items' => 'nullable|array', // opsional jika review per produk spesifik
            'items.*.product_id' => 'nullable|integer',
            'items.*.bundling_id' => 'nullable|integer',
            'items.*.rating' => 'nullable|integer|min:1|max:5',
            'items.*.komentar' => 'nullable|string|max:1000',
        ]);

        $kantorCabang = $user->kantor_cabang;

        // Ambil link Google Maps Review cabang untuk respon
        $branchSetting = BranchSetting::where('enum_value', $kantorCabang)->first();
        $googleMapsReviewUrl = $branchSetting ? $branchSetting->google_maps_review_url : null;

        // Simpan foto ulasan jika ada (support base64)
        $savedPhotos = [];
        if (!empty($request->foto) && is_array($request->foto)) {
            foreach ($request->foto as $index => $photoBase64) {
                if (empty($photoBase64)) continue;
                
                if (preg_match('/^data:image\/(\w+);base64,/', $photoBase64, $type)) {
                    $photoBase64 = substr($photoBase64, strpos($photoBase64, ',') + 1);
                    $type = strtolower($type[1]); // jpg, png, jpeg
                    if (!in_array($type, ['jpg', 'jpeg', 'png', 'webp'])) {
                        $type = 'jpg';
                    }
                    $photoData = base64_decode($photoBase64);
                    if ($photoData !== false) {
                        $fileName = 'reviews/' . uniqid('rev_') . '_' . time() . '.' . $type;
                        Storage::disk('public')->put($fileName, $photoData);
                        $savedPhotos[] = $fileName;
                    }
                } elseif (str_starts_with($photoBase64, 'reviews/')) {
                    $savedPhotos[] = $photoBase64;
                }
            }
        }

        // Support multipart file upload langsung
        if ($request->hasFile('foto_files')) {
            foreach ($request->file('foto_files') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('reviews', 'public');
                    $savedPhotos[] = $path;
                }
            }
        }

        DB::beginTransaction();
        try {
            // Jika ada items spesifik
            if (!empty($validated['items']) && is_array($validated['items'])) {
                foreach ($validated['items'] as $itemData) {
                    Review::create([
                        'order_id' => $order->id,
                        'user_id' => $user->id,
                        'product_id' => $itemData['product_id'] ?? null,
                        'bundling_id' => $itemData['bundling_id'] ?? null,
                        'kantor_cabang' => $kantorCabang,
                        'rating' => $itemData['rating'] ?? $validated['rating'],
                        'komentar' => $itemData['komentar'] ?? $validated['komentar'],
                        'tags' => $validated['tags'] ?? [],
                        'foto' => $savedPhotos,
                        'is_anonymous' => $validated['is_anonymous'] ?? false,
                        'is_hidden' => false,
                    ]);
                }
            } else {
                // Buat ulasan untuk setiap produk di dalam order_items
                if ($order->orderItems && $order->orderItems->isNotEmpty()) {
                    foreach ($order->orderItems as $orderItem) {
                        Review::create([
                            'order_id' => $order->id,
                            'user_id' => $user->id,
                            'product_id' => $orderItem->product_id,
                            'bundling_id' => $orderItem->bundling_id ?? null,
                            'kantor_cabang' => $kantorCabang,
                            'rating' => $validated['rating'],
                            'komentar' => $validated['komentar'],
                            'tags' => $validated['tags'] ?? [],
                            'foto' => $savedPhotos,
                            'is_anonymous' => $validated['is_anonymous'] ?? false,
                            'is_hidden' => false,
                        ]);
                    }
                } else {
                    // Fallback jika tidak ada orderItems terikat
                    Review::create([
                        'order_id' => $order->id,
                        'user_id' => $user->id,
                        'product_id' => null,
                        'bundling_id' => null,
                        'kantor_cabang' => $kantorCabang,
                        'rating' => $validated['rating'],
                        'komentar' => $validated['komentar'],
                        'tags' => $validated['tags'] ?? [],
                        'foto' => $savedPhotos,
                        'is_anonymous' => $validated['is_anonymous'] ?? false,
                        'is_hidden' => false,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Terima kasih! Ulasan Anda berhasil disimpan.',
                'data' => [
                    'order_id' => $order->id,
                    'rating' => $validated['rating'],
                    'google_maps_review_url' => $googleMapsReviewUrl,
                    'kantor_cabang' => $kantorCabang,
                ],
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan ulasan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get review status and details for an order
     */
    public function showByOrder($orderId)
    {
        $user = Auth::user();

        $reviews = Review::with(['product', 'bundling'])
            ->where('order_id', $orderId)
            ->where('user_id', $user->id)
            ->get();

        $branchSetting = BranchSetting::where('enum_value', $user->kantor_cabang)->first();

        return response()->json([
            'success' => true,
            'has_reviewed' => $reviews->isNotEmpty(),
            'reviews_count' => $reviews->count(),
            'google_maps_review_url' => $branchSetting ? $branchSetting->google_maps_review_url : null,
            'reviews' => $reviews->map(function ($rev) {
                return [
                    'id' => $rev->id,
                    'product_id' => $rev->product_id,
                    'product_name' => $rev->product ? $rev->product->nama_produk : null,
                    'rating' => $rev->rating,
                    'komentar' => $rev->komentar,
                    'tags' => $rev->tags ?? [],
                    'foto' => collect($rev->foto ?? [])->map(function ($f) {
                        return str_starts_with($f, 'http') ? $f : asset('storage/' . $f);
                    }),
                    'is_anonymous' => (bool)$rev->is_anonymous,
                    'balasan_admin' => $rev->balasan_admin,
                    'balasan_admin_at' => $rev->balasan_admin_at ? $rev->balasan_admin_at->format('d M Y H:i') : null,
                    'created_at' => $rev->created_at->format('d M Y H:i'),
                ];
            }),
        ]);
    }

    /**
     * Get public reviews for a specific product
     */
    public function showByProduct(Request $request, $productId)
    {
        $product = Product::where('id_product', $productId)->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan.',
            ], 404);
        }

        $query = Review::with('user')
            ->where('product_id', $productId)
            ->where('is_hidden', false);

        // Filter berdasarkan rating jika ada parameter
        if ($request->has('rating') && in_array((int)$request->rating, [1, 2, 3, 4, 5])) {
            $query->where('rating', (int)$request->rating);
        }

        // Filter jika hanya ingin yang ada foto
        if ($request->boolean('with_photo')) {
            $query->whereNotNull('foto')->where('foto', '!=', '[]');
        }

        // Hitung statistik
        $totalReviews = Review::where('product_id', $productId)->where('is_hidden', false)->count();
        $avgRating = round(Review::where('product_id', $productId)->where('is_hidden', false)->avg('rating') ?: 5.0, 1);

        $distribution = [
            5 => Review::where('product_id', $productId)->where('is_hidden', false)->where('rating', 5)->count(),
            4 => Review::where('product_id', $productId)->where('is_hidden', false)->where('rating', 4)->count(),
            3 => Review::where('product_id', $productId)->where('is_hidden', false)->where('rating', 3)->count(),
            2 => Review::where('product_id', $productId)->where('is_hidden', false)->where('rating', 2)->count(),
            1 => Review::where('product_id', $productId)->where('is_hidden', false)->where('rating', 1)->count(),
        ];

        $reviews = $query->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'data' => [
                'product_id' => $product->id_product,
                'product_name' => $product->nama_produk,
                'average_rating' => $avgRating,
                'total_reviews' => $totalReviews,
                'rating_distribution' => $distribution,
                'reviews' => $reviews->map(function ($rev) {
                    $displayName = 'Pengguna Wowin';
                    if ($rev->user) {
                        $displayName = $rev->is_anonymous
                            ? Str::mask($rev->user->name, '*', 2, -1)
                            : $rev->user->name;
                    }

                    return [
                        'id' => $rev->id,
                        'user_name' => $displayName,
                        'kantor_cabang' => $rev->kantor_cabang,
                        'rating' => $rev->rating,
                        'komentar' => $rev->komentar,
                        'tags' => $rev->tags ?? [],
                        'foto' => collect($rev->foto ?? [])->map(function ($f) {
                            return str_starts_with($f, 'http') ? $f : asset('storage/' . $f);
                        }),
                        'balasan_admin' => $rev->balasan_admin,
                        'balasan_admin_at' => $rev->balasan_admin_at ? $rev->balasan_admin_at->format('d M Y H:i') : null,
                        'created_at' => $rev->created_at->format('d M Y, H:i'),
                    ];
                }),
                'pagination' => [
                    'current_page' => $reviews->currentPage(),
                    'last_page' => $reviews->lastPage(),
                    'per_page' => $reviews->perPage(),
                    'total' => $reviews->total(),
                ],
            ],
        ]);
    }
}
