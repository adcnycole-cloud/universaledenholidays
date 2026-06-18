<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$products = App\Models\Product::where('category', 'package')->where('is_active', true)->orderBy('duration')->get(['id','name','duration']);
foreach ($products as $product) {
    echo $product->id . ' | ' . ($product->duration ?? 'NULL') . ' | ' . $product->name . PHP_EOL;
}
