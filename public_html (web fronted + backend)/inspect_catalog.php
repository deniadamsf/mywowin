<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CATEGORIES ===\n";
foreach (\App\Models\Category::all() as $c) {
    echo "ID: {$c->id} | Name: {$c->name} | Image: {$c->foto_kategori}\n";
}

echo "\n=== PRODUCTS ===\n";
foreach (\App\Models\Product::select('id_product', 'nama_product', 'category_id', 'gambar')->get() as $p) {
    echo "ID: {$p->id_product} | Name: {$p->nama_product} | CatID: {$p->category_id} | Image: {$p->gambar}\n";
}
