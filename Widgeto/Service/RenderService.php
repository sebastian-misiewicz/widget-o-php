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
            $html = self::cleanTails($html);
            $html = self::cleanExchanges($html);
            
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
            return $html;
        }
        
        foreach ($matches as $match) {
            $guard = $match[0];
            $guardedElement = $match[1];
            
            $startIndex = strpos($html, $guard);
            $endIndex = strpos($html, $guardedElement, $startIndex + strlen($guard));
            $html = substr_replace($html, $guardedElement, $startIndex, $endIndex - $startIndex + strlen($guardedElement));
        }
        return $html;
    }
    
    private static function cleanTails($html) {
        $matches = array();
        preg_match_all('/<!-- widget-o:tail:([^;]+); -->/', 
                $html, $matches, 
                PREG_SET_ORDER);
        
        if(sizeof($matches) == 0) {
            return $html;
        }
        
        foreach ($matches as $match) {
            $tail = $match[0];
            $tailedElement = $match[1];
            
            $startIndex = strpos($html, $tailedElement);
            $endIndex = strpos($html, $tail) + strlen($tail);

            $html = substr_replace($html, $tailedElement, $startIndex, $endIndex - $startIndex);
        }
        return $html;
    }
    
    private static function cleanExchanges($html) {
        $matches = array();
        preg_match_all('/<!-- widget-o:exchange:([^;]+);([^;]+); -->/', 
                $html, $matches, 
                PREG_SET_ORDER);

        if(sizeof($matches) == 0) {
            return $html;
        }
        
        foreach ($matches as $match) {
            $pattern = "/" . $match[1] . "/";
            $replacement = $match[2];
            
            $html = str_replace($match[0], "", $html);
            $html = preg_replace($pattern, $replacement, $html);
            
        }
        return $html;
    }
    
}

