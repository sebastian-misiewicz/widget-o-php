<?php

namespace Widgeto;

use Widgeto\Service\PageService;

class Widgeto {

    private $app;
    
    public function run() {
        $app = new \Slim\Slim();
        $this->app = $app;
        
        $app->add(
                new \Widgeto\Middleware\Authorization(
                        getallheaders(),
                        array(
                            '/' => 'GET', 
                            '/rest' => 'GET')));
        
        $databaseConfig = json_decode(file_get_contents('config/database.json'), true);
        \dibi::connect($databaseConfig);

        new \Widgeto\Rest\LoginRest($app);
        new \Widgeto\Rest\PageRest($app);
        new \Widgeto\Rest\TemplateRest($app);

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
        $app->run();
    }

}
