<?php

require 'vendor/autoload.php';

$app = new \Slim\Slim();
$app->get('/:name+', function ($name) {
    
    dibi::connect(array(
        'driver'   => 'mysql',
        'host'     => 'localhost',
        'username' => 'root',
        'password' => 'abcd',
        'database' => 'widget-o'
    ));
    
    $idsite = implode('/', $name);
    
    $result = dibi::query('select idsite, template, json FROM `site` where idsite = %s', $idsite);
    
    // TODO 404 if not found
    $site = $result->fetchAll()[0];
    
    echo str_replace('{page:"page"}', $site->json, file_get_contents("templates/".$site->template));
});
$app->run();

