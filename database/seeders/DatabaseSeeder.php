<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use App\Modules\Contacts\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a tenant
        $tenant = Tenant::factory()->create([
            'name' => 'Test Tenant',
            'subdomain' => 'testtenant',
        ]);

        // Create user and attach to tenant
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'tenant_id' => $tenant->id,
        ]);

        // Add module data

        // Contacts
        Company::factory()->create([
            'name' => 'Test Company',
            'tenant_id' => $tenant->id,
        ]);

        // Sales pipelines
    }
}
