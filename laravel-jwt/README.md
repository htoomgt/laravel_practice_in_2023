# Laravel JWT Access Token, Refresh Token Authentication

To practice the access token, refreh token authentication at laravel with JWT package

## Setup

1. Clone from repository
2. Package (dependencies) installation with `composer install`
3. dot env setup for database, log channel, app key
4. JWT secret generate `php artisan jwt:secret`
5. JWT config time to live (ttl) config
6. migrate database `php artisan migrate`
7. run the application `php artisan serve`

## Referenced List

-   The main referenced tutorial article [link](https://blog.logrocket.com/implementing-jwt-authentication-laravel-9/#install-laravel-9)
-   Refresh JWT token from middleware [link](https://laracasts.com/discuss/channels/general-discussion/how-to-refreshing-jwt-token?page=1)
