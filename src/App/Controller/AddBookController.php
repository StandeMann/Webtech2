<?php

namespace App\Controller;

use App\Model\Book;
use App\Repository\BookFunctions;
use App\Repository\BookRepository;
use App\View\CompileAddBookView;
use App\View\CompileHeaderView;
use Framework\AccesControl\AuthenticationService;
use Framework\Database\Interfaces\ConnectionInterface;
use Framework\Http\Classes\Request;
use Framework\Http\Classes\Response;
use Framework\Templating\TemplateEngine;

class AddBookController
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
        $data = $request->getAttributes();
        $user = $request->getUser();
        $title = $data['title'];
        $author = $data['author'];
        $genre = $data['genre'];
        $description = $data['description'];

        $newBook = new Book(0, $title, $author, $genre, $description, $user->getId(), 0, 0,0);
        $this->bookRepository->addBook($newBook);
        header('Location: /');
        exit;
    }
}