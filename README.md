# Laravel Django Authentication
An authentication driver for Laravel5 which allows to authenticate users using their legacy Django PBKDF2 passwords.

## Usage
Add the following service provider to the providers section of ```config/app.php```:

```Jobinja\Djangoable\DjangoableServiceProvider::class```

change your auth driver to ```djangoable``` in ```config/auth.php```, if you prefer to use database driver instead of eloquent use ```djangoable_database``` instead of
```djangoable```.

## Refreshing passwords on first login
By default after the first login of the user *using a password*, user's password is rehashed based on your laravel hasher contract. If you don't wat
this you can disable it by setting ```rehash_django``` to ```false``` in ```config/auth.php``` :

```php
// auth.php
//...
'rehash_django' => false,
//...
```

## Running hasher contract test

Clone the project then run ```vendor/bin/phpunit```.