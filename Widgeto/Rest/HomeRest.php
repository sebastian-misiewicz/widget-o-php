<?php

namespace Widgeto\Rest;

use Widgeto\Service\PageService;

class HomeRest {    

    /* @var $app \Slim\Slim */
    public function __construct($app) {
        $parent = $this;
        $app->get('/:name+', function ($name) use ($app, $parent) {
            $parent->getPage($app, $name);
        });
        
        $app->get('/', function () use ($app, $parent) {
            $parent->getPage($app, array('index.html'));
        });
    }
    
    function getPage($app, $name)  {
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
    }
    
}
