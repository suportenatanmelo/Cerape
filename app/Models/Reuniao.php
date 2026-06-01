<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Reuniao extends Model
{
    protected $table = 'reunioes';

    protected $fillable = [
        'user_id',
        'titulo',
        'descricao',
        'data_reuniao',
        'participantes_user_ids',
        'ata',
    ];

    protected $casts = [
        'data_reuniao' => 'datetime',
        'participantes_user_ids' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return Collection<int, User>
     */
    public function participantesUsers(): Collection
    {
        $ids = collect($this->participantes_user_ids ?? [])
            ->filter(fn (mixed $id): bool => filled($id))
            ->map(fn (mixed $id): int => (int) $id)
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return collect();
        }

        $driver = DB::connection()->getDriverName();
        $orderBy = $driver === 'mysql'
            ? 'FIELD(id, '.$ids->implode(',').')'
            : 'id';

        return User::query()
            ->whereIn('id', $ids)
            ->orderByRaw($orderBy)
            ->get();
    }
}
