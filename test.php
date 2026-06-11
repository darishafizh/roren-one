<?php require "vendor/autoload.php"; $app = require_once "bootstrap/app.php"; $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap(); 
$routes = Route::getRoutes(); 
foreach($routes as $r) { 
    if(str_contains($r->uri, "operasional")) echo $r->uri . " => " . $r->getActionName() . "\n"; 
} ?>
