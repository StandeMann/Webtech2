<?php

namespace App\Controller;

use App\Repository\UserFunctions;
use Framework\AccesControl\AuthenticationService;
use Framework\Http\Classes\Request;
use Framework\Http\Classes\Response;
use Framework\Templating\TemplateEngine;
use JetBrains\PhpStorm\NoReturn;
use PDO;

class RegisterController
{
    private UserFunctions $functions;
    private PDO $pdo;
    private AuthenticationService $authenticationService;
    private TemplateEngine $templateEngine;

    public function __construct(PDO $pdo, authenticationService $authenticationService, TemplateEngine $templateEngine)
    {
        $this->functions = new UserFunctions();
        $this->pdo = $pdo;
        $this->authenticationService = $authenticationService;
        $this->templateEngine = $templateEngine;
    }

    public function showPage(Request $request, array $params): Response{
        $html = $this->templateEngine->render('register', $params);

        return new Response($html);
    }

    #[NoReturn]
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