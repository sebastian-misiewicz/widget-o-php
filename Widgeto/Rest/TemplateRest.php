<?php

namespace Widgeto\Rest;

use Widgeto\Service\StringService;

class TemplateRest {    

    private $template;
    
    /* @var $app \Slim\Slim */
    public function __construct($app) {
        $parent = $this;
        $this->template = getenv("TEMPLATE") ? "/" . getenv("TEMPLATE") : "";
        
        $app->get('/rest/template/', function () use ($parent) {
            $templates = array();
            foreach (scandir("templates" . $parent->template) as $file) {
                if (StringService::endsWith($file, ".html")) {
                    $templates[] = $file;
                }
            }
            
            echo json_encode($templates);
        });
    }
    
}
