<?php

namespace Widgeto\Service;

class DatabaseConfigService {
    
    public static function getConfig() {
        $databaseUrl = getenv('DATABASE_URL');
        
        if ($databaseUrl != "") {
            preg_match(
                '/^([a-z]+):\/\/([^:]+):([^@]+)@([^:]+):([^\/]+)\/([^@]+)/',
                getenv('DATABASE_URL'),
                $matches);
            
            return array(
                "driver" => $matches[1],
                "host" => $matches[4],
                "username" => $matches[2],
                "password" => $matches[3],
                "database" => $matches[6],
                "port" => $matches[5]
            );
        }
        
        return json_decode(file_get_contents('config/database.json'), true);
    }
    
}
