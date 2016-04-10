<?php

namespace Test\Widgeto\Service;

use Widgeto\Service\DatabaseConfigService;

class DatabaseConfigServiceTest extends \PHPUnit_Framework_TestCase {
    
    public function testShouldFindDriverFromDatabaseUrlEnv() {
        putenv("DATABASE_URL=postgres://username:password@localhost:123456/database");
        
        $config = DatabaseConfigService::getConfig();
        
        $this->assertEquals('postgre', $config['driver']);
    }
    
    public function testShouldFindUsernameFromDatabaseUrlEnv() {
        putenv("DATABASE_URL=postgres://username:password@localhost:123456/database");
        
        $config = DatabaseConfigService::getConfig();
        
        $this->assertEquals('username', $config['username']);
    }
    
    public function testShouldFindPasswordFromDatabaseUrlEnv() {
        putenv("DATABASE_URL=postgres://username:password@localhost:123456/database");
        
        $config = DatabaseConfigService::getConfig();
        
        $this->assertEquals('password', $config['password']);
    }
    
    public function testShouldFindDatabaseFromDatabaseUrlEnv() {
        putenv("DATABASE_URL=postgres://username:password@localhost:123456/database");
        
        $config = DatabaseConfigService::getConfig();
        
        $this->assertEquals('database', $config['database']);
    }
    
    public function testShouldFindHostFromDatabaseUrlEnv() {
        putenv("DATABASE_URL=postgres://username:password@localhost:123456/database");
        
        $config = DatabaseConfigService::getConfig();
        
        $this->assertEquals('localhost', $config['host']);
    }
    
    public function testShouldFindPortFromDatabaseUrlEnv() {
        putenv("DATABASE_URL=postgres://username:password@localhost:123456/database");
        
        $config = DatabaseConfigService::getConfig();
        
        $this->assertEquals('123456', $config['port']);
    }
    
    public function testShouldFindDriverFromDatabaseUrlEnvForSqlite3() {
        putenv("DATABASE_URL=sqlite3://folder/database.s3db");
        
        $config = DatabaseConfigService::getConfig();
        
        $this->assertEquals('sqlite3', $config['driver']);
    }
    
    public function testShouldFindDatabaseFromDatabaseUrlEnvForSqlite3() {
        putenv("DATABASE_URL=sqlite3://folder/database.s3db");
        
        $config = DatabaseConfigService::getConfig();
        
        $this->assertEquals('folder/database.s3db', $config['database']);
    }

}