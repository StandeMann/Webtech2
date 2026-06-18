<?php

namespace App\Controller;

use App\Repository\BookFunctions;
use App\Repository\ReviewFunctions;
use Framework\AccesControl\AuthenticationService;
use Framework\Http\Request;
use Framework\Http\Response;
use PDO;

class ErrorController
{
    private BookFunctions $bookFunctions;
    private PDO $pdo;
    private ReviewFunctions $reviewFunctions;
    private AuthenticationService $authenticationService;


    public function __construct(PDO $pdo, AuthenticationService $authenticationService)
    {
        $this->pdo = $pdo;
        $this->bookFunctions = new BookFunctions($this->pdo);
        $this->reviewFunctions = new ReviewFunctions($this->pdo);
        $this->authenticationService = $authenticationService;
    }

    public function error404(Request $request, array $params): Response{
        ob_start();

        require __DIR__ . '/../../../views/404.html';

        $html = ob_get_clean();

        return new Response($html);
    }

    public function error403(Request $request, array $params): Response{
        ob_start();

        require __DIR__ . '/../../../views/403.html';

        $html = ob_get_clean();

        return new Response($html);
    }


}