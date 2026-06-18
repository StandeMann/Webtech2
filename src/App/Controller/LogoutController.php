<?php

namespace App\Controller;


use App\Repository\BookFunctions;
use Framework\AccesControl\AuthenticationService;
use Framework\Http\Classes\Request;
use Framework\Templating\TemplateEngine;
use PDO;

class LogoutController
{
    private PDO $pdo;
    private BookFunctions $bookFunctions;
    private AuthenticationService $authenticationService;
    private TemplateEngine $templateEngine;
    public function __construct(PDO $pdo, AuthenticationService $authenticationService, TemplateEngine $templateEngine)
    {
        $this->authenticationService = $authenticationService;
        $this->pdo = $pdo;
        $this->bookFunctions = new BookFunctions($this->pdo);
        $this->templateEngine = $templateEngine;
    }
    public function logoutUser(Request $request){
        $this->authenticationService->logout();
        $location = 'Location: /';
        header($location);
        exit;
    }
}