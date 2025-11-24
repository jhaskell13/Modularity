<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantModule extends Model
{
    protected $fillable = [
        'tenant_id',
        'module_key',
        'is_active',
        'configuration',
        'activated_at',
        'expires_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'configuration' => 'array',
        'activated_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function getModuleConfig(): array
    {
        return config("modules.available.{$this->module_key}", []);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}
