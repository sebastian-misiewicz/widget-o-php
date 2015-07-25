<?php

namespace Widgeto\Rest;

use Widgeto\Service\PageService;

class HomeRest {    

    /* @var $app \Slim\Slim */
    public function __construct($app) {
        $app->get('/:name+', function ($name) {
            $site = PageService::getPage($name);

            echo str_replace(
                    array('{idpage}', '{page:"page"}'), array($site->idsite, $site->json), file_get_contents("templates/" . $site->template));
        });
        
        $app->get('/', function () {
            $site = PageService::getPage(array('index.html'));

            echo str_replace(
                    array('{idpage}'), array($site->idsite), file_get_contents("templates/" . $site->template));
        });
    }
    
}
