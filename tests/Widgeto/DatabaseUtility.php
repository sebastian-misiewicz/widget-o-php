<?php

namespace Test\Widgeto;

use Widgeto\Service\DatabaseConfigService;

class DatabaseUtility {
    
    public static function setUpDatabase() {
        putenv("DATABASE_URL=sqlite3://tests/test.s3db");
        
        \dibi::connect(DatabaseConfigService::getConfig());
        \dibi::getSubstitutes()->{''} = "";
    }
    
}

