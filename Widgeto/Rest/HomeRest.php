<?php

namespace Widgeto\Rest;

use Widgeto\Service\PageService;

class HomeRest {    

    /* @var $app \Slim\Slim */
    public function __construct($app) {
        $app->get('/:name+', function ($name) use ($app) {
            $page = PageService::getPage($name);
            
            if ($page == NULL) {
                $app->notFound();
            }
            
            $source = "rendered";
            if ($app->request->get('edit') == true) {
                $source = "templates";
            }
            
            echo str_replace(
                    array('{idpage}', '{page:"page"}'), array($page->idpage, $page->json), file_get_contents($source . "/" . $page->template));
        });
        
        $app->get('/', function () use ($app) {
            $page = PageService::getPage(array('index.html'));

            if ($page == NULL) {
                $app->notFound();
            }
            
            echo str_replace(
                    array('{idpage}'), array($page->idpage), file_get_contents("rendered/" . $page->template));
        });
    }
    
}
