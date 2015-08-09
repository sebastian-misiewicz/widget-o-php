<?php

namespace Widgeto\Middleware;

use Widgeto\Service\UserService;
use Widgeto\Service\StringService;

class Authorization extends \Slim\Middleware {    

    protected $headers = array();
    protected $unprotectedUrls = array();
    
    /* 
     * @var $headers array 
     * @var $unprotectedUrls array
     */
    public function __construct($headers, $unprotectedUrls) {
        $this->headers = $headers;
        $this->unprotectedUrls = $unprotectedUrls;
    }
    
    public function call() {
        $app = $this->getApplication();
        
        foreach ($this->unprotectedUrls as $url => $method) {
            if ($app->request->getMethod() == $method && 
                    StringService::startsWith($app->request->getPathInfo(), $url)) {
                
                $this->next->call();
                return;
            }
        }
        
        if (!isset($this->headers["auth-token"])) {
            return $this->status403();
        }

        $login = json_decode($this->headers["auth-token"], true);
        $result = UserService::check($login["username"], md5($login["password"]));
        if (!$result) {
            return $this->status403();
        }
        
        $this->next->call();
    }
    
    function status403() {
        $this->getApplication()->status(403);
    }
    
}

