<?php

namespace App\Controller;


use App\Repository\BookFunctions;
use Framework\AccesControl\AuthenticationService;
use Framework\Database\ConnectionInterface;
use Framework\Http\Classes\Request;
use Framework\Templating\TemplateEngine;
use PDO;

class LogoutController
{
    private ConnectionInterface $connection;
    private AuthenticationService $authenticationService;
    private TemplateEngine $templateEngine;

    public function __construct(ConnectionInterface $connection, AuthenticationService $authenticationService, TemplateEngine $templateEngine)
    {
        $this->authenticationService = $authenticationService;
        $this->connection = $connection;
        $this->templateEngine = $templateEngine;
    }
    public function logoutUser(Request $request){
        $this->authenticationService->logout();
        $location = 'Location: /';
        header($location);
        exit;
    }
}