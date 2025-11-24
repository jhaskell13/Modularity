<?php

namespace App\Modules\Pipelines\Models;

use App\Models\User;
use App\Modules\Contacts\Models\Company;
use App\Modules\Contacts\Models\Contact;
use App\Models\Tenant;
use App\Modules\Traits\HasTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deal extends Model
{
    use HasTenant, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'pipeline_id',
        'stage_id',
        'title',
        'description',
        'value',
        'currency',
        'contact_id',
        'company_id',
        'owner_id',
        'expected_close_date',
        'closed_date',
        'status',
        'lost_reason',
        'probability',
        'custom_fields',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'probability' => 'decimal:2',
        'custom_fields' => 'array',
        'expected_close_date' => 'date',
        'closed_date' => 'date',
    ];

    protected $attributes = [
        'status' => 'open',
        'currency' => 'USD',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function pipeline(): BelongsTo
    {
        return $this->belongsTo(Pipeline::class);
    }

    public function stage(): BelongsTo
    {
        return $this->belongsTo(PipelineStage::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(DealActivity::class);
    }

    // Helper methods
    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    public function isWon(): bool
    {
        return $this->status === 'won';
    }

    public function isLost(): bool
    {
        return $this->status === 'lost';
    }

    public function markAsWon(): void
    {
        $this->update([
            'status' => 'won',
            'closed_date' => now(),
        ]);
    }

    public function markAsLost(string $reason): void
    {
        $this->update([
            'status' => 'lost',
            'lost_reason' => $reason,
            'closed_date' => now(),
        ]);
    }
}
