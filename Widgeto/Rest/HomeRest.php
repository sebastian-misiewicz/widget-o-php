<?php

namespace Widgeto\Rest;

use Widgeto\Service\PageService;

class HomeRest {    

    /* @var $app \Slim\Slim */
    public function __construct($app) {
        $app->get('/:name+', function ($name) {
            $page = PageService::getPage($name);

            echo str_replace(
                    array('{idpage}', '{page:"page"}'), array($page->idpage, $page->json), file_get_contents("templates/" . $page->template));
        });
        
        $app->get('/', function () {
            $page = PageService::getPage(array('index.html'));

            echo str_replace(
                    array('{idpage}'), array($page->idpage), file_get_contents("templates/" . $page->template));
        });
    }
    
}
