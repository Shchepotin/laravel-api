# Laravel API

Install:

```bash
composer require schepotin/laravel-api
```

Add the service provider to your `$providers` array in `config/app.php` file like:

```bash
Schepotin\LaravelApi\LaravelApiServiceProvider::class
```

Run command:

```bash
php artisan laravel-api:publish
```