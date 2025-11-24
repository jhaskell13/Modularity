<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;

class OnboardTenant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:onboard {name} {users?*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Onboard new tenant.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenant = Tenant::create([
            'name' => $this->argument('name'),
            'subdomain' => str_replace(' ', '', strtolower($this->argument('name'))),
        ]);

        if (! empty($users = $this->argument('users'))) {
            $tenant->addUsers($users);
        }

        echo "Tenant successfully created!\n\n";
        echo "Tenant ID:\t\t$tenant->id\n";
        echo "Tenant Name:\t\t$tenant->name\n";
        echo "Tenant Subdomain:\t$tenant->subdomain\n\n";
    }
}
