<?php

namespace Framework\AccesControl;

use App\Model\User;
use App\Repository\UserFunctions;
use App\Repository\UserRepository;
use Framework\Http\Classes\Session;

class AuthenticationService
{
    public function __construct(
        private Session $session,
        private UserRepository $userFunctions)
    {}
    public function getCurrentUser(): ?User{
        $userId = $this->session->get('user_id');

        if (!$userId) {
            return null;
        }

        return $this->userFunctions->getUser($userId);
    }

    public function login(User $user): void
    {
        $this->session->set(
            'user_id',
            $user->getId()
        );
    }

    public function logout(): void
    {
        $this->session->remove('user_id');
    }

}