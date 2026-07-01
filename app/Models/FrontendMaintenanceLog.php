<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrontendMaintenanceLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'payload',
        'result',
    ];

    protected $casts = [
        'payload' => 'array',
        'result' => 'array',
    ];
}
