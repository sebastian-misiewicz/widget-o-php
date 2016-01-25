<?php

namespace Widgeto;

use Widgeto\Service\GetAllHeadersService;
use Widgeto\Service\DatabaseConfigService;

class Widgeto {

    private $app;
    
    public function run() {
        $app = new \Slim\Slim();
        $this->app = $app;
        
        GetAllHeadersService::fixMissingFunction();
        
        $app->add(
                new \Widgeto\Middleware\Authorization(
                        getallheaders(),
                        array(
                            '^\/$' => 'GET', 
                            '^\/[^.]*.html$' => 'GET',
                            '^\/rest\/login\/$' => 'POST'
                            )));
        
        \dibi::connect(DatabaseConfigService::getConfig());
        \dibi::getSubstitutes()->{''} = getenv("TABLE_PREFIX");

        new \Widgeto\Rest\LoginRest($app);
        new \Widgeto\Rest\LogoutRest($app);
        new \Widgeto\Rest\PageRest($app);
        new \Widgeto\Rest\TemplateRest($app);
        new \Widgeto\Rest\FileRest($app);
        new \Widgeto\Rest\UserRest($app);
        new \Widgeto\Rest\HomeRest($app);

        $app->run();
    }

}
