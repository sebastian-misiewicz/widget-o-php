<?php

namespace Widgeto;

class Widgeto {

    private $app;
    
    public function run() {
        $app = new \Slim\Slim();
        $this->app = $app;
        
        $app->add(
                new \Widgeto\Middleware\Authorization(
                        getallheaders(),
                        array(
                            '^\/$' => 'GET', 
                            '^\/[^.]*.html$' => 'GET',
                            '^\/rest\/login\/$' => 'POST'
                            )));
        
        $databaseConfig = json_decode(file_get_contents('config/database.json'), true);
        \dibi::connect($databaseConfig);

        new \Widgeto\Rest\LoginRest($app);
        new \Widgeto\Rest\LogoutRest($app);
        new \Widgeto\Rest\PageRest($app);
        new \Widgeto\Rest\TemplateRest($app);
        new \Widgeto\Rest\FileRest($app);
        new \Widgeto\Rest\HomeRest($app);

        $app->run();
    }

}
