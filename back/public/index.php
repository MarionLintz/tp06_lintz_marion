<?php

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../bootstrap.php';

$app = AppFactory::create();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();

$routes = require __DIR__ . '/../router.php';
$routes($app);

// Chargement du Middleware
$app->run();