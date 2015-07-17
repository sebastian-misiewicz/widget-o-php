<?php

namespace Widgeto\Rest;

class LoginRest {    

    /* @var $app \Slim\Slim */
    public function __construct($app) {
        $app->post('/rest/login/', function () {
            // Do nothing here. See \Widgeto\Middleware\Authorization            
        });
    }
    
}
