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
    
    /* @var $idsite String */
    public static function findPage($idsite) {
        $result = \dibi::query('select idsite, template, json FROM `site` where idsite = %s', $idsite);

        $sites = $result->fetchAll();
        if (sizeof($sites) == 0) {
            return NULL;
        }
            
        return $result->fetchAll()[0];
    }
    
    /* @var $idsite String */
    public static function getAll() {
        $result = \dibi::query('select idsite FROM `site`');

        return $result->fetchAll();
    }
    
}

