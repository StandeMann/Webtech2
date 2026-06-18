<?php

namespace Framework\Kernel;

use App\Controller\AddBookController;
use App\Controller\BookDetailController;
use App\Controller\DashboardController;
use App\Controller\DeleteBookController;
use App\Controller\ErrorController;
use App\Controller\LoginController;
use App\Controller\LogoutController;
use App\Controller\RegisterController;
use Framework\AccesControl\AuthenticationService;
use Framework\Database\Connection;
use Framework\Http\Classes\Request;
use Framework\Http\Classes\Response;
use Framework\Routing\Router;

class Kernel
{
    private Connection $connection;
    private AuthenticationService $authenticationService;
    public function __construct(Connection $connection, $authenticationService){
        $this->connection = $connection;
        $this->authenticationService = $authenticationService;
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }

    public function handle(Request $request): Response
    {
        $router = new Router();

        $addBookController = new AddBookController($this::getConnection()->getPdo(), $this->authenticationService);
        $bookDetailController = new BookDetailController($this::getConnection()->getPdo(), $this->authenticationService);
        $dashBoardController = new DashBoardController($this::getConnection()->getPdo(), $this->authenticationService);
        $loginController = new LoginController($this::getConnection()->getPdo(), $this->authenticationService);
        $registerController = new RegisterController($this::getConnection()->getPdo(), $this->authenticationService);
        $errorController = new ErrorController($this::getConnection()->getPdo(), $this->authenticationService);
        $logoutController = new LogoutController($this::getConnection()->getPdo(), $this->authenticationService);
        $deleteBookController = new DeleteBookController($this::getConnection()->getPdo(), $this->authenticationService);

        $router->add(
            'GET',
            '/addBook',
            [$addBookController, 'showPage']
        );

        $router->add(
            'POST',
        '/addBook',
        [$addBookController, 'addBook']);

        $router->add(
            'GET',
            '/bookDetail/{id}',
            [$bookDetailController, 'showPage']
        );

        $router->add(
            'POST',
            '/bookDetail/{id}/review',
            [$bookDetailController, 'addReview']
        );

        $router->add(
            'POST',
            '/bookDetail/deleteReview/{id}',
            [$bookDetailController, 'deleteReview']
        );

        $router->add(
            'POST',
            '/bookDetail/{id}/visible',
            [$bookDetailController, 'makeVisible']
        );

        $router->add(
            'GET',
            '/',
            [$dashBoardController, 'showPage']
        );

        $router->add(
            'POST',
            '/',
            [$dashBoardController, 'useFilter']
        );

        $router->add(
            'POST',
            '/deleteBook/{id}',
            [$deleteBookController, 'deleteBook']
        );

        $router->add(
            'GET',
            '/login',
            [$loginController, 'showPage']
        );

        $router->add(
            'POST',
            '/login',
            [$loginController, 'loginUser']
        );

        $router->add(
            'GET',
            '/register',
            [$registerController, 'showPage']
        );

        $router->add(
            'POST',
            '/register',
            [$registerController, 'register']
        );

        $router->add(
            'GET',
            '/logout',
            [$logoutController, 'logoutUser']
        );

        $router->add(
            'GET',
            '/403',
            [$errorController, 'error403']
        );

        $router->add(
            'GET',
            '/404',
            [$errorController, 'error404']
        );


        $route = $router->match($request);

        if (!$route) {
            ob_start();

            require __DIR__ . '/../../../views/404.html';

            $html = ob_get_clean();
            return new Response($html, 404);
        }

        $user = $this->authenticationService->getCurrentUser();

        if ($user) {
            $request = $request->withUser($user);
        }

        $controller = $route['controller'];
        $params = $route['params'];

        return $controller($request, $params);
    }
}