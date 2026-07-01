<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'phone_whatsapp')) {
                $table->string('phone_whatsapp', 20)->nullable()->after('email');
            }

            if (! Schema::hasColumn('users', 'cpf')) {
                $table->string('cpf', 14)->nullable()->unique()->after('phone_whatsapp');
            }

            if (! Schema::hasColumn('users', 'address')) {
                $table->string('address')->nullable()->after('cpf');
            }

            if (! Schema::hasColumn('users', 'permission_status')) {
                $table->string('permission_status', 30)
                    ->default(User::PERMISSION_STATUS_ACTIVE)
                    ->after('is_blocked');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'permission_status')) {
                $table->dropColumn('permission_status');
            }

            if (Schema::hasColumn('users', 'address')) {
                $table->dropColumn('address');
            }

            if (Schema::hasColumn('users', 'cpf')) {
                $table->dropUnique(['cpf']);
                $table->dropColumn('cpf');
            }

            if (Schema::hasColumn('users', 'phone_whatsapp')) {
                $table->dropColumn('phone_whatsapp');
            }
        });
    }
};
