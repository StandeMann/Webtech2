<?php

namespace App\Controller;

use App\Repository\BookFunctions;
use Framework\AccesControl\AuthenticationService;
use Framework\Database\Interfaces\ConnectionInterface;
use Framework\Http\Classes\Request;
use Framework\Http\Classes\Response;
use Framework\Templating\TemplateEngine;

class ErrorController
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

    public function error404(Request $request, array $params): Response{

        $html = $this->templateEngine->render('404', $params);

        return new Response($html);
    }

    public function error403(Request $request, array $params): Response{
        $html = $this->templateEngine->render('403', $params);

        return new Response($html);
    }
}