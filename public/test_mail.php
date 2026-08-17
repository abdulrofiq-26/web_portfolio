<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

try {
    \Illuminate\Support\Facades\Mail::raw('Test email', function ($message) {
        $message->to('webportfolio161@gmail.com')
                ->subject('Test Email');
    });
    echo "SUCCESS";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n\n";
    echo $e->getTraceAsString();
}
