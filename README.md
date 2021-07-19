## Novel Auth

[![Latest Stable Version](http://poser.pugx.org/hos3ein/novel-auth/v)](https://packagist.org/packages/hos3ein/novel-auth)
[![Total Downloads](http://poser.pugx.org/hos3ein/novel-auth/downloads)](https://packagist.org/packages/hos3ein/novel-auth)
[![Latest Unstable Version](http://poser.pugx.org/hos3ein/novel-auth/v/unstable)](https://packagist.org/packages/hos3ein/novel-auth)
[![Packagist Stars](https://img.shields.io/packagist/stars/hos3ein/novel-auth)](https://packagist.org/packages/hos3ein/novel-auth)
[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/hos3ein/novel-auth)](https://packagist.org/packages/hos3ein/novel-auth)
[![License](http://poser.pugx.org/hos3ein/novel-auth/license)](https://packagist.org/packages/hos3ein/novel-auth)

* Laravel authentication with password or otp or both and highly configurable

## Install NovelAuth

```shell
composer require hos3ein/novel-auth
```

```shell
php artisan vendor:publish --provider="Hos3ein\NovelAuth\NovelAuthServiceProvider"
```

```shell
php artisan migrate
```

add `NovelAuthServiceProvider` to `app.php` in `providers` array

```php
App\Providers\NovelAuthServiceProvider::class
```

Add `HasOtpCodes` and `NovelAuthAuthenticatable` to your eloquent model

```php
class User extends Authenticatable
{
    use HasOtpCodes, NovelAuthAuthenticatable;
    ...
}
```

## Extra settings

Define `redirectTo` in `App\Http\Middleware\Authenticate.php`

```php
App\Http\Middleware\Authenticate
```


install fortify and migrate
use TwoFactorAuthenticatable in User

Override Fortify `twoFactorQrCodeUrl()` in 'User.php'
```php
public function twoFactorQrCodeUrl()
{
    return app(TwoFactorAuthenticationProvider::class)->qrCodeUrl(
        config('app.name'),
        $this->email ?? $this->phone,
        decrypt($this->two_factor_secret)
    );
}
```
## License

Novel-Auth is open-sourced software licensed under the [MIT license](LICENSE.md).
