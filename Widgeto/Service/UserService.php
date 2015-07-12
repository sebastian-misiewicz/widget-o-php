<?php

namespace Widgeto\Service;

class UserService {
    
    public static function check($username, $password) {
        $result = \dibi::query(
                'select username FROM `user` where username = %s and password = %s', 
                $username, $password);
        
        return $result->count() == 1;
    }
    
}

