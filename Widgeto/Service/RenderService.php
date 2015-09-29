<?php

namespace Widgeto\Service;

class RenderService {
    
    /* @var $html String */
    public static function clean($html) {
            if (!strpos($html, '<html>')) {
                $html = '<!DOCTYPE html><html lang="en">' . $html;
            }
            
            $html = preg_replace('/([\" ])ng-[a-z\-]+=\"[^\"]+\"/i', "$1", $html);
            $html = preg_replace('/([\" ])ng-[a-z]+/i', "$1", $html);
            $html = preg_replace('/[a-z]+=\"[ ]*\"/i', "", $html);
            $html = preg_replace('/<!-- widget-o:no-render.+/si', "", $html);
            $html = $html . "</body></html>";
            
            return $html;
    }
    
}

