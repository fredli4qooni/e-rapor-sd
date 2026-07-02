<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$p = \App\Models\P5Proyek::find(2); 
$targets = $p->targetSubElemens; 
echo json_encode($targets->pluck('elemen.dimensi_id'));
