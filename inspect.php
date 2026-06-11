<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$cols = DB::connection('mysql_tahap_konstruksi')->select('SHOW COLUMNS FROM tahap_konstruksi');
foreach ($cols as $c) {
    echo "{$c->Field} | {$c->Type}" . PHP_EOL;
}



