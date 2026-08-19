<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product; // Panggil model Product
use App\Models\ProductImage; // Panggil model Product
use App\Models\Category; // Panggil model Product
use Illuminate\Support\Facades\Storage;



class SuperProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('images');
    
        // Filter pencarian
        if ($request->has('search') && !empty($request->search)) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }
    
        // Urutan produk
        if ($request->has('sort')) {
            if ($request->sort == 'latest') {
                $query->orderBy('created_at', 'desc');
            } elseif ($request->sort == 'oldest') {
                $query->orderBy('created_at', 'asc');
            }
        }
    
        $products = $query->paginate(10)->appends($request->all());
    
        return view('superadmin.products.index', compact('products'));
    }
    


        // Menampilkan form tambah produk
      public function create()
{
    $product = new Product(); // objek kosong
    return view('superadmin.products.create', compact('product'));
}

    // Menyimpan produk beserta gambarnya
    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'nullable|string|max:255',
            'isi_ml' => 'nullable|integer',
            'harga' => 'nullable|integer',
            'no_bpom' => 'nullable|string|max:255',
            'no_halal' => 'nullable|string|max:255',
            'isi_karton' => 'nullable|integer',
            'rekom_guna' => 'nullable|string|max:100',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
             'category_id' => 'nullable|exists:categories,id',
        ]);

        $product =Product::create([
            'nama_produk' => $request->input ('nama_produk'),
            'isi_ml' => $request->input ('isi_ml'),
            'harga' => $request->input ('harga'),
            'no_bpom' => $request->input ('no_bpom'),
            'no_halal' => $request->input('no_halal'),
            'isi_karton' => $request->input ('isi_karton'),
            'rekom_guna' => $request->input ('rekom_guna'),
            'category_id' => $request->input('category_id'),
        ]);
      

        if ($request->hasFile ('images')) {
            $isPrimary = true;
            foreach ($request->file ('images') as $image) {
                $imagePath = $image ->store ('product_images', 'public');
                ProductImage::create([
                    'product_id' =>$product->id_product,
                    'image_url' =>$imagePath,
                    'is_primary' => 1

                ]);
                $isPrimary = false;
            }
        }
        // dd($product->getKey());

      
        return redirect()->route('superadmin.products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    // Menampilkan produk beserta gambarnya
    public function show($id)
    {
        $product = Product::with('images')->findOrFail($id);
        // return view('admin.products.show', compact('product'));
    }

    public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);    
    

    // Update data produk
    $product->update([
        'nama_produk' => $request->nama_produk,
        'isi_ml' => $request->isi_ml,
        'harga' => $request->harga,
        'isi_karton' => $request->isi_karton,
        'no_bpom' => $request->no_bpom,
        'no_halal' => $request->no_halal,
        'rekom_guna' => $request->rekom_guna,
        'category_id' => $request->category_id,
    ]);

    // 🔁 Ganti gambar lama (jika ada input file untuk id gambar tersebut)
    if ($request->has('replace_image')) {
        foreach ($request->replace_image as $id => $file) {
            if ($file) {
                $oldImage = ProductImage::find($id);
                if ($oldImage) {
                    Storage::disk('public')->delete($oldImage->image_url); // Hapus lama
                    $newPath = $file->store('product_images', 'public');

                    $oldImage->update([
                        'image_url' => $newPath
                    ]);
                }
            }
        }
    }

    // 🗑️ Hapus gambar jika dicentang
    if ($request->filled('hapus')) {
        foreach ($request->hapus as $id) {
            $image = ProductImage::find($id);
            if ($image) {
                Storage::disk('public')->delete($image->image_url);
                $image->delete();
            }
        }
    }

    // ➕ Tambah gambar baru (jika diupload)
    if ($request->hasFile('product_images')) {
        foreach ($request->file('product_images') as $file) {
            $path = $file->store('product_images', 'public');

            ProductImage::create([
                'product_id' => $product->id_product,
                'image_url' => $path,
                'is_primary' => 0,
            ]);
        }

        // Jika tidak ada primary, jadikan salah satu sebagai primary
        if (!$product->images()->where('is_primary', 1)->exists()) {
            $firstImage = $product->images()->first();
            if ($firstImage) {
                $firstImage->update(['is_primary' => 1]);
            }
        }
    }
    

    return redirect()->route('superadmin.products.index')->with('success', 'Produk berhasil diperbarui.');
}

    

public function destroy($id)
{
    $product = Product::findOrFail($id);
    
    // Hapus semua gambar terkait dari storage dan database
    foreach ($product->images as $image) {
        Storage::disk('public')->delete($image->image_url);
        $image->delete();
    }

    // Hapus produk dari database
    $product->delete();

    return redirect()->route('superadmin.products.index')->with('success', 'Produk berhasil dihapus!');
}


}