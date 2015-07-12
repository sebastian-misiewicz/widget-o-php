<?php

namespace Widgeto\Middleware;

class Authorization extends \Slim\Middleware {    

    protected $headers = array();
    
    public function __construct($headers) {
        $this->headers = $headers;
    }
    
    public function call() {
        // TODO sebastian finish it up
        $this->next->call();
    }

}

