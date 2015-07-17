<?php

namespace Widgeto\Service;

class PageService {
    
    /* @var $name array */
    public static function getPage($name) {
        $idsite = implode('/', $name);
        
        $result = \dibi::query('select idsite, template, json FROM `site` where idsite = %s', $idsite);

        $sites = $result->fetchAll();
        if (sizeof($sites) != 1) {
            $this->app->notFound();
        }
            
        return $result->fetchAll()[0];
    }
    
}

