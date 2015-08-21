<?php

namespace Widgeto\Rest;

use Widgeto\Service\PageService;

class RenderRest {    

    /* @var $app \Slim\Slim */
    public function __construct($app) {
        $app->post('/rest/render', function () use ($app) {
            $page = json_decode($app->request->getBody(), true);
            
            // TODO sebastian Better handle validation errors
            if (!isset($page["idpage"]) || empty($page["idpage"])) {
                $app->error();
            }
            
            if (!isset($page["html"]) || empty($page["html"])) {
                $app->error();
            }
            
            if (PageService::findPage($page["idpage"]) == NULL) {
                $app->error();
            }
            
            if (!strpos($page["html"], '<html>')) {
                $page["html"] = '<!DOCTYPE html><html lang="en">' . $page["html"];
            }
            
            $page["html"] = preg_replace('/ng-[a-z]+=\"[^\"]+\"/i', "", $page["html"]);
            $page["html"] = preg_replace('/ng-[a-z]+/i', "", $page["html"]);
            $page["html"] = preg_replace('/<!-- widget-o:no-render[^]+/i', "", $page["html"]);
            $page["html"] = $page["html"] + "</body></html>";
            
            file_put_contents("rendered/" . $page["idpage"], $page["html"]);
        });
        
    }
    
}
