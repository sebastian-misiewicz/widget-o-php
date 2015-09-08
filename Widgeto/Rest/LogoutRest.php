<?php

namespace Widgeto\Rest;

use Widgeto\Service\AuthService;

class LogoutRest {    

    /* @var $app \Slim\Slim */
    public function __construct($app) {
        $app->post('/rest/logout/', function () use ($app) {
            $token = $app->request->headers("auth-token");
            
            if (!isset($token) || empty($token)) {
                $app->error();
            }
            
            AuthService::removeToken($token);
        });
    }
    
}
