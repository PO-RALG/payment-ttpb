# TMIS Backend API

Backend services for TMIS. This document outlines the minimum setup steps every developer should follow before contributing.

## Prerequisites

- PHP 8.2+
- Composer 2.x
- PostgreSQL (recommended v14+)
- Git

## Getting Started

1. **Clone the repository**
   ```bash
   git clone https://github.com/PO-RALG/tims-api.git
   cd tims-api/ttpb-backend
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Prepare the environment**
   ```bash
   cp .env.example .env
   ```
   - Update database credentials (e.g. `DB_DATABASE=ttpb_db`).
   - Set mail, queue, cache, and any other environment-specific values.
   - When running NationalitiesSeeder behind a corporate proxy or with missing root certificates, set `REST_COUNTRIES_SKIP_SSL=true`.

4. **Generate the app key**
   ```bash
   php artisan key:generate --ansi
   ```

5. **Run migrations**
   ```bash
   php artisan migrate
   ```

6. **Seed baseline data**
   ```bash
   php artisan db:seed
   ```
   Seeds demo users, roles, permissions, default admin hierarchy, etc.

7. **Generate Swagger docs**
   ```bash
   php artisan l5-swagger:generate
   ```

8. **Optimize and refresh autoloaders (optional but recommended)**
   ```bash
   composer dump-autoload
   php artisan optimize
   ```

9. **Run the dev server**
   ```bash
   php artisan serve
   ```
   API base URL: `http://127.0.0.1:8000/api`

## API References

- **REST API:** `<your_host>/api/...`
- **Swagger UI:** `<your_host>/api/documentation`

## Useful Commands

- Clear caches: `php artisan optimize:clear`
- Rerun Swagger: `php artisan l5-swagger:generate`
- Run feature tests: `php artisan test`

Keep this README up-to-date when new environment variables or setup steps are introduced.
