<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$u = App\Models\User::whereNotNull('acolhido_id')->with('roles.permissions')->first();
if (! $u) {
    echo "NO_LINKED_USER\n";
    exit(0);
}
echo 'USER=' . $u->email . PHP_EOL;
echo 'ACOLHIDO_ID=' . $u->acolhido_id . PHP_EOL;
echo 'ROLES=' . $u->roles->pluck('name')->implode(',') . PHP_EOL;
echo 'PERMS=' . $u->getAllPermissions()->pluck('name')->implode(',') . PHP_EOL;
?>
