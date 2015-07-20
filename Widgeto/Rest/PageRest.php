<?php

namespace Widgeto\Rest;

use Widgeto\Service\PageService;

class PageRest {    

    /* @var $app \Slim\Slim */
    public function __construct($app) {
        $app->post('/rest/page', function () use ($app) {
            $page = json_decode($app->request->getBody(), true);
            
            // TODO sebastian Better handle validation errors
            if (!isset($page["idsite"]) || empty($page["idsite"])) {
                $app->error();
            }
            
            if (!isset($page["template"]) || empty($page["template"])) {
                $app->error();
            }
            
            $page["idsite"] = $page["idsite"] . ".html";
            if (PageService::findPage($page["idsite"]) != NULL) {
                $app->error();
            }
            $page["json"] = file_get_contents("templates/" . $page["template"] . ".json");
            
            \dibi::query('insert into `site`', $page);
        });
        
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
