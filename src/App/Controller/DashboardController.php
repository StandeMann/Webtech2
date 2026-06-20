<?php

namespace App\Controller;

use App\Repository\BookRepository;
use App\View\CompileDashBoardView;
use App\View\CompileHeaderView;
use Framework\AccesControl\AuthenticationService;
use Framework\Database\Interfaces\ConnectionInterface;
use Framework\Http\Classes\Request;
use Framework\Http\Classes\Response;
use Framework\Templating\TemplateEngine;
use JetBrains\PhpStorm\NoReturn;

class DashboardController
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
        $dashBoardRender = new CompileDashBoardView($request);
        $headerRender = new CompileHeaderView($request);

        $books = $this->bookRepository->getBooks($request->getParams());

        $html = $this->templateEngine->render('dashboard', $params);

        $html .= $headerRender->renderHeader();

        $html .= $dashBoardRender->renderFilters($request->getParams());

        $html .= "<main class='dashboard'>";

        $html .= $dashBoardRender->renderBooks($books);

        $html .= "</main></body></html>";



        return new Response($html);
    }

    #[NoReturn]
    public function useFilter(Request $request): Response{
        $data = $request->getAttributes();
        $title  = $data['title'] ?? '';
        $author = $data['author'] ?? '';
        $genre  = $data['genre'] ?? '';
        $query = http_build_query([
            'title'  => $title,
            'author' => $author,
            'genre'  => $genre,
        ]);
        $location = 'Location: /?'.$query;
        header($location);
        exit;
    }

}