<?php

namespace App\Modules\Pipelines\Models;

use App\Models\Tenant;
use App\Modules\Traits\HasTenant;
use App\Modules\Pipelines\Database\Factories\PipelineFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pipeline extends Model
{
    use HasFactory, HasTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'is_default',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    protected static function newFactory()
    {
        return PipelineFactory::new();
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function stages(): HasMany
    {
        return $this->hasMany(PipelineStage::class)->orderBy('order');
    }

    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class);
    }

    protected static function booted(): void
    {
        static::created(function ($pipeline) {
            // Create default stages for new pipeline
            if ($pipeline->stages()->count() === 0) {
                $defaultStages = [
                    ['name' => 'Lead', 'order' => 1, 'probability' => 10, 'color' => '#94a3b8'],
                    ['name' => 'Qualified', 'order' => 2, 'probability' => 25, 'color' => '#60a5fa'],
                    ['name' => 'Proposal', 'order' => 3, 'probability' => 50, 'color' => '#fbbf24'],
                    ['name' => 'Negotiation', 'order' => 4, 'probability' => 75, 'color' => '#fb923c'],
                    ['name' => 'Closed Won', 'order' => 5, 'probability' => 100, 'color' => '#22c55e', 'is_closed' => true],
                ];

                foreach ($defaultStages as $stage) {
                    $pipeline->stages()->create($stage);
                }
            }
        });
    }
}
