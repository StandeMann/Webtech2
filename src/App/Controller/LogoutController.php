<?php

namespace App\Controller;


use App\Repository\BookFunctions;
use Framework\AccesControl\AuthenticationService;
use Framework\Http\Classes\Request;
use PDO;

class LogoutController
{
    private PDO $pdo;
    private BookFunctions $bookFunctions;
    private AuthenticationService $authenticationService;
    public function __construct(PDO $pdo, AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
        $this->pdo = $pdo;
        $this->bookFunctions = new BookFunctions($this->pdo);
    }
    public function logoutUser(Request $request){
        $this->authenticationService->logout();
        $location = 'Location: /';
        header($location);
        exit;
    }
}