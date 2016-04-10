<?php

namespace Widgeto\Rest;

use Widgeto\Repository\PageRepository;
use Widgeto\Service\RegularPageSourceService;
use Widgeto\Service\AwsS3PageSourceService;

class HomeRest {    

    private $pageSourceService;
    
    /* @var $app \Slim\Slim */
    public function __construct($app) {
        $parent = $this;
        $app->get('/:name+', function ($name) use ($app, $parent) {
            $parent->getPage($app, $name);
        });
        
        $app->get('/', function () {
            header("Location: index.html");
            exit;
        });
        switch (getenv("PAGE_SOURCE_HANDLER")) {
            case "AWS_S3":
                $this->pageSourceService = new AwsS3PageSourceService();
                break;
            default:
                $this->pageSourceService = new RegularPageSourceService();
                break;
        }
        
    }
    
    function getPage($app, $name)  {
        $page = PageRepository::getPage($name);
            
        if ($page == NULL) {
            $app->notFound();
        }
        
        $content = '';
        if (!empty($_COOKIE["auth-token"])) {
            $content = $this->pageSourceService->getTemplate($page->template);
        } else {
            $content = $this->pageSourceService->getRendered($page->idpage);
        }

        echo str_replace(
                array('{idpage}', '{page:"page"}'), array($page->idpage, $page->json), $content);
    }
    
}
