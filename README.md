## Novel Auth

[![Latest Stable Version](http://poser.pugx.org/hos3ein/novel-auth/v)](https://packagist.org/packages/hos3ein/novel-auth)
[![Packagist Download](https://img.shields.io/packagist/dt/hos3ein/novel-auth)](https://packagist.org/packages/hos3ein/novel-auth)
[![Latest Unstable Version](http://poser.pugx.org/hos3ein/novel-auth/v/unstable)](https://packagist.org/packages/hos3ein/novel-auth)
[![Packagist Stars](https://img.shields.io/packagist/stars/hos3ein/novel-auth)](https://packagist.org/packages/hos3ein/novel-auth)
[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/hos3ein/novel-auth)](https://packagist.org/packages/hos3ein/novel-auth)
[![License](http://poser.pugx.org/hos3ein/novel-auth/license)](https://packagist.org/packages/hos3ein/novel-auth)

* Laravel authentication with Password or OTP or both and highly configurable

## Sample

* Source: [NovelAuth sample with multiple guard](https://github.com/hos3ein/novel-auth-sample)
* Demo:  [http://novel-auth.herokuapp.com/](http://novel-auth.herokuapp.com)

## Install

```shell
composer require hos3ein/novel-auth
```

```shell
php artisan vendor:publish --provider="Hos3ein\NovelAuth\NovelAuthServiceProvider"
```

```shell
php artisan migrate
```

## The Novel Auth Service Provider

The `vendor:publish` command discussed above will also publish the `App\Providers\NovelAuthServiceProvider` class. You
should ensure this class is registered within the `providers` array of your application's `config/app.php` configuration
file.

```php
'providers' => [
    // ...
    App\Providers\NovelAuthServiceProvider::class
]
```

Add `HasOtpCodes` and `NovelAuthAuthenticatable` to your eloquent model

```php
class User extends Authenticatable
{
    use HasOtpCodes, NovelAuthAuthenticatable;
    ...
}
```

## Final step

Implement methods in `App\Actions\NovelAuth\OtpManager.php` in order to do how to send SMS or make a Call and others.

## License

The Novel-Auth is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
