<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
Illuminate\Support\Facades\Schema::setFacadeApplication($app);
if (! Illuminate\Support\Facades\Schema::hasTable('acolhidos')) {
    echo "no_table\n";
    exit(0);
}
$cols = Illuminate\Support\Facades\Schema::getColumnListing('acolhidos');
sort($cols);
foreach ($cols as $col) {
    echo $col . "\n";
}
