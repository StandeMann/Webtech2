<?php

namespace App\Controller;

use App\Repository\BookFunctions;
use App\Repository\ReviewFunctions;
use Framework\AccesControl\AuthenticationService;
use Framework\Database\ConnectionInterface;
use Framework\Http\Classes\Request;
use Framework\Http\Classes\Response;
use Framework\Templating\TemplateEngine;
use PDO;

class DeleteBookController
{
    private ConnectionInterface $connection;
    private BookFunctions $bookFunctions;
    private AuthenticationService $authenticationService;
    private TemplateEngine $templateEngine;

    public function __construct(ConnectionInterface $connection, AuthenticationService $authenticationService, TemplateEngine $templateEngine)
    {
        $this->authenticationService = $authenticationService;
        $this->connection = $connection;
        $this->bookFunctions = new BookFunctions($this->connection);
        $this->templateEngine = $templateEngine;
    }

    public function deleteBook(Request $request, array $params): Response{
        $user = $request->getUser();
        if (!$user){
            $location = 'Location: /403';
            header($location);
            exit;
        }

        if ($user->getRole() == 'user') {
            $location = 'Location: /403';
            header($location);
            exit;
        }

        if ($user->getRole() == 'admin') {
            $bookId = (int)$params['id'];
            $this->bookFunctions->deleteBook($bookId);
            $location = 'Location: /';
            header($location);
            exit;
        }
        return new Response();

    }

}