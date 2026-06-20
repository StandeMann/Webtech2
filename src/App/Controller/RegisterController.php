<?php

namespace App\Controller;

use App\Repository\UserFunctions;
use Framework\AccesControl\AuthenticationService;
use Framework\Database\Interfaces\ConnectionInterface;
use Framework\Http\Classes\Request;
use Framework\Http\Classes\Response;
use Framework\Templating\TemplateEngine;
use JetBrains\PhpStorm\NoReturn;

class RegisterController
{
    private ConnectionInterface $connection;
    private AuthenticationService $authenticationService;
    private TemplateEngine $templateEngine;
    private UserFunctions $userFunctions;

    public function __construct(ConnectionInterface $connection, AuthenticationService $authenticationService, TemplateEngine $templateEngine)
    {
        $this->authenticationService = $authenticationService;
        $this->connection = $connection;
        $this->templateEngine = $templateEngine;
        $this->userFunctions = new UserFunctions($this->connection);
    }

    public function showPage(Request $request, array $params): Response{
        $html = $this->templateEngine->render('register', $params);

        return new Response($html);
    }

    #[NoReturn]
    public function register(Request $request){
        $data = $request->getAttributes();

        $name =  $data['name'];
        $email =  $data['email'];
        $password =  $data['password'];

        $this->userFunctions->createUser($name, $email, $password);

        header('Location: /login');

        exit;
    }


}