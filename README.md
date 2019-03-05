# laravel-response
response custom，定制 response 返回值

### Response result

Use the middleware to handle the error response; the result template like:

```
http status: 200 OK
{
    "message": "The password field is required.",
    "status_code": "4220000",
    "extra": {
        "status_code": 422,
        "message": "422 Unprocessable Entity"
    }
}
```  
status_code form by "http code" + 0000; 

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
PS: you should add this middleware in first queue as the middleware’ rule(first-in, last-out), 
this plugin' base is resolve the error result, and acquire the specified info from the array 
we set in config/message.php file. 

