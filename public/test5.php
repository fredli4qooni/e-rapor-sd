<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$proyek = \App\Models\P5Proyek::with('targetSubElemens.elemen')->find(2);
echo json_encode($proyek->targetSubElemens->pluck('elemen.p5_dimensi_id')->filter()->unique());
