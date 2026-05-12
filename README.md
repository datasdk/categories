# DataSDK Categories

Category and tag contracts, category model, category trait, migrations and factories.

## Installation

```bash
composer require datasdk/categories
```

## Migrations

The service provider loads package migrations automatically.

```bash
php artisan migrate
```

## Config

Publish the config file when you need to customize it:

```bash
php artisan vendor:publish --provider="DataSDK\Categories\CategoriesServiceProvider" --tag=config
```
