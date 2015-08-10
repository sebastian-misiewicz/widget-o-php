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
        $app->get('/rest/file/:type', function ($type) use ($uploadHandler) {
            $iterationMethod = null;
            switch ($type) {
                case 'image':
                    $iterationMethod = 'get_image_object';
                    break;
            }
            $uploadHandler->get(true, $iterationMethod);
        });
        $app->delete('/rest/file', function () use ($uploadHandler) {
            $uploadHandler->delete();
        });
        
    }
    
}
