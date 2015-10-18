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
        
        $app->get('/', function () {
            header("Location: index.html");
            exit;
        });
    }
    
    function getPage($app, $name)  {
        $page = PageService::getPage($name);
            
        if ($page == NULL) {
            $app->notFound();
        }

        $sourceDirectory = "rendered";
        $sourceFile = $page->idpage;
        if (!empty($_COOKIE["auth-token"])) {
            $sourceDirectory = "templates";
            $sourceFile = $page->template;
        }

        echo str_replace(
                array('{idpage}', '{page:"page"}'), array($page->idpage, $page->json), file_get_contents($sourceDirectory . "/" . $sourceFile));
    }
    
}
