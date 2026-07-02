<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$k = \App\Models\P5Kelompok::find(2);
echo "Proyeks Count: " . $k->proyeks->count() . "\n";
foreach($k->proyeks as $p) {
    echo $p->nama_proyek . "\n";
}
