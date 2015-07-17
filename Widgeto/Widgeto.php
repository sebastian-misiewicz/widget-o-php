<?php

namespace Widgeto;

use Widgeto\Service\StringService;

class Widgeto {

    private $app;
    
    public function run() {
        $app = new \Slim\Slim();
        $this->app = $app;
        
        $app->add(new \Widgeto\Middleware\Authorization(getallheaders()));
        
        $databaseConfig = json_decode(file_get_contents('config/database.json'), true);
        \dibi::connect($databaseConfig);

        $app->post('/rest/login/', function () {
            // Do nothing here. See \Widgeto\Middleware\Authorization
        });
        
        $app->get('/rest/templates/', function () {
            $templates = array();
            foreach (scandir("templates") as $file) {
                if (StringService::endsWith($file, ".html")) {
                    $templates[] = $file;
                }
            }
            
            echo json_encode($templates);
        });

        $app->put('/rest/:name+', function ($name) use ($app) {
            $idsite = implode('/', $name);
            
            \dibi::query(
                    'update `site` set', array('json' => $app->request->getBody()), 'where `idsite` = %s', $idsite);
        });

        $app->get('/rest/:name+', function ($name) {
            $site = $this->getSite($name);

            echo $site->json;
        });

        $app->get('/:name+', function ($name) {
            $site = $this->getSite($name);

            echo str_replace(
                    array('{idpage}', '{page:"page"}'), array($site->idsite, $site->json), file_get_contents("templates/" . $site->template));
        });
        
        $app->get('/', function () {
            $site = $this->getSite(array('index.html'));

            echo str_replace(
                    array('{idpage}', '{page:"page"}'), array($site->idsite, $site->json), file_get_contents("templates/" . $site->template));
        });
        $app->run();
    }
    
    private function getSite($name) {
        $idsite = implode('/', $name);
        
        $result = \dibi::query('select idsite, template, json FROM `site` where idsite = %s', $idsite);

        $sites = $result->fetchAll();
        if (sizeof($sites) != 1) {
            $this->app->notFound();
        }
            
        return $result->fetchAll()[0];
    }

}
