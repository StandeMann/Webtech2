<?php

namespace App\Controller;

use App\Repository\BookFunctions;
use App\View\CompileDashBoardView;
use App\View\CompileHeaderView;
use Framework\AccesControl\AuthenticationService;
use Framework\Http\Classes\Request;
use Framework\Http\Classes\Response;
use Framework\Templating\TemplateEngine;
use JetBrains\PhpStorm\NoReturn;
use PDO;

class DashboardController
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
        $this->pdo = $pdo;
        $this->bookFunctions = new BookFunctions($this->pdo);
        $this->authenticationService = $authenticationService;
        $this->templateEngine = $templateEngine;
    }

    public function showPage(Request $request, array $params): Response{
        $dashBoardRender = new CompileDashBoardView($request);
        $headerRender = new CompileHeaderView($request);

        $books = $this->bookFunctions->getBooks($request->getParams());

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
        $data = $request->getPost();
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