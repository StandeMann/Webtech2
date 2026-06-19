<?php

namespace App\Controller;

use App\Repository\BookFunctions;
use App\Repository\UserFunctions;
use Framework\AccesControl\AuthenticationService;
use Framework\Database\ConnectionInterface;
use Framework\Http\Classes\Request;
use Framework\Http\Classes\Response;
use Framework\Templating\TemplateEngine;
use JetBrains\PhpStorm\NoReturn;
use PDO;

class LoginController
{

    private ConnectionInterface $connection;
    private AuthenticationService $authenticationService;
    private TemplateEngine $templateEngine;
    private UserFunctions $userFunctions;

    public function __construct(ConnectionInterface $connection, AuthenticationService $authenticationService, TemplateEngine $templateEngine)
    {
        $this->authenticationService = $authenticationService;
        $this->connection = $connection;
        $this->userFunctions = new UserFunctions($this->connection);
        $this->templateEngine = $templateEngine;
    }
    public function showPage(Request $request, array $params): Response{

        $html = $this->templateEngine->render('login', $params);

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

        $data = $request->getAttributes();
        $email = $data["email"];
        $password = $data["password"];


        $user = $this->userFunctions->getUserByEmail($email);
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