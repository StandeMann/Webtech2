<?php

namespace App\Controller;

use App\Repository\BookFunctions;
use App\Repository\ReviewFunctions;
use Framework\AccesControl\AuthenticationService;
use Framework\Http\Classes\Request;
use Framework\Http\Classes\Response;
use PDO;

class DeleteBookController
{
    private BookFunctions $bookFunctions;
    private PDO $pdo;
    private ReviewFunctions $reviewFunctions;
    private AuthenticationService $authenticationService;

    /**
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo, AuthenticationService $authenticationService)
    {
        $this->pdo = $pdo;
        $this->bookFunctions = new BookFunctions($this->pdo);
        $this->reviewFunctions = new ReviewFunctions($this->pdo);
        $this->authenticationService = $authenticationService;
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