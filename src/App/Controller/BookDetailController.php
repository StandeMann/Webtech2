<?php

namespace App\Controller;

use App\Repository\BookFunctions;
use App\Repository\ReviewFunctions;
use App\View\CompileHeaderView;
use App\View\CompileReviewView;
use App\View\ReviewView;
use Framework\AccesControl\AuthenticationService;
use Framework\Database\ConnectionInterface;
use Framework\Http\Classes\Request;
use Framework\Http\Classes\Response;
use Framework\Templating\TemplateEngine;
use PDO;

class BookDetailController
{
    private ConnectionInterface $connection;
    private BookFunctions $bookFunctions;
    private AuthenticationService $authenticationService;
    private TemplateEngine $templateEngine;
    private ReviewFunctions $reviewFunctions;
    public function __construct(ConnectionInterface $connection, AuthenticationService $authenticationService, TemplateEngine $templateEngine)
    {
        $this->authenticationService = $authenticationService;
        $this->connection = $connection;
        $this->bookFunctions = new BookFunctions($this->connection);
        $this->reviewFunctions = new ReviewFunctions($this->connection);
        $this->templateEngine = $templateEngine;
    }

    public function showPage(Request $request, array $params): Response{
        $user = $request->getUser();

        $id = (int)$params['id'];

        $book = $this->bookFunctions->getBook($id);

        if((!$user || $user->getRole() == "user") && $book->showable === 0){
            $location = 'Location: /403';
            header($location);
            exit;
        }

        $reviewRender = new CompileReviewView($request);
        $headerRender = new CompileHeaderView($request);

        $html = $this->templateEngine->render('book-detail', $params);

        $html .= $headerRender->renderHeader();

        $html .= $reviewRender->renderBookDetail($book, $id);

        $html.= $reviewRender->renderReviewForm($id);

        $reviews = $this->reviewFunctions->getReviews($id);

        $html .= $reviewRender->renderReviewList($reviews, $id);

        $html .= "</main></body></html>";

        return new Response($html);
    }

    public function addReview(Request $request, array $params): Response{
        $data = $request->getAttributes();
        $stars = $data['stars'];
        $description = $data['description'];
        $id = (int)$params['id'];
        $user  = $request->getUser();
        $this->reviewFunctions->addReview($description, $stars, $id, $user->getId());

        $location = 'Location: /bookDetail/'.$id;

        header($location);
        exit;
    }

    public function makeVisible(Request $request, array $params): Response{
        $user = $request->getUser();
        if ($user->getRole() != 'admin') {
            $location = 'Location: /403';
            header($location);
            exit;
        }
        $id = (int)$params['id'];
        $book = $this->bookFunctions->getBook($id);

        if ($book->showable === 0) {
            $this->bookFunctions->makeBookVisible($id);
        }
        if ($book->showable === 1) {
            $this->bookFunctions->makeBookHidden($id);
        }

        $location = 'Location: /bookDetail/'.$id;

        header($location);
        exit;
    }

    public function deleteReview(Request $request, array $params): Response{
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
            $reviewId = (int)$params['id'];
            $this->reviewFunctions->deleteReview($reviewId);
            $location = 'Location: /';
            header($location);
            exit;
        }
        return new Response();

    }
}