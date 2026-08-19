<?php

namespace App\Http\Controllers\Superadmin;
use App\Models\Bundling;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuperBundlingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Bundling::query();
        
    
        // Filter search
        if ($request->filled('search')) {
            $query->where('nama_bundling', 'like', '%' . $request->search . '%');
        }
    
        // Filter status waktu diskon
        if ($request->filled('status')) {
            $today = now();
    
            switch ($request->status) {
                case 'active':
                    $query->where('waktu_diskon_mulai', '<=', $today)
                          ->where('waktu_diskon_selesai', '>=', $today);
                    break;
    
                case 'upcoming':
                    $query->where('waktu_diskon_mulai', '>', $today);
                    break;
    
                case 'expired':
                    $query->where('waktu_diskon_selesai', '<', $today);
                    break;
            }
        }
    
         $bundlings = $query->with('products')->paginate(10)->appends($request->all()); // pastikan relasi juga dimuat
            $products = Product::all(); // ✅ Tambahkan ini
    
        return view('superadmin.bundlings.index',  compact('bundlings', 'products'));
    }
    


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         $products = Product::all(); 
            return view('superadmin.bundlings.create', compact('products'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validasi input
    $validated = $request->validate([
        'nama_bundling' => 'required|string',
        'barang_bundling' => 'required|image|mimes:jpeg,png,jpg,gif,webp',
        'youtube_link' => 'nullable|url',
        'Waktu_diskon_mulai' => 'required|date',
        'Waktu_diskon_selesai' => 'required|date|after:Waktu_diskon_mulai',
        'snk' => 'required|string',
        'price' => 'required|numeric|min:0',
        'price_before' => 'required|numeric|min:0',
        'products' => 'required|array|min:1',
        'products.*' => 'exists:products,id_product',

    ]);

    // Proses unggah gambar
    $imagePath = null;
    if ($request->hasFile('barang_bundling')) {
        $file = $request->file("barang_bundling");
        $fileName = time() . "_" . $file->getClientOriginalName();
        $imagePath = $file->storeAs('barang_bundling', $fileName, 'public');
    }

  $youtubeLink = $this->convertToEmbed($validated['youtube_link'] ?? null);



    // Simpan data ke database dan simpan hasilnya ke variabel $bundling
    $bundling = Bundling::create([
        'nama_bundling' => $validated['nama_bundling'],
        'barang_bundling' => $imagePath,
        'youtube_link' => $youtubeLink,
        'waktu_diskon_mulai' => $validated['Waktu_diskon_mulai'],
        'waktu_diskon_selesai' => $validated['Waktu_diskon_selesai'],
        'snk' => $validated['snk'],
        'price' => $validated['price'],
        'price_before' => $validated['price_before'],
    ]);

    // // Sync produk yang dipilih ke pivot
    $bundling->products()->sync($request->products);

    // Redirect dengan pesan sukses
    return redirect()->route('superadmin.bundlings.index')->with('success', 'Produk bundlings berhasil ditambahkan!');
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
        $bundling = Bundling::findOrFail($id);
    
        $validated = $request->validate([
    'nama_bundling' => 'required|string',
    'waktu_diskon_mulai' => 'required|date',
    'waktu_diskon_selesai' => 'required|date|after:waktu_diskon_mulai',
    'price' => 'required|numeric|min:0',
    'price_before' => 'required|numeric|min:0',
    'snk' => 'required|string',
    'products' => 'required|array',
    'products.*' => 'exists:products,id_product',
    'youtube_link' => 'nullable|url',
]);
 $youtubeLink = $this->convertToEmbed($validated['youtube_link'] ?? null);
// Setelah validasi, proses update:
$bundling->update([
    'nama_bundling' => $validated['nama_bundling'],
    'waktu_diskon_mulai' => $validated['waktu_diskon_mulai'],
    'waktu_diskon_selesai' => $validated['waktu_diskon_selesai'],
    'price' => $validated['price'],
    'price_before' => $validated['price_before'],
    'snk' => $validated['snk'],
   'youtube_link' => $youtubeLink,
]);
        // Kalau ada upload foto
        if ($request->hasFile('barang_bundling')) {
            $path = $request->file('barang_bundling')->store('barang_bundling', 'public');
            $bundling->update(['barang_bundling' => $path]);
        }

   
        // 🔁 Sinkronisasi produk bundling
        $bundling->products()->sync($request->products);
        return redirect()->route('superadmin.bundlings.index')->with('success', 'Data admin berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
{
    $bundling = Bundling::findOrFail($id);

    // Hapus gambar terkait jika ada
    if ($bundling->barang_bundling) {
        Storage::disk('public')->delete('barang_bundling/' . $bundling->barang_bundling);
    }

    // Hapus produk dari database
    $bundling->delete();

    return redirect()->route('superadmin.bundlings.index')->with('success', 'Produk berhasil dihapus!');
}
private function convertToEmbed(?string $link): ?string
{
    if (!$link) return null;

    // Hapus semua query string dan fragment setelah video ID
    // Contoh: https://youtu.be/bbW5OPLcn-8?si=xxx -> ambil bbW5OPLcn-8 saja

    // Parsing URL
    $urlParts = parse_url($link);

    $videoId = null;

    if (isset($urlParts['host'])) {
        if (str_contains($urlParts['host'], 'youtu.be')) {
            // Pathnya: /bbW5OPLcn-8
            $videoId = ltrim($urlParts['path'], '/');
        } elseif (str_contains($urlParts['host'], 'youtube.com')) {
            if (isset($urlParts['query'])) {
                parse_str($urlParts['query'], $queryParams);
                if (isset($queryParams['v'])) {
                    $videoId = $queryParams['v'];
                }
            }
            // Jika URL sudah embed, misal /embed/VIDEOID
            if (!$videoId && isset($urlParts['path'])) {
                $parts = explode('/', trim($urlParts['path'], '/'));
                $embedIndex = array_search('embed', $parts);
                if ($embedIndex !== false && isset($parts[$embedIndex + 1])) {
                    $videoId = $parts[$embedIndex + 1];
                }
            }
        }
    }

    if (!$videoId) return null;

    return "https://www.youtube.com/embed/{$videoId}?autoplay=1&mute=1";
}



}
