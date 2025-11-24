<?php

namespace App\Modules\Contacts\Models;

use App\Models\User;
use App\Models\Tenant;
use App\Modules\Traits\HasTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Contact extends Model
{
    use HasTenant, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'company_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'mobile',
        'job_title',
        'department',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'birthday',
        'lead_source',
        'status',
        'notes',
        'custom_fields',
        'owner_id',
    ];

    protected $casts = [
        'custom_fields' => 'array',
        'birthday' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function ($contact) {
            if (Auth::check() && ! $contact->tenant_id) {
                $contact->tenant_id = Auth::user()->tenant_id;
            }
        });
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(ContactTag::class, 'contact_tag_pivot');
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
