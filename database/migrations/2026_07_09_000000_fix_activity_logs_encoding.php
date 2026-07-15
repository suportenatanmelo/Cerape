<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Map of common bad encodings/variants to correct values
        $replacements = [
            'AutenticaÃ§Ã£o' => 'Autenticação',
            'Autentica��o' => 'Autenticação',
            'Autenticacao' => 'Autenticação',
        ];

        foreach ($replacements as $bad => $good) {
            DB::table('activity_logs')
                ->where('module', 'like', "%{$bad}%")
                ->orWhere('module', $bad)
                ->update(['module' => DB::raw("REPLACE(module, '" . addslashes($bad) . "', '" . addslashes($good) . "')")]);
        }

        // Also fix common action/display texts if needed
        DB::table('activity_logs')
            ->where('action', 'Autenticacao')
            ->orWhere('action', 'Autentica��o')
            ->update(['action' => 'Autenticação']);
    }

    public function down(): void
    {
        // No-op: undoing encoding fixes isn't safe
    }
};
