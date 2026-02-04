# CLAUDE.md

## Commands

```bash
composer run dev     # Dev server (Laravel + Vite concurrently)
php artisan serve    # Laravel dev server at localhost:8000
npm run dev          # Vite dev server
php artisan test     # Run Pest tests
```

## Architecture

Špajza is a shopping list app using **Laravel 12** with **Livewire 4**, **Tailwind CSS 4**, and **MySQL**. Rewrite of the Astro/Vue version at `../shopping-list/`.

### Key layers

- **Routes** (`routes/web.php`): Croatian route names (`prijava`, `artikli`, `predlosci`, `liste/{id}`).
- **Livewire components** (`app/Livewire/`): Server-driven reactive components. Alpine.js for client-side interactions (swipe gestures, dropdowns).
- **Models** (`app/Models/`): User, Item, Template, TemplateItem, ShoppingList, ListItem, Category, PurchaseHistory. All data scoped to authenticated user.
- **Database** (`database/migrations/`): MySQL. Versioned migrations.
- **Auth**: Laravel Fortify (bundled with Livewire starter kit). Cookie-based sessions. New users get seeded with default items, templates, and categories.
- **Views** (`resources/views/`): Blade templates with Livewire components.

### Conventions

- UI text and error messages are in **Croatian**.
- All data is scoped to the authenticated user.
- Tailwind 4 config is in `resources/css/app.css` using `@theme`.
- Gabarito is the custom font (set as `--font-sans`).
- Dark mode is supported via `dark:` Tailwind variants.
- Item categories: mliječno, meso, voće-povrće, pekara, smočnica, pića, smrznuto, čišćenje, ostalo.

### Database

Local MySQL access: root user, no password.

```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS spajza;"
php artisan migrate
```
