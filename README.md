# Špajza

Shopping list app built with Laravel 12, Livewire, and Tailwind CSS 4.

Rewrite of the [Astro/Vue version](../shopping-list/) using Laravel's server-driven architecture.

## Stack

- **Laravel 12** — PHP framework
- **Livewire 4** — reactive server-driven UI components
- **Flux** — Livewire UI component library (bundled with starter kit)
- **Alpine.js** — lightweight JS for client-side interactions (bundled with Livewire)
- **Tailwind CSS 4** — utility-first CSS
- **MySQL** — database
- **Pest** — testing framework

## Setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Create the database:
```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS spajza;"
```

Run migrations:
```bash
php artisan migrate
```

## Development

```bash
# Start Laravel dev server + Vite in one command
composer run dev

# Or separately:
php artisan serve    # Laravel at localhost:8000
npm run dev          # Vite dev server
```

## Testing

```bash
php artisan test
```

## Production

```bash
npm run build
php artisan migrate --force
```
