<?php

namespace Framework\AccesControl;

use App\Model\User;

class AuthorizationService
{
    public function isGranted(User $user, string $permission): bool {
        return match ($permission) {
            'admin' => $user->getRole() === 'admin',
            default => false
        };
    }
}