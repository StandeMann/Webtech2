<?php

namespace App\Controller;

use App\Repository\BookFunctions;
use App\View\CompileAddBookView;
use App\View\CompileHeaderView;
use Framework\AccesControl\AuthenticationService;
use Framework\Http\Classes\Request;
use Framework\Http\Classes\Response;
use Framework\Templating\TemplateEngine;
use PDO;

class AddBookController
{
    private PDO $pdo;
    private BookFunctions $bookFunctions;
    private AuthenticationService $authenticationService;
    private TemplateEngine $templateEngine;

    /**
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo, AuthenticationService $authenticationService, TemplateEngine $templateEngine)
    {
        $this->authenticationService = $authenticationService;
        $this->pdo = $pdo;
        $this->bookFunctions = new BookFunctions($this->pdo);
        $this->templateEngine = $templateEngine;
    }

    public function showPage(Request $request, array $params): Response{
        $compileHeader = new CompileHeaderView($request);
        $compileAddBook =  new CompileAddBookView($request);
        $user = $request->getUser();
        if(!$user){
            $location = 'Location: /403';
            header($location);
            exit;
        }

        $html = $this->templateEngine->render('book-add', $params);

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