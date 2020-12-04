<?php

use App\Middlewares\CorsMiddleware;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app){
    $app->add(CorsMiddleware::class);
    
    $app->group('/user', function (Group $group) {
        $group->post('/login', "App\Controllers\UserController:login");
        $group->post('/register', "App\Controllers\UserController:register");
    });

};


