<?php

namespace Widgeto\Rest;

use Widgeto\Service\StringService;

class TemplateRest {    

    /* @var $app \Slim\Slim */
    public function __construct($app) {
        $app->get('/rest/templates/', function () {
            $templates = array();
            foreach (scandir("templates") as $file) {
                if (StringService::endsWith($file, ".html")) {
                    $templates[] = $file;
                }
            }
            
            echo json_encode($templates);
        });
    }
    
}
