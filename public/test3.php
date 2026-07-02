<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$proyek = \App\Models\P5Proyek::with('targetSubElemens.elemen')->find(2);
$targets = $proyek->targetSubElemens;
echo "Pluck Result: " . json_encode($targets->pluck('elemen.dimensi_id')) . "\n";
foreach($targets as $t) {
    echo "SubElemen ID: " . $t->id . " | Elemen ID: " . $t->p5_elemen_id . " | Elemen Object Dimensi ID: " . ($t->elemen ? $t->elemen->dimensi_id : 'null') . "\n";
}
