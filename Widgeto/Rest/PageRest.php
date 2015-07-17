<?php

namespace Widgeto\Rest;

use Widgeto\Service\PageService;

class PageRest {    

    /* @var $app \Slim\Slim */
    public function __construct($app) {
        $app->put('/rest/page/:name+', function ($name) use ($app) {
            $idsite = implode('/', $name);
            
            \dibi::query(
                    'update `site` set', array('json' => $app->request->getBody()), 'where `idsite` = %s', $idsite);
        });

        $app->get('/rest/page/:name+', function ($name) {
            $site = PageService::getPage($name);

            echo $site->json;
        });
    }
    
}
