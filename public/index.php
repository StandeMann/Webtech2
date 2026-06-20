<?php

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use App\Repository\UserRepository;
use Framework\AccesControl\AuthenticationService;
use Framework\Database\Classes\Connection;
use Framework\Http\Classes\Request;
use Framework\Http\Classes\Session;
use Framework\Kernel\Kernel;
use Framework\Templating\TemplateEngine;

$session =  new Session();

$connection =  new Connection();

$templateEngine = new TemplateEngine();

$userRepository = new UserRepository($connection);

$authenticationService = new AuthenticationService(
    $session,
    $userRepository
);

$request = Request::FromGlobals();

$kernel = new Kernel($connection, $authenticationService, $templateEngine);

$response = $kernel->handle($request);

$response->send();