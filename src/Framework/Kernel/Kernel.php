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
use Framework\Http\Interfaces\RequestInterface;
use Framework\Routing\Router;
use Framework\Templating\TemplateEngine;

class Kernel implements KernelInterface
{
    private Connection $connection;
    private AuthenticationService $authenticationService;
    private TemplateEngine $templateEngine;
    public function __construct(Connection $connection, $authenticationService, TemplateEngine $templateEngine){
        $this->connection = $connection;
        $this->authenticationService = $authenticationService;
        $this->templateEngine = $templateEngine;
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }

    public function handle(RequestInterface $request): Response
    {
        $router = new Router();

        $addBookController = new AddBookController($this::getConnection(), $this->authenticationService, $this->templateEngine);
        $bookDetailController = new BookDetailController($this::getConnection(), $this->authenticationService, $this->templateEngine);
        $dashBoardController = new DashBoardController($this::getConnection(), $this->authenticationService, $this->templateEngine);
        $loginController = new LoginController($this::getConnection(), $this->authenticationService, $this->templateEngine);
        $registerController = new RegisterController($this::getConnection(), $this->authenticationService, $this->templateEngine);
        $errorController = new ErrorController($this::getConnection(), $this->authenticationService, $this->templateEngine);
        $logoutController = new LogoutController($this::getConnection(), $this->authenticationService, $this->templateEngine);
        $deleteBookController = new DeleteBookController($this::getConnection(), $this->authenticationService, $this->templateEngine);

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
        try {
            $route = $router->route($request);
            $user = $this->authenticationService->getCurrentUser();

            if ($user) {
                $request = $request->withUser($user);
            }

            $controller = $route['controller'];
            $params = $route['params'];

            return $controller($request, $params);
        } catch (\DomainException $e) {
            $html = $this->templateEngine->render('404', []);
            return new Response($html, 404);
        }
    }
}