<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Framework\Database\Connection;
use Framework\Database\DatabaseMigrator;

$connection = new Connection();

//$stmt = $connection->getPdo()->prepare("
//    UPDATE users
//    SET role = ?
//    WHERE id = ?
//");
//
//$stmt->execute(['admin', 1]);

$migrator = new DatabaseMigrator();
$migrator->alterDatabase($connection->getPdo());

