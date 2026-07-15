<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('module')->nullable()->index();
                $table->string('action')->nullable()->index();
                $table->text('description')->nullable();
                $table->string('model_type')->nullable()->index();
                $table->unsignedBigInteger('model_id')->nullable();
                $table->longText('old_values')->nullable();
                $table->longText('new_values')->nullable();
                $table->string('ip')->nullable();
                $table->string('browser')->nullable();
                $table->string('platform')->nullable();
                $table->string('device')->nullable();
                $table->string('url')->nullable();
                $table->string('method')->nullable();
                $table->string('session_id')->nullable()->index();
                $table->timestamp('executed_at')->nullable()->index();
                $table->timestamps();
            });

            return;
        }

        $this->addColumnIfMissing('activity_logs', 'module', fn (Blueprint $table) => $table->string('module')->nullable()->after('user_id'));
        $this->addColumnIfMissing('activity_logs', 'action', fn (Blueprint $table) => $table->string('action')->nullable()->after('module'));
        $this->addColumnIfMissing('activity_logs', 'description', fn (Blueprint $table) => $table->text('description')->nullable()->after('action'));
        $this->addColumnIfMissing('activity_logs', 'model_type', fn (Blueprint $table) => $table->string('model_type')->nullable()->after('description'));
        $this->addColumnIfMissing('activity_logs', 'model_id', fn (Blueprint $table) => $table->unsignedBigInteger('model_id')->nullable()->after('model_type'));
        $this->addColumnIfMissing('activity_logs', 'old_values', fn (Blueprint $table) => $table->longText('old_values')->nullable()->after('model_id'));
        $this->addColumnIfMissing('activity_logs', 'new_values', fn (Blueprint $table) => $table->longText('new_values')->nullable()->after('old_values'));
        $this->addColumnIfMissing('activity_logs', 'ip', fn (Blueprint $table) => $table->string('ip')->nullable()->after('new_values'));
        $this->addColumnIfMissing('activity_logs', 'browser', fn (Blueprint $table) => $table->string('browser')->nullable()->after('ip'));
        $this->addColumnIfMissing('activity_logs', 'platform', fn (Blueprint $table) => $table->string('platform')->nullable()->after('browser'));
        $this->addColumnIfMissing('activity_logs', 'device', fn (Blueprint $table) => $table->string('device')->nullable()->after('platform'));
        $this->addColumnIfMissing('activity_logs', 'url', fn (Blueprint $table) => $table->string('url')->nullable()->after('device'));
        $this->addColumnIfMissing('activity_logs', 'method', fn (Blueprint $table) => $table->string('method')->nullable()->after('url'));
        $this->addColumnIfMissing('activity_logs', 'session_id', fn (Blueprint $table) => $table->string('session_id')->nullable()->after('method'));
        $this->addColumnIfMissing('activity_logs', 'executed_at', fn (Blueprint $table) => $table->timestamp('executed_at')->nullable()->after('session_id'));
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }

    private function addColumnIfMissing(string $table, string $column, callable $callback): void
    {
        if (Schema::hasColumn($table, $column)) {
            return;
        }

        Schema::table($table, $callback);
    }
};
