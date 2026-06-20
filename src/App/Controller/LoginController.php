<?php

namespace App\Controller;

use App\Repository\UserFunctions;
use App\Repository\UserRepository;
use Framework\AccesControl\AuthenticationService;
use Framework\Database\Interfaces\ConnectionInterface;
use Framework\Database\Interfaces\RepositoryInterface;
use Framework\Http\Classes\Request;
use Framework\Http\Classes\Response;
use Framework\Templating\TemplateEngine;
use JetBrains\PhpStorm\NoReturn;

class LoginController
{

    private ConnectionInterface $connection;
    private AuthenticationService $authenticationService;
    private TemplateEngine $templateEngine;
    private UserFunctions $userFunctions;
    private RepositoryInterface $repository;

    public function __construct(ConnectionInterface $connection, AuthenticationService $authenticationService, TemplateEngine $templateEngine)
    {
        $this->authenticationService = $authenticationService;
        $this->connection = $connection;
        $this->userFunctions = new UserFunctions($this->connection);
        $this->templateEngine = $templateEngine;
        $this->repository = new UserRepository($this->connection);
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


        $user = $this->repository->getUserByEmail($email);
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