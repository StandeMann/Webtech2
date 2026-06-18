<?php

namespace App\Controller;

use Framework\AccesControl\AuthenticationService;
use Framework\Http\Request;
use Framework\Http\Response;
use App\Repository\UserFunctions;
use JetBrains\PhpStorm\NoReturn;
use PDO;

class LoginController
{

    private UserFunctions $functions;
    private PDO $pdo;
    private AuthenticationService $authenticationService;

    public function __construct(PDO $pdo, AuthenticationService $authenticationService)
    {
        $this->functions = new UserFunctions();
        $this->authenticationService = $authenticationService;
        $this->pdo = $pdo;
    }
    public function showPage(Request $request){
        ob_start();

        require __DIR__ . '/../../../views/login.html';

        $html = ob_get_clean();

        return new Response($html);
    }

    #[NoReturn]
    public function loginUser(Request $request): Response{

        $user = $request->getUser();

        if ($user) {
            $location = 'Location: /';

            header($location);
            exit;
        }

        $data = $request->getPost();
        $email = $data["email"];
        $password = $data["password"];


        $user = $this->functions->getUserByEmail($email);
        $databaseHash = $user->password;

        if (password_verify($password, $databaseHash)){
            $this->authenticationService->login($user);
            header('Location: /');
            exit;
        }
        else{
            $location = 'Location: /login';
            header($location);
            exit;
        }
    }
}