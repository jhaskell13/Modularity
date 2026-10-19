<?php

namespace App\Modules\Alerts\Models;

use App\Modules\Traits\HasTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    /** @use HasFactory<\Database\Factories\App\Modules\Alerts\Models\AlertFactory> */
    use HasFactory, HasTenant;

    protected $fillable = [
        'type',
        'severity',
        'title',
        'fingerprint',
        'status',
        'first_seen_at',
        'last_seen_at',
        'occurences',
        'last_payload',
    ];

    protected $casts = [
        'first_seen_at' => 'datetime',
        'last_seen_at' => 'datetime',
    ];
}
