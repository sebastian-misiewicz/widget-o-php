<?php

namespace Widgeto\Repository;

class PageRepository {
    
    /* @var $name array */
    public static function getPage($name) {
        if (is_array($name)) {
            $idpage = implode('/', $name);
        } else {
            $idpage = $name;
        }
        
        $result = \dibi::query('select idpage, template, json FROM ::page where idpage = %s', $idpage);

        $sites = $result->fetchAll();
        if (sizeof($sites) != 1) {
            return NULL;
        }
            
        return $result->fetchAll()[0];
    }
    
    /* @var $idpage String */
    public static function findPage($idpage) {
        $result = \dibi::query('select idpage, template, json FROM ::page where idpage = %s', $idpage);

        $sites = $result->fetchAll();
        if (sizeof($sites) == 0) {
            return NULL;
        }
        
        return $result->fetchAll()[0];
    }
    
    public static function getAll() {
        $result = \dibi::query('select idpage, template FROM ::page');

        return $result->fetchAll();
    }
    
    public static function getAllOnlyIds() {
        $result = \dibi::query('select idpage FROM ::page');

        return $result->fetchAll();
    }
    
}

