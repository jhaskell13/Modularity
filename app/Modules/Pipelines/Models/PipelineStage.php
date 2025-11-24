<?php

namespace App\Modules\Pipelines\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PipelineStage extends Model
{
    protected $fillable = [
        'pipeline_id',
        'name',
        'order',
        'probability',
        'color',
        'is_closed',
    ];

    protected $casts = [
        'order' => 'integer',
        'probability' => 'decimal:2',
        'is_closed' => 'boolean',
    ];

    protected $attributes = [
        'is_closed' => false,
        'color' => '#3b82f6',
    ];

    public function pipeline(): BelongsTo
    {
        return $this->belongsTo(Pipeline::class);
    }

    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class, 'stage_id');
    }
}
