# Modularity

Modularity is an early-stage, multi-tenant CRM application built with Laravel 12 and Filament 4. Its central design idea is to organize product features as modules that can be enabled per tenant. The repository currently contains three feature areas: Contacts (contacts, companies, and tags), Pipelines (pipelines, stages, deals, and deal activities), and Alerts (alert records and a Filament resource).

The project has the beginnings of a module framework—module configuration, service providers, tenant-module records, a module manager, tenant-aware models, and Artisan scaffolding commands—but tenant isolation and module lifecycle enforcement are incomplete. Treat this as an active prototype, not a production-ready multi-tenant service.

## Current status

- Laravel 12 / PHP 8.2+ application with a Filament admin panel at `/admin`.
- Single-database tenant model: tenants, users, and feature records share one database and feature rows carry a `tenant_id`.
- Module definitions live in `config/modules.php`; the configured base modules are Contacts, Pipelines, and Alerts.
- Filament resources are discovered under `app/Modules`.
- Tests use Pest. Existing tests are starter-level and do not yet establish tenant isolation or module lifecycle guarantees.

See [ROADMAP.md](ROADMAP.md) for the implementation plan and known fixes.

## Requirements

- PHP 8.2 or newer with the extensions required by Laravel.
- Composer.
- Node.js and npm.
- A database supported by Laravel. The example environment defaults to SQLite.

## Setup

```sh
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
```

On Windows PowerShell, use `Copy-Item .env.example .env` in place of `cp` if needed. Update `.env` with the database and other environment settings before migrating. The repository includes a local `.env`; keep secrets and machine-specific values out of commits.

Start the development services with:

```sh
composer run dev
```

This starts the Laravel development server, queue listener, and Vite. The Filament panel is served at `/admin`. The root web route is not configured in `routes/web.php` yet.

## Useful commands

```sh
php artisan migrate
php artisan test
php artisan tenant:onboard "Example Company"
php artisan list make:module
```

`tenant:onboard` creates a tenant and accepts optional user IDs, but the current user-assignment implementation needs correction before relying on it. Review ROADMAP.md before using onboarding or tenant data in a real environment.

## Repository map

```text
app/
  Console/Commands/       Tenant onboarding and module scaffolding commands
  Http/Middleware/        Module activation middleware (currently unfinished)
  Models/                 User, Tenant, and TenantModule
  Modules/
    Contacts/             Contacts, companies, tags, Filament resources
    Pipelines/            Pipelines, stages, deals, Filament resources
    Alerts/               Alert model, migration, and Filament resources
    Contracts/            Shared module service-provider base class
    Traits/               Tenant assignment trait
  Services/               ModuleManager
config/modules.php        Module catalog, providers, dependencies, base modules
 database/migrations/     Core, tenant, contacts, and pipeline schemas
routes/                   HTTP and console routes
 tests/                   Pest tests
```

## How modules are represented today

A module is declared in `config/modules.php` with a key, display metadata, provider, tier, dependencies, and version. `TenantModule` stores per-tenant activation and configuration. `ModuleManager` reads active module records and caches them by tenant. Module providers can load their migrations, views, and translations. Filament discovers resources from the module source tree.

The current implementation does not yet provide safe runtime module isolation: the base providers are registered by application bootstrap, the middleware does not enforce activation, and tenant scoping is not applied automatically to all queries. Module dependency metadata is also not yet a complete activation workflow.

## Development conventions

- Keep feature code inside its module directory where practical.
- Put shared tenant and module primitives in the core `app/` areas.
- Add tenant ownership and authorization checks to every feature query and write path.
- Add focused Pest coverage for behavior changed, especially tenant boundaries and module states.
- Keep credentials and local environment files out of source control.

## License

The Composer project declares the MIT license. Add or verify the repository's license file before distributing the project.
