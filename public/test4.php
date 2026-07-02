<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$sub = \App\Models\P5SubElemen::find(1);
echo "SubElemen 1: " . json_encode($sub) . "\n";
echo "Elemen Relation: " . json_encode($sub->elemen) . "\n";
