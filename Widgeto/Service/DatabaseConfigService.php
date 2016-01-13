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
                "driver" => self::mapDriver($matches[1]),
                "host" => $matches[4],
                "username" => $matches[2],
                "password" => $matches[3],
                "database" => $matches[6],
                "port" => $matches[5]
            );
        }
        
        return json_decode(file_get_contents('config/database.json'), true);
    }
    
    private static function mapDriver($driver) {
        switch ($driver) {
            case "postgres":
                return "postgre";
            default :
                return $driver;
        }
    }
    
}
