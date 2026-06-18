<?php

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use App\Repository\UserFunctions;
use Framework\AccesControl\AuthenticationService;
use Framework\Database\Connection;
use Framework\Http\Classes\Request;
use Framework\Http\Classes\Session;
use Framework\Kernel\Kernel;
use Framework\Templating\TemplateEngine;

$session =  new Session();

$connection =  new Connection();

$templateEngine = new TemplateEngine();

$userFunctions = new UserFunctions();

$authenticationService = new AuthenticationService(
    $session,
    $userFunctions
);

$request = Request::FromGlobals();

$kernel = new Kernel($connection, $authenticationService, $templateEngine);

$response = $kernel->handle($request);

$response->send();