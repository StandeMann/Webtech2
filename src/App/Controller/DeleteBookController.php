<?php

namespace App\Controller;

use App\Repository\BookFunctions;
use App\Repository\BookRepository;
use Framework\AccesControl\AuthenticationService;
use Framework\Database\Interfaces\ConnectionInterface;
use Framework\Http\Classes\Request;
use Framework\Http\Classes\Response;
use Framework\Templating\TemplateEngine;

class DeleteBookController
{
    private ConnectionInterface $connection;
    private AuthenticationService $authenticationService;
    private TemplateEngine $templateEngine;
    private BookRepository $bookRepository;

    public function __construct(ConnectionInterface $connection, AuthenticationService $authenticationService, TemplateEngine $templateEngine)
    {
        $this->authenticationService = $authenticationService;
        $this->connection = $connection;
        $this->bookRepository = new BookRepository($this->connection);
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
            $this->bookRepository->deleteBook($bookId);
            $location = 'Location: /';
            header($location);
            exit;
        }
        return new Response();

    }

}