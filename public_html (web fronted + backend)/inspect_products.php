<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$products = \App\Models\Product::with('images')->get();
foreach ($products as $p) {
    $imgList = $p->images->pluck('image_path')->toArray();
    echo "Product #{$p->id_product}: {$p->nama_produk} (CatID: {$p->category_id}) -> Images: " . implode(', ', $imgList) . "\n";
}
