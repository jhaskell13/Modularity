# Modularity Roadmap

This roadmap reflects the repository as it exists today: an early Laravel 12 / Filament 4 multi-tenant CRM prototype with Contacts, Pipelines, and Alerts modules. Milestones are ordered by risk and dependency. They are outcomes, not calendar commitments.

## Current baseline

- Core Laravel app, Filament panel, module catalog, module providers, tenant/module models, and module manager exist.
- Contacts and Pipelines have feature models, migrations, and Filament resources. Alerts has a model, migration, and resource.
- The default application is single-database; tenant-specific database switching is not implemented despite a `tenants.database` column.
- Tests are sparse starter tests. There is no demonstrated end-to-end onboarding, module activation, tenant isolation, or authorization coverage.
- The working tree may contain ongoing application changes; docs should not be read as validation of those changes.

## Milestone 1 — Make tenant boundaries safe

**Priority: P0 · prerequisite for any shared or real customer data**

- Implement a reliable tenant context for authenticated requests and console jobs; fail closed when no valid tenant is present.
- Enforce tenant scoping on reads as well as writes. `HasTenant` currently assigns a `tenant_id` on creation but does not scope queries; a tenant ID on a row alone does not prevent cross-tenant access.
- Validate that related records (company, contact, deal, pipeline, stage, owner, tags) belong to the same tenant before associating them. Review foreign keys that currently permit cross-tenant references.
- Correct `Tenant::addUsers()` so it persists each user's tenant assignment, and make tenant onboarding transactional with validated, reported user IDs.
- Add authorization policies for tenant administration and CRM records; verify Filament resource actions respect them.
- Review soft-delete behavior and tenant/user deletion rules.

**Done when:** automated tests demonstrate that a user cannot list, view, update, delete, or associate another tenant's data through UI, HTTP, model, and relationship paths; onboarding persists assignments or rolls back cleanly.

## Milestone 2 — Define and enforce module lifecycle

**Priority: P0 · required for module configuration to mean anything**

- Implement activation/deactivation operations with validation against the catalog, dependencies, tier/entitlement, and expiry.
- Make dependency handling explicit: reject deactivation of a required module or deactivate dependents in a documented order.
- Enforce module availability in Filament navigation, pages, actions, and any routes/jobs. `EnsureModuleIsActive` is currently a no-op.
- Decide whether providers are boot-time registrations or request-time capabilities. The current provider boot check depends on authentication during application boot, while bootstrap registers base modules globally.
- Invalidate tenant module cache on every activation/configuration change and add tests for cache freshness and tenant switching.
- Specify the meaning and migration policy for module versions and module-specific configuration.

**Done when:** every module operation has a defined state transition and disabled modules cannot be reached or mutate data; dependency and cache behavior is covered by tests.

## Milestone 3 — Repair module scaffolding and conventions

**Priority: P1 · needed before adding modules quickly**

- Fix `make:module`, `make:module-model`, `make:module-migration`, and `make:module-provider` to use the repository's `app/Modules` path and consistent PSR-4 namespaces. Several commands currently use `App/Modules` with uppercase `App`.
- Make generated files match the layout Filament discovery expects, without copy/move operations that leave stale namespaces or paths.
- Validate module names, class names, reserved keys, and dependencies; propagate and report nested Artisan command failures.
- Generate a module catalog entry and provider registration through one documented mechanism; avoid multiple sources of truth.
- Add a smoke example or generator verification for creating a module from a clean checkout.

**Done when:** a generated module autoloads, migrates, appears in Filament when enabled, and can be disabled without manual file surgery.

## Milestone 4 — Establish product and data integrity

**Priority: P1**

- Define the first supported user workflow (for example: onboard tenant → manage companies and contacts → track deals through pipeline stages).
- Add the missing operational flows for Alerts: clarify whether it is an application error inbox, business alerting feature, or both; implement deduplication, severity/status transitions, retention, and access control accordingly.
- Add database constraints and validation for required ownership, statuses, currencies, probabilities, and cross-module references.
- Check whether `company_id`/`contact_id` null-on-delete behavior and soft deletes match product expectations.
- Seed a development tenant, users, module assignments, and representative CRM data without creating unsafe production defaults.
- Add a useful root page or redirect and document the actual panel login and first-user setup flow.

**Done when:** a new developer can follow the documented flow and complete the core use case with persisted, tenant-safe data.

## Milestone 5 — Build a reliable verification and release baseline

**Priority: P1**

- Replace example-only tests with focused unit, feature, and Filament authorization coverage for milestones 1–4.
- Add CI for supported PHP versions, Composer validation, migrations, test suite, Pint, and frontend production build.
- Document supported database/cache/queue configurations and verify migrations against the chosen production database (not only SQLite).
- Add operational health checks, structured logging, queue failure handling, backup/restore guidance, and deployment/configuration documentation.
- Audit dependency versions and security advisories before a public or production release.

**Done when:** CI provides a reproducible green baseline from a clean checkout and an operator can deploy, monitor, back up, and restore the app using documented steps.

## Milestone 6 — Expand modular product capabilities

**Priority: P2 · after the core is safe and supportable**

- Define extension contracts and module APIs so modules interact through stable services/events rather than implicit model coupling.
- Decide whether third-party modules are in scope. If so, define trust, signing, compatibility, permissions, installation, upgrade, and rollback rules before loading external code.
- Consider additional modules (tasks/activities, marketing, products, invoicing, support) only after validating user needs; commented catalog entries are ideas, not implemented features.
- Add module-level documentation and compatibility/version policy.

**Done when:** a new first-party module can be developed against documented contracts without bypassing tenant, authorization, migration, and release guarantees.

## Known fixes to track

| Area | Current issue | Target milestone |
| --- | --- | --- |
| Tenant onboarding | `Tenant::addUsers()` assigns in memory without saving; onboarding is not transactional | 1 |
| Tenant isolation | Creation helper does not globally scope reads; relationships can cross tenant IDs | 1 |
| Module guard | `EnsureModuleIsActive` is currently commented out / pass-through | 2 |
| Provider lifecycle | Base provider registration and auth-dependent boot checks do not form a clear per-tenant lifecycle | 2 |
| Module cache | Cache clear exists but activation paths do not centrally invalidate it | 2 |
| Scaffolding paths | Commands target `App/Modules`, while source lives under `app/Modules` | 3 |
| Scaffolding namespaces | Generator contains a TODO to reconcile generated Filament namespaces/layout | 3 |
| Product surface | Root route is commented out; onboarding and initial admin flow are not fully documented | 4 |
| Verification | Current suite is mostly example tests plus a narrow tenant trait test | 5 |
| License | Composer metadata says MIT; no root license file was visible during repository inspection | 5 |

## Scope notes

The roadmap does not assume separate database-per-tenant architecture, billing, third-party module installation, or a specific deployment provider. Decide those product and operating constraints before implementing the corresponding work. Keep milestone completion based on observable behavior and automated checks, not just code structure.
