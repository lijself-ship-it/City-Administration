# Kombolcha City Administration

A full-stack municipal government portal built with Laravel (backend API and CMS data) and a Vue 3 + TypeScript SPA.

Version: current `main` commit `fc02a58ba04a6e7a62aaa73f200c2ab4acc85bc9` plus documentation/CI updates.

## Technology stack

- **Backend:** Laravel 13 skeleton, PHP 8.3+, MySQL
- **Frontend:** Vue 3, TypeScript, Vite, Tailwind CSS v4, Pinia, Axios, Vue Router
- **Database:** MySQL `city`
- **API base:** `server/routes/api.php` exposed under `/api/v1`
- **Frontend entry point:** `client/src/main.ts`

## Repository layout

- `server/` — Laravel API, migrations, seeders, models, and backend tests
- `client/` — Vue 3 + TypeScript SPA and frontend build
- `.github/` — project-level configuration and CI workflow

## Local development setup

### Prerequisites

- PHP 8.3+
- Composer
- Node.js 20+
- MySQL with a database named `city`

### Backend

```bash
cd server
composer install
php artisan key:generate
php artisan migrate --force
php artisan db:seed
php artisan serve --host=127.0.0.1 --port=8000
```

Create the MySQL database and user according to your environment before running migrations. Do not commit `.env`; use `server/.env.example` as the template.

### Frontend

```bash
cd client
npm ci
npm run build
npm run dev
```

Set `VITE_API_BASE_URL` if the API is not available at `http://localhost:8000/api/v1`.

## Common verification commands

Backend:

```bash
cd server
php artisan test
php artisan route:list --path=api/v1
```

Frontend:

```bash
cd client
npm run build
```

## Application areas

- Homepage, hero carousel, featured services, municipal statistics
- Citizen services and step guides
- News, events, vacancies, tenders, resources, galleries
- Government members and departments
- Explore and tourism content
- Civic polls, forum topics, contact inquiries, newsletter subscriptions
- Multilingual support (English, Amharic, Afaan Oromoo)
- Light and dark theme support

## Security and operational notes

- `.env` is excluded from Git and must not be committed.
- Authorization and validation are enforced through Laravel routes/controllers/middleware.
- Production secrets must be managed outside source control.
- Database migrations use MySQL-compatible index and foreign-key names.
- The application was verified with a frontend production build, Laravel unit/feature tests, MySQL migrations, API smoke checks, viewport checks, and runtime console checks in the development environment.

## Responsive QA targets

Frontend layout and overflow checks were performed at:

`320, 375, 430, 768, 1024, 1440, 1920` pixels.

## Deployment summary

1. Create the production MySQL database.
2. Copy and configure `server/.env` from `server/.env.example`.
3. Run `composer install --no-dev --optimize-autoloader`.
4. Run `php artisan key:generate` if required.
5. Run `php artisan migrate --force` and required seeders.
6. Build frontend assets with `npm ci && npm run build`.
7. Serve the Laravel application and built frontend assets according to your hosting strategy.