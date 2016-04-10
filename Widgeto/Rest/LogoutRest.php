<?php

namespace Widgeto\Rest;

use Widgeto\Repository\AuthRepository;

class LogoutRest {    

    /* @var $app \Slim\Slim */
    public function __construct($app) {
        $app->post('/rest/logout/', function () use ($app) {
            $token = $app->request->headers("auth-token");
            
            if (!isset($token) || empty($token)) {
                $app->error();
            }
            
            AuthRepository::removeToken($token);
        });
    }
    
}
