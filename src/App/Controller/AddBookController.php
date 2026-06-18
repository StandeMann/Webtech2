<?php

namespace App\Controller;

use App\Repository\BookFunctions;
use App\View\CompileAddBookView;
use App\View\CompileHeaderView;
use Framework\AccesControl\AuthenticationService;
use Framework\Http\Request;
use Framework\Http\Response;
use PDO;

class AddBookController
{
    private PDO $pdo;
    private BookFunctions $bookFunctions;
    private AuthenticationService $authenticationService;

    /**
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo, AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
        $this->pdo = $pdo;
        $this->bookFunctions = new BookFunctions($this->pdo);
    }

    public function showPage(Request $request): Response{
        $compileHeader = new CompileHeaderView($request);
        $compileAddBook =  new CompileAddBookView($request);
        $user = $request->getUser();
        if(!$user){
            $location = 'Location: /403';
            header($location);
            exit;
        }

        ob_start();

        require __DIR__ . '/../../../views/book-add.html';

        $html = ob_get_clean();

        $html .= $compileHeader->renderHeader();

        $html .= $compileAddBook->compileAddBookForm();

        $html.= "</body></html>";

        return new Response($html);
    }

    public function addBook(Request $request, array $params): Response{
        $data = $request->getPost();
        $user = $request->getUser();
//        $img = $request->getFiles();

//        $imgData = file_get_contents($img['image']['tmp_name']);
        $title = $data['title'];
        $author = $data['author'];
        $genre = $data['genre'];
        $description = $data['description'];

        $this->bookFunctions->addBook($title, $author, $genre, $description, $user->getId());

        header('Location: /');
        exit;
    }
}