<?php

namespace App\Controller;

use App\Repository\BookFunctions;
use App\Repository\ReviewFunctions;
use Framework\AccesControl\AuthenticationService;
use Framework\Http\Classes\Request;
use Framework\Http\Classes\Response;
use Framework\Templating\TemplateEngine;
use PDO;

class ErrorController
{
    private BookFunctions $bookFunctions;
    private PDO $pdo;
    private AuthenticationService $authenticationService;
    private TemplateEngine $templateEngine;


    public function __construct(PDO $pdo, AuthenticationService $authenticationService, TemplateEngine $templateEngine)
    {
        $this->pdo = $pdo;
        $this->bookFunctions = new BookFunctions($this->pdo);
        $this->authenticationService = $authenticationService;
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