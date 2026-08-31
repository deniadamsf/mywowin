<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$artikels = \App\Models\Artikel::all();
echo "=== CURRENT ARTICLES (" . $artikels->count() . ") ===\n";
foreach ($artikels as $a) {
    echo "ID: {$a->id} | Judul: {$a->judul}\n";
}
