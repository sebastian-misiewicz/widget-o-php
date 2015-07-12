<?php

namespace Widgeto\Middleware;

use Widgeto\Service\UserService;

class Authorization extends \Slim\Middleware {    

    protected $headers = array();
    
    public function __construct($headers) {
        $this->headers = $headers;
    }
    
    public function call() {
        $app = $this->getApplication();
        if ($this->startsWith($app->request->getPathInfo(), "/rest/") ) {
            $login = json_decode($this->headers["auth-token"], true);
            $result = UserService::check($login["username"], md5($login["password"]));
            if (!$result) {
                $app->status(403);
            }
        }
        
        $this->next->call();
    }
    
    // Thanks: http://stackoverflow.com/a/10473026
    function startsWith($haystack, $needle) {
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
    }

}

