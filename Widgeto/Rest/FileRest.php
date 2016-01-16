<?php

namespace Widgeto\Rest;

use Widgeto\Service\UploadHandler;
use Widgeto\Service\AwsS3FileHandler;

class FileRest {    

    /* @var $app \Slim\Slim */
    public function __construct($app) {
        
        
        $fileHandler = getenv("FILE_HANDLER");
        
        $app->post('/rest/file', function () use ($fileHandler) {
            switch ($fileHandler) {
                case "AWS_S3":
                    $handler = new AwsS3FileHandler();
                    $handler->upload();
                    break;
                default:
                    $handler = new UploadHandler(null, false);
                    $handler->post();
                    break;
            }
        });
        $app->get('/rest/file', function () use ($fileHandler) {
            switch ($fileHandler) {
                case "AWS_S3":
                    $handler = new AwsS3FileHandler();
                    echo json_encode($handler->getAllFiles());
                    break;
                default:
                    $handler = new UploadHandler(null, false);
                    $handler->get();
                    break;
            }
        });
        $app->get('/rest/file/:type', function ($type) use ($fileHandler) {
            switch ($fileHandler) {
                case "AWS_S3":
                    $handler = new AwsS3FileHandler();
                    echo json_encode($handler->getAllFiles($type));
                    break;
                default:
                    $handler = new UploadHandler(null, false);
                    $iterationMethod = null;
                    switch ($type) {
                        case 'image':
                            $iterationMethod = 'get_image_object';
                            break;
                    }
                    $handler->get(true, $iterationMethod);
                    break;
            }
        });
        $app->delete('/rest/file', function () use ($fileHandler) {
            switch ($fileHandler) {
                case "AWS_S3":
                    $handler = new AwsS3FileHandler();
                    $handler->delete();
                    break;
                default:
                    $handler = new UploadHandler(null, false);
                    $handler->delete();
                    break;
            }
        });
        
    }
    
}
