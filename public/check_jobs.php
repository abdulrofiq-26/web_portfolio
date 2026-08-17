<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$jobs = \Illuminate\Support\Facades\DB::table('jobs')->get();
echo "Jobs in queue: " . $jobs->count() . "\n";
foreach($jobs as $job) {
    echo $job->payload . "\n";
}
