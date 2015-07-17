<?php

namespace Widgeto\Middleware;

use Widgeto\Service\UserService;
use Widgeto\Service\StringService;

class Authorization extends \Slim\Middleware {    

    protected $headers = array();
    
    public function __construct($headers) {
        $this->headers = $headers;
    }
    
    public function call() {
        $app = $this->getApplication();
        if (StringService::startsWith($app->request->getPathInfo(), "/rest/") ) {
            if (!isset($this->headers["auth-token"])) {
                return $this->status403();
            }
            
            $login = json_decode($this->headers["auth-token"], true);
            $result = UserService::check($login["username"], md5($login["password"]));
            if (!$result) {
                return $this->status403();
            }
        }
        
        $this->next->call();
    }
    
    function status403() {
        $this->getApplication()->status(403);
        $this->next->call();
    }
    
}

