<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class CustomUserProvider implements UserProvider
{
    public function retrieveById($identifier)
    {
        return Usuario::where('id_usuario', $identifier)->first();
    }

    public function retrieveByToken($identifier, $token)
    {
        return Usuario::where('id_usuario', $identifier)
            ->where('remember_token', $token)
            ->first();
    }

    public function updateRememberToken(UserContract $user, $token)
    {
        $user->setRememberToken($token);
        $user->save();
    }

    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials)) {
            return;
        }

        // Buscar por el campo 'correo' en lugar de 'email'
        $query = Usuario::query();
        
        foreach ($credentials as $key => $value) {
            if (str_contains($key, 'password')) {
                continue;
            }
            
            if ($key === 'email') {
                $query->where('correo', $value);
            } else {
                $query->where($key, $value);
            }
        }
        
        return $query->first();
    }

    public function validateCredentials(UserContract $user, array $credentials)
    {
        $plain = $credentials['password'];
        return Hash::check($plain, $user->getAuthPassword());
    }
}