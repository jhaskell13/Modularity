<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Available Modules
    |--------------------------------------------------------------------------
    */
    'available' => [
        'contacts' => [
            'name' => 'Contact Management',
            'description' => 'Manage customers and companies',
            'icon' => 'heroicon-o-users',
            'provider' => \App\Modules\Contacts\ContactsServiceProvider::class,
            'tier' => 'base',
            'dependencies' => [],
            'version' => '1.0.0',
        ],
        'pipelines' => [
            'name' => 'Sales Pipeline',
            'description' => 'Track deals and opportunities',
            'icon' => 'heroicon-o-chart-bar',
            'provider' => \App\Modules\Pipelines\PipelinesServiceProvider::class,
            'tier' => 'base',
            'dependencies' => ['contacts'],
            'version' => '1.0.0',
        ],
        'alerts' => [
            'name' => 'Alerts',
            'description' => 'Alert and logging system',
            'icon' => 'heroicon-o-alarm',
            'provider' => \App\Modules\Alerts\AlertsServiceProvider::class,
            'tier' => 'base',
            'dependencies' => [],
            'version' => '1.0.0',
        ],
        // 'tasks' => [
        //     'name' => 'Tasks & Activities',
        //     'description' => 'Manage tasks and schedule activities',
        //     'icon' => 'heroicon-o-clipboard-document-check',
        //     'provider' => \App\Modules\Tasks\TasksServiceProvider::class,
        //     'tier' => 'base',
        //     'dependencies' => [],
        //     'version' => '1.0.0',
        // ],
        // 'marketing' => [
        //     'name' => 'Marketing Automation',
        //     'description' => 'Email campaigns and marketing tools',
        //     'icon' => 'heroicon-o-megaphone',
        //     'provider' => \App\Modules\Marketing\MarketingServiceProvider::class,
        //     'tier' => 'premium',
        //     'price' => 49.99,
        //     'dependencies' => ['contacts'],
        //     'version' => '1.0.0',
        // ],
        // 'products' => [
        //     'name' => 'Product Management',
        //     'description' => 'Manage products and inventory',
        //     'icon' => 'heroicon-o-cube',
        //     'provider' => \App\Modules\Products\ProductsServiceProvider::class,
        //     'tier' => 'premium',
        //     'price' => 29.99,
        //     'dependencies' => [],
        //     'version' => '1.0.0',
        // ],
        // 'invoicing' => [
        //     'name' => 'Invoicing & Billing',
        //     'description' => 'Create invoices and track payments',
        //     'icon' => 'heroicon-o-document-text',
        //     'provider' => \App\Modules\Invoicing\InvoicingServiceProvider::class,
        //     'tier' => 'premium',
        //     'price' => 39.99,
        //     'dependencies' => ['contacts', 'products'],
        //     'version' => '1.0.0',
        // ],
        // 'support' => [
        //     'name' => 'Customer Support',
        //     'description' => 'Helpdesk and ticket management',
        //     'icon' => 'heroicon-o-lifebuoy',
        //     'provider' => \App\Modules\Support\SupportServiceProvider::class,
        //     'tier' => 'premium',
        //     'price' => 34.99,
        //     'dependencies' => ['contacts'],
        //     'version' => '1.0.0',
        // ],
        // 'projects' => [
        //     'name' => 'Project Management',
        //     'description' => 'Manage projects and track progress',
        //     'icon' => 'heroicon-o-briefcase',
        //     'provider' => \App\Modules\Projects\ProjectsServiceProvider::class,
        //     'tier' => 'premium',
        //     'price' => 44.99,
        //     'dependencies' => ['contacts', 'tasks'],
        //     'version' => '1.0.0',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Base Modules
    |--------------------------------------------------------------------------
    | Modules included in the base package
    */
    'base_modules' => [
        'contacts',
        'pipelines',
        'alerts',
        // 'tasks',
        // 'marketing',
        // 'products',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Cache TTL
    |--------------------------------------------------------------------------
    | Time in seconds to cache module configuration
    */
    'cache_ttl' => 3600,
];
