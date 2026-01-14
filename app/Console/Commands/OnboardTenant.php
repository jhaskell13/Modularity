<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Console\Command;

class OnboardTenant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:onboard {name} {userIds?*}';

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

        if (! empty($userIds = $this->argument('userIds'))) {
            $tenant->addUsers(User::whereIn('id', $userIds)->get());
        }

        echo "Tenant successfully created!\n\n";
        echo "Tenant ID:\t\t$tenant->id\n";
        echo "Tenant Name:\t\t$tenant->name\n";
        echo "Tenant Subdomain:\t$tenant->subdomain\n\n";
    }
}
