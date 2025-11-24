<?php

namespace App\Modules\Contacts\Models;

use App\Models\Tenant;
use App\Modules\Contacts\Database\Factories\CompanyFactory;
use App\Modules\Traits\HasTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Company extends Model
{
    use HasFactory, HasTenant, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'phone',
        'website',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'industry',
        'employee_count',
        'annual_revenue',
        'notes',
        'custom_fields',
    ];

    protected $casts = [
        'custom_fields' => 'array',
        'annual_revenue' => 'decimal:2',
        'employee_count' => 'integer',
    ];

    // protected static function booted(): void
    // {
    //     static::creating(function ($company) {
    //         if (Auth::check() && ! $company->tenant_id) {
    //             $company->tenant_id = Auth::user()->tenant_id;
    //         }
    //     });
    // }

    protected static function newFactory()
    {
        return CompanyFactory::new();
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }
}
