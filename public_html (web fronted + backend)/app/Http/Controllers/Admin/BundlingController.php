<?php

namespace App\Http\Controllers\admin;
use App\Models\Bundling;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BundlingController extends Controller
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
    
        return view('admin.bundlings.index',  compact('bundlings', 'products'));
    }
    


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         $products = Product::all(); 
            return view('admin.bundlings.create', compact('products'));

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

    // Simpan data ke database dan simpan hasilnya ke variabel $bundling
    $bundling = Bundling::create([
        'nama_bundling' => $validated['nama_bundling'],
        'barang_bundling' => $imagePath,
        'waktu_diskon_mulai' => $validated['Waktu_diskon_mulai'],
        'waktu_diskon_selesai' => $validated['Waktu_diskon_selesai'],
        'snk' => $validated['snk'],
        'price' => $validated['price'],
        'price_before' => $validated['price_before'],
    ]);

    // // Sync produk yang dipilih ke pivot
    $bundling->products()->sync($request->products);

    // Redirect dengan pesan sukses
    return redirect()->route('admin.bundlings.index')->with('success', 'Produk bundlings berhasil ditambahkan!');
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
    
        // Update data produk
        $bundling->update([
            'nama_bundling' => $request->nama_bundling,
            'waktu_diskon_mulai' => $request->waktu_diskon_mulai,
            'waktu_diskon_selesai' => $request->waktu_diskon_selesai,
            'price' => $request->price,
            'price_before' => $request->price_before,
            'snk' => $request->snk,
            'products' => 'required|array',
            'products.*' => 'exists:products,id_product',
        ]);
        // Kalau ada upload foto
        if ($request->hasFile('barang_bundling')) {
            $path = $request->file('barang_bundling')->store('barang_bundling', 'public');
            $bundling->update(['barang_bundling' => $path]);
        }
        // 🔁 Sinkronisasi produk bundling
        $bundling->products()->sync($request->products);
        return redirect()->route('admin.bundlings.index')->with('success', 'Data admin berhasil diperbarui!');
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

    return redirect()->route('admin.bundlings.index')->with('success', 'Produk berhasil dihapus!');
}

}
