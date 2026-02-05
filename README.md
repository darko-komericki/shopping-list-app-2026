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

## Deployment

Deployment is automated via GitHub Actions on version tags.

### Deploy a new version

```bash
git tag v1.x.x
git push origin v1.x.x
```

The workflow builds assets, syncs files via rsync, and runs migrations.

### GitHub Secrets Required

| Secret | Description |
|--------|-------------|
| `SSH_PRIVATE_KEY` | Private SSH key for server access |
| `SSH_HOST` | Server IP address |
| `SSH_USERNAME` | SSH username (e.g., `deployer`) |
| `SSH_DEPLOY_DIR` | Deploy path (e.g., `/var/www/shopping.identik.hr`) |

### Server Setup

```bash
# Create storage directories (excluded from rsync)
mkdir -p storage/logs storage/framework/{cache,sessions,views}
mkdir -p bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Create .env with production values
# Generate app key after first deploy
php artisan key:generate
```
