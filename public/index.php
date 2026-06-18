<?php

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use Framework\Http\Request;
use Framework\Kernel\Kernel;
use Framework\Http\Session;
use Framework\AccesControl\AuthenticationService;
use Framework\Database\Connection;
use App\Repository\UserFunctions;

$session =  new Session();

$connection =  new Connection();

$userFunctions = new UserFunctions();

$authenticationService = new AuthenticationService(
    $session,
    $userFunctions
);

$request = Request::FromGlobals();

$kernel = new Kernel($connection, $authenticationService);

$response = $kernel->handle($request);

$response->send();