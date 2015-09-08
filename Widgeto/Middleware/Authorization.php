<?php

namespace Widgeto\Middleware;

use Widgeto\Service\AuthService;
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
        
        if($this->unprotectedUrls) {
            foreach ($this->unprotectedUrls as $url => $method) {
                if ($app->request->getMethod() == $method && 
                        preg_match("/" . $url . "/", $app->request->getPathInfo())) {

                    $this->next->call();
                    return;
                }
            }
        }
        
        if (!isset($this->headers["auth-token"])) {
            return $this->status403();
        }

        $token = $this->headers["auth-token"];
        
        if (!AuthService::checkToken($token)) {
            return $this->status403();
        }

        $this->next->call();
    }
    
    function status403() {
        $this->getApplication()->status(403);
    }
    
}

