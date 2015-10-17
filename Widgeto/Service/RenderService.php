<?php

namespace Widgeto\Service;

class RenderService {
    
    /* @var $html String */
    public static function clean($html) {
            if (!strpos($html, '<html>')) {
                $html = '<!DOCTYPE html><html lang="en">' . $html;
            }
            if (!strpos($html, '</html>')) {
                $html = $html . '</html>';
            }
            
            $html = self::cleanGuards($html);
            
            
            $html = preg_replace('/([\" ])ng-[a-z\-]+=\"[^\"]+\"/i', "$1", $html);
            $html = preg_replace('/([\" ])ng-[a-z]+/i', "$1", $html);
            $html = preg_replace('/[a-z]+=\"[ ]*\"/i', "", $html);
            
            return $html;
    }
    
    private static function cleanGuards($html) {
        $matches = array();
        preg_match_all('/<!-- widget-o:guard:([^;]+); -->/', 
                $html, $matches, 
                PREG_SET_ORDER);
        
        if(sizeof($matches) == 0) {
            return;
        }
        
        foreach ($matches as $match) {
            $guard = $match[0];
            $guardedElement = $match[1];
            $escapedGuardedElement = preg_replace("/\//", "\/", $guardedElement);
            
            $guardEnd = preg_replace("/{$escapedGuardedElement};/", "{$guardedElement};end", $guard);
            $startIndex = strpos($html, $guard);
            $endIndex = strpos($html, $guardedElement, $startIndex + strlen($guard));
            $html = substr_replace($html, $guardedElement, $startIndex, $endIndex - $startIndex + strlen($guardedElement));
        }
        return $html;
    }
    
}

