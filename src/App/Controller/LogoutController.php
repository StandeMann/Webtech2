<?php

namespace App\Controller;


use Framework\AccesControl\AuthenticationService;
use Framework\Database\Interfaces\ConnectionInterface;
use Framework\Http\Classes\Request;
use Framework\Templating\TemplateEngine;

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