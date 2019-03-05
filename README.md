# laravel-response
response custom，定制 response 返回值

### Laravel

This package can be used in Laravel 5.4 or higher. 
You can install the package via composer:

``` bash
composer require qujsh/laravel-response
```

In Laravel 5.5 the service provider will automatically get registered. 

You can publish the migration with:

```bash
php artisan vendor:publish --provider="Qujsh\Response\ResponseServiceProvider"
```

## Using a middleware

This package comes with `ResponseCustomMiddleWare` middleware. You can add them inside your `app/Http/Kernel.php` file.

```php
protected $routeMiddleware = [
    // ...   
    'responseCustom' => \Qujsh\Response\Middlewares\ResponseCustomMiddleWare::class, 
];
```

Then you can protect your routes using middleware rules:

```php
Route::group(['middleware' => ['responseCustom]], function () {
    //
});

Route::group(['middleware' => ['responseCustom:default']], function () {
    //
});
```


