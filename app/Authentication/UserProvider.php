<?php

namespace App\Authentication;

use \Illuminate\Contracts\Auth\UserProvider as IlluminateUserProvider;
use \Illuminate\Contracts\Auth\Authenticatable;
use \App\Models\User;
use \Illuminate\Support\Facades\Hash;
use \Illuminate\Support\Facades\DB;

class UserProvider implements IlluminateUserProvider
{
    /**
     * @param  mixed  $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        // Get and return a user by their unique identifier
        $user = User::find($identifier);
        return $user;
    }

    /**
     * @param  mixed   $identifier
     * @param  string  $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        // Get and return a user by their unique identifier and "remember me" token
        return null;
    }

    /**
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string  $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        // Save the given "remember me" token for the given user
        return;
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        // Get and return a user by looking up the given credentials
        // DB::enableQueryLog();
        // dd(DB::getQueryLog());

        $user = User::where('EmailAddress', $credentials['email'])->where('Active', 1)->first();
        if ($user) return $user;

        return null;
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        // Check that given credentials belong to the given user
        return Hash::check($credentials['password'], $user->getAuthPassword());
    }
}
