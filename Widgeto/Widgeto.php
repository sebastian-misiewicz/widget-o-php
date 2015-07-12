<?php

namespace Widgeto;

class Widgeto {

    public function run() {
        $app = new \Slim\Slim();

        $databaseConfig = json_decode(file_get_contents('config/database.json'), true);
        dibi::connect($databaseConfig);

        $app->post('/rest/login.html', function () use ($app) {
            $login = json_decode($app->request->getBody(), true);

            $result = UserService::check($login["username"], md5($login["password"]));

            if (!$result) {
                $app->notFound();
            }
        });

        $app->put('/rest/:name+', function ($name) use ($app) {
            $idsite = implode('/', $name);

            dibi::query(
                    'update `site` set', array('json' => $app->request->getBody()), 'where `idsite` = %s', $idsite);
        });

        $app->get('/rest/:name+', function ($name) {
            $idsite = implode('/', $name);

            $result = dibi::query('select idsite, template, json FROM `site` where idsite = %s', $idsite);

            // TODO 404 if not found
            $site = $result->fetchAll()[0];

            echo $site->json;
        });

        $app->get('/:name+', function ($name) {
            $idsite = implode('/', $name);

            $result = dibi::query('select idsite, template, json FROM `site` where idsite = %s', $idsite);

            // TODO 404 if not found
            $site = $result->fetchAll()[0];

            echo str_replace(
                    array('{idpage}', '{page:"page"}'), array($site->idsite, $site->json), file_get_contents("templates/" . $site->template));
        });
        $app->run();
    }

}
