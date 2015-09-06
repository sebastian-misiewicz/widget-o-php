<?php

namespace Widgeto\Service;

class AuthService {
    
    public static function checkToken($token) {
        $result = \dibi::query(
                'select username FROM `oauth` where token = %s', 
                $token);
        
        return $result->count() == 1;
    }
    
    public static function removeToken($token) {
        \dibi::query(
                'delete from `oauth` where token = %s', 
                $token);
    }
    
}

