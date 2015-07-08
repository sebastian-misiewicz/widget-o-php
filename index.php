<?php

require 'vendor/autoload.php';

$app = new \Slim\Slim();
$app->get('/:name+', function ($name) {
    echo file_get_contents("./templates/index.html");
});
$app->run();

