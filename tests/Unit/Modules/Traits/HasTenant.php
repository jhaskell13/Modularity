<?php

use App\Exceptions\MissingTenantId;
use App\Models\Tenant;
use App\Models\User;
use App\Modules\Contacts\Models\Company;
use App\Modules\Contacts\Models\Contact;
use App\Modules\Pipelines\Models\Pipeline;
use App\Modules\Traits\HasTenant;

use function Pest\Laravel\actingAs;

test('expected models use HasTenant trait', function () {
    expect([
        Contact::class,
        Company::class,
        Pipeline::class,
    ])
        ->toUseTrait(HasTenant::class);
});

test('model creation associates tenant_id relation', function () {
    /**
     * This is necessary to prevent Intelephense casting error
    *
    * @var User $user
    */
    $user = User::factory()->create();

    $tenant = Tenant::factory()->create();
    $tenant->addUsers([$user]);

    actingAs($user);

    expect($user->tenant_id)
        ->not()
        ->toBeNull();

    $pipeline = Pipeline::factory()->create();

    expect($pipeline->tenant_id)
        ->not()
        ->toBeNull();
});

test('no tenant_id on user throws MissingTenantId exception', function () {
    // Create model without user
    Pipeline::factory()->create();
})->throws(MissingTenantId::class);
