# Warehouse Stock System Demo (Laravel + Vue + Docker)

This project is a demonstration of how to set up a basic warehouse stock system using Laravel & Vue in Docker.

Built with:

- PHP 8.4.7
- Laravel 12.15
- Vue 3.5
- Linted with php-cs-fixer
- Docker & Docker Compose
- MySQL 
- Redis

## 🚀 Running the App

Please ensure you have composer installed before continuing.

```bash
# From project root, run the commands in this order
composer install
cp .env.example .env
./vendor/bin/sail up -d 
./vendor/bin/sail npm i
./vendor/bin/sail artisan migrate:fresh && ./vendor/bin/sail artisan db:seed
./vendor/bin/sail npm run dev or ./vendor/bin/sail npm run build (to build assets)
```

### 🧪 Running the Tests

```bash
# From project root
./vendor/bin/sail artisan test
```

## 🔨 Tech Stack

| Layer       | Tool                    |
|-------------|-------------------------|
| Language    | PHP 8                   |
| Framework   | Laravel 12              |
| DB          | MySQL                   |
| Container   | Docker + Docker Compose |
| API Testing | Pest PHP                |

## ✅ Features

- API-first approach.
- Vue 3 SPA front-end.
- Uses the Laravel Repository pattern.
- Decoupled order placement using Event Driven Arch / Horizon queues.
- Dockerized environment.
- Feature testing.
- Linted to PSR12 standards.

## 🧠 Author

Created by **[@silver-speedo](https://github.com/silver-speedo)**

## 📄 License

This project is licensed under the MIT License. See `LICENSE` for more info.

