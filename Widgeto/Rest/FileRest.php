<?php

namespace Widgeto\Rest;

use Widgeto\Service\UploadHandler;

class FileRest {    

    /* @var $app \Slim\Slim */
    public function __construct($app) {
        $uploadHandler = new UploadHandler(null, false);
        
        $app->post('/rest/file', function () use ($uploadHandler) {
            $uploadHandler->post();
        });
        $app->get('/rest/file', function () use ($uploadHandler) {
            $uploadHandler->get();
        });
        
    }
    
}
