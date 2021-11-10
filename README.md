# Project Test

This is my simple program.

## Instalation
- Clone or download repository
- copy the .env.example file and rename it to ".env", then adjust the following variable with your database
    ```html
    DB_DATABASE=
    DB_USERNAME=
    DB_PASSWORD=
    ```
- Download package: run `composer install` and run `npm install`
- Build assets: run `npm run dev`
- Migrate and Seeder Data: run `php artisan migrate --seed`
- Run local server: run `php artisan serve`
- Access to backend: `http://localhost:8000/admin` => format `BASE_URL/admin`
    ```html
    Email: super.admin@gmail.com
    Password: 123456
    ```

## Issue
- if there is a problem like this when run `composer install` => `PHP Fatal error:  Allowed memory size of 1610612736 bytes exhausted (tried to allocate 12288 bytes)`
solution: run `COMPOSER_MEMORY_LIMIT=-1 composer install`
