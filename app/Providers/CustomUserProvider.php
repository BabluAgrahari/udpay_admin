<?php

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use App\Models\User;

class CustomUserProvider implements UserProvider
{
    public function retrieveByCredentials(array $credentials)
    {
        $client_id = $credentials['client_id'];
        $secret_key = $credentials['secret_key'];

        // Fetch the user using client_id and secret_key from the database
        return User::where('client_id', $client_id)
            ->where('secret_key', $secret_key)
            ->first();
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return $user->secret_key === $credentials['secret_key'];
    }

    // Implement other methods of the UserProvider interface...
}
