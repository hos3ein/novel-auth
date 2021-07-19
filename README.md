## Introduction

Proper Fortify

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

JWT SECRET

## Extra settings

Define `redirectTo` in `App\Http\Middleware\Authenticate.php`

```php
App\Http\Middleware\Authenticate
```

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
