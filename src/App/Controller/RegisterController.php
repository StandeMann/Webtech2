<?php

namespace App\Controller;

use App\Repository\UserFunctions;
use Framework\AccesControl\AuthenticationService;
use Framework\Http\Classes\Request;
use Framework\Http\Classes\Response;
use PDO;

class RegisterController
{
    private UserFunctions $functions;
    private PDO $pdo;
    private AuthenticationService $authenticationService;

    public function __construct(PDO $pdo, authenticationService $authenticationService)
    {
        $this->functions = new UserFunctions();
        $this->pdo = $pdo;
        $this->authenticationService = $authenticationService;
    }

    public function showPage(Request $request): Response{
        ob_start();

        require __DIR__ . '/../../../views/register.html';

        $html = ob_get_clean();

        return new Response($html);
    }

    public function register(Request $request){
        $data = $request->getPost();

        $name =  $data['name'];
        $email =  $data['email'];
        $password =  $data['password'];

        $this->functions->createUser($name, $email, $password);


        header('Location: /login');

        exit;
    }


}