<?php

namespace App\Controller;

use App\View\CompileDashBoardView;
use App\View\CompileHeaderView;
use Framework\AccesControl\AuthenticationService;
use Framework\Http\Request;
use Framework\Http\Response;
use JetBrains\PhpStorm\NoReturn;
use PDO;
use App\Repository\BookFunctions;

class DashboardController
{
    private PDO $pdo;
    private BookFunctions $bookFunctions;
    private AuthenticationService $authenticationService;

    /**
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo, AuthenticationService $authenticationService)
    {
        $this->pdo = $pdo;
        $this->bookFunctions = new BookFunctions($this->pdo);
        $this->authenticationService = $authenticationService;
    }

    public function showPage(Request $request, array $params): Response{
        $dashBoardRender = new CompileDashBoardView($request);
        $headerRender = new CompileHeaderView($request);

        $books = $this->bookFunctions->getBooks($request->getParams());

        ob_start();

        require __DIR__ . '/../../../views/dashboard.html';

        $html = ob_get_clean();

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