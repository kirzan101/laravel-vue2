## About Project: Laravel-Vue-2 

Template for creating laravel project with Vue.js 2 front end using Inertia.js

## Requirements

* php8.1
* MariaDB
* nodejs >= 18.15
* php8.1-bcmath
* php8.1-intl
* php8.1-gd
* php8.1-xml
* php8.1-zip
* php8.1-mbstring
* php8.1-pdo
* php8.1-mysql
* php8.1-curl

## Build Setup

``` bash
# install composer dependencies
$ cp .env.example .env
$ composer install
$ php artisan key:generate
$ php artisan migrate:fresh --seed

# install npm dependencies
$ npm install

# Run Server
$ php artisan serve
$ npm run dev

```
