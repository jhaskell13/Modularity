<?php

namespace App\Modules\Traits;

use App\Exceptions\MissingTenantId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait HasTenant
{
    protected static function bootHasTenant()
    {
        static::creating(function (Model $model) {
            if (($tenantId = Auth::user()?->tenant_id) === null) {
                throw new MissingTenantId('No tenant_id associated with user.');
            }

            if (Auth::check() && ! $model->tenant_id) {
                $model->tenant_id = $tenantId;
            }
        });
    }
}
