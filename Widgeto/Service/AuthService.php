<?php

namespace Widgeto\Service;

class AuthService {
    
    public static function checkToken($token) {
        $result = \dibi::query(
                'select username FROM `oauth` where token = %s', 
                $token);
        
        return $result->count() == 1;
    }
    
}

