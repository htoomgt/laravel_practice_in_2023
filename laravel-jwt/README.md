# Laravel JWT Access Token, Refresh Token Authentication

To practice the access token, refreh token authentication at laravel with JWT package.

## Setup

1. Clone from repository
2. Package (dependencies) installation with `composer install`
3. dot env setup for database, log channel, app key
4. JWT secret generate `php artisan jwt:secret`
5. JWT config time to live (ttl) config
6. migrate database `php artisan migrate`
7. run the application `php artisan serve`

## Generate Token and Refresh Token

1. From queried or created user `$user = User::find(1); $token = Auth::login($user)`
2. Token refresh from Auth `$token = Auth::refresh();`
3. Token refresh from JWTAuth `$newAccessToken = JWTAuth::parseToken()->refresh();`

## User Authentication

1. Credential authentication `$credientials = $request->only('email', 'password');      $token =  Auth::attempt($credientials);`
2. JWT token authentication `$user = JWTAuth::parseToken()->authenticate();`

## Gist Note

1. access token and refresh token can be used same token but access_token ttl and refresh token ttl can be different.
2. token's ttl has to be provided in minutes
3. JWT both access token and refresh token expiration can be detected with TokenExpiredException
4. From jwt token, we can get user by `$user = JWTAuth::setToken($newAccessToken)->toUser();`
5. From Auth, we ccan get user by `$user = Auth::user();`

## File to Check

1. app/Http/Middlewares/JWTMiddleware.php
2. config/jwt.php
3. app/Http/Controllers/AuthController.php
4. routes/api.php

## Referenced List

-   The main referenced tutorial article [link](https://blog.logrocket.com/implementing-jwt-authentication-laravel-9/#install-laravel-9)
-   Refresh JWT token from middleware [link](https://laracasts.com/discuss/channels/general-discussion/how-to-refreshing-jwt-token?page=1)
