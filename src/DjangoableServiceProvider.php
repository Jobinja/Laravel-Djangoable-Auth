<?php
namespace Jobinja\Djangoable;

use Illuminate\Auth\AuthManager;
use Illuminate\Support\ServiceProvider;

class DjangoableServiceProvider extends ServiceProvider
{
    /**
     * Boot method
     *
     * @return void
     */
    public function boot()
    {
        /** @var AuthManager $auth */
        $auth = $this->app['auth'];

        $auth->extend('djangoable', function () {
            return new DjangoableEloquentUserProvider(new DjangoableHasher(), config('auth.model'));
        });

        $auth->extend('djangoable_database', function () {
            return new DjangoableDatabaseUserProvider(new DjangoableHasher(), config('auth.table'));
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // TODO: Implement register() method.
    }
}