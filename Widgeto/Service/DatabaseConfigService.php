<?php

namespace Widgeto\Service;

class DatabaseConfigService {
    
    public static function getConfig() {
        $databaseUrl = getenv('DATABASE_URL');
        
        if ($databaseUrl != "") {
            return self::handle($databaseUrl);
        }
        
        return json_decode(file_get_contents('config/database.json'), true);
    }
    
    private static function handle($databaseUrl) {
        $driver = self::getDriver($databaseUrl);
        switch ($driver) {
            case "sqlite3":
                return self::handleSqlite3($databaseUrl);
            default:
                return self::handlePostgres($databaseUrl);
        }
    }
    
    private static function handleSqlite3($databaseUrl) {
        $matches = [];
        preg_match(
            '/^([a-z0-9]+):\/\/([^:]+)/',
            $databaseUrl,
            $matches);

        return array(
            "driver" => self::mapDriver($matches[1]),
            "database" => $matches[2],
            "unbuffered" => 'true'
        );
    }
    
    private static function handlePostgres($databaseUrl) {
        $matches = [];
        preg_match(
            '/^([a-z]+):\/\/([^:]+):([^@]+)@([^:]+):([^\/]+)\/([^@]+)/',
            $databaseUrl,
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
    
    private static function getDriver($databaseUrl) {
        preg_match(
                '/^([a-z0-9]+):/',
                $databaseUrl,
                $matches);
        
        return $matches[1];
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
