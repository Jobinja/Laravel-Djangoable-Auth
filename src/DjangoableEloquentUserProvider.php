<?php
namespace Jobinja\Djangoable;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

class DjangoableEloquentUserProvider extends EloquentUserProvider implements UserProvider
{
    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        $plain = $credentials['password'];

        $laravel = $this->hasher->check($plain, $user->getAuthPassword());

        if ($laravel) {
            return true;
        }

        $django = $this->hasher->checkForDjango($plain, $user->getAuthPassword());

        if ($django) {
            if (config('auth.rehash_django', true)) {
                $this->updatePassword($user, $plain);
            }
            return true;
        }

        return false;
    }

    /**
     * Update user password if he wants
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param                                            $plainPass
     */
    public function updatePassword(Authenticatable $user, $plainPass)
    {
        $field = config('auth.password_field', 'password');
        $user->$field = $this->hasher->make($plainPass);
        $user->save();
    }
}