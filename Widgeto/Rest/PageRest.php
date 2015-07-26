<?php

namespace Widgeto\Rest;

use Widgeto\Service\PageService;

class PageRest {    

    /* @var $app \Slim\Slim */
    public function __construct($app) {
        $app->post('/rest/page', function () use ($app) {
            $page = json_decode($app->request->getBody(), true);
            
            // TODO sebastian Better handle validation errors
            if (!isset($page["idpage"]) || empty($page["idpage"])) {
                $app->error();
            }
            
            if (!isset($page["template"]) || empty($page["template"])) {
                $app->error();
            }
            
            $page["idpage"] = $page["idpage"] . ".html";
            if (PageService::findPage($page["idpage"]) != NULL) {
                $app->error();
            }
            $page["json"] = file_get_contents("templates/" . $page["template"] . ".json");
            
            \dibi::query('insert into `page`', $page);
        });
        
        $app->get('/rest/page/', function () {
            
            echo json_encode(PageService::getAll());
        });
        
        $app->put('/rest/page/:name+', function ($name) use ($app) {
            $idpage = implode('/', $name);
            
            \dibi::query(
                    'update `page` set', array('json' => $app->request->getBody()), 'where `idpage` = %s', $idpage);
        });

        $app->get('/rest/page/:name+', function ($name) {
            $site = PageService::getPage($name);

            echo $site->json;
        });
    }
    
}
