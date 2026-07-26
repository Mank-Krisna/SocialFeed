# SocialFeed

Platform media sosial modern berbasis komunitas — dibangun dengan Laravel 13, Livewire 3, dan Tailwind CSS.

## Fitur Utama

- **Feed** — Postingan dengan media, poll, repost, like, komentar
- **Grup** — Komunitas publik/privat dengan admin & member
- **Pesan** — Messaging real-time antar user
- **Notifikasi** — Notifikasi like, komentar, pesan, follow
- **Pencarian** — Search user, post, grup dengan hashtag
- **Bookmark** — Simpan postingan untuk dibaca nanti
- **Admin Dashboard** — Moderasi laporan, kelola konten
- **PWA** — Installable, offline-ready, service worker
- **Dark Mode** — Toggle terang/gelap

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 13, PHP 8.3 |
| Frontend | Livewire 3, Tailwind CSS, Alpine.js |
| Database | MySQL 8 / SQLite (dev) |
| Search | Laravel Scout + Meilisearch |
| Queue | Redis / Database |
| Cache | Redis / Database |
| Real-time | Laravel Broadcasting + Pusher |

## Instalasi

### Prerequisites
- PHP 8.3+
- Node.js 20+
- MySQL 8.0+ / SQLite
- Composer

### Setup

```bash
# Clone
git clone https://github.com/Mank-Krisna/SocialFeed.git
cd SocialFeed

# Install dependencies
composer install
npm install

# Environment
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate
php artisan db:seed   # optional

# Build assets
npm run build

# Start
php artisan serve
```

### Docker

```bash
docker-compose up -d
docker-compose exec app php artisan migrate
docker-compose exec app npm run build
```

## Testing

```bash
php artisan test                    # 88 tests
vendor/bin/phpunit --coverage-html coverage/
npm run build                       # verify assets
```

## Deployment

### VPS (Ubuntu + Nginx)

```bash
# On server
git clone https://github.com/Mank-Krisna/SocialFeed.git /var/www/socialfeed
cd /var/www/socialfeed
cp .env.production.example .env     # configure
./deploy.sh main
```

### Docker

```bash
docker-compose -f docker-compose.yml up -d --build
```

### CI/CD

Push to `main` branch triggers GitHub Actions workflow:
1. Run tests against MySQL
2. Build assets
3. Deploy (manual trigger via `deploy.sh`)

## Konfigurasi

| Variable | Description | Default |
|----------|-------------|---------|
| `FEED_PER_PAGE` | Posts per page | 15 |
| `RATE_POST_PER_MINUTE` | Post creation limit | 10 |
| `SCOUT_DRIVER` | Search engine | `database` |
| `MEILISEARCH_HOST` | Meilisearch URL | `http://localhost:7700` |

Lihat `.env.production.example` untuk semua variabel.

## License

MIT
