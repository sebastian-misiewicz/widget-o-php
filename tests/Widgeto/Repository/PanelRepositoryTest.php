<?php

namespace Test\Widgeto\Service;

use Widgeto\Repository\PanelRepository;
use Test\Widgeto\DatabaseUtility;

class PanelRepositoryTest extends \PHPUnit_Framework_TestCase
{
    
    public static function setUpBeforeClass()
    {
        
        DatabaseUtility::setUpDatabase();
        \dibi::query('delete from ::panel');
        
        \dibi::query('insert into ::panel', [
            "idpanel" => "test",
            "json" => '{"text":"message"}'
        ]);
        
        
    }
    
    public function testShouldFindPanelWithId()
    {
        $stack = new PanelRepository();
        
        $panel = $stack->find("test");
        
        $this->assertEquals("test", $panel["idpanel"]);
    }
    
    public function testShouldFindPanelWithJson()
    {
        $stack = new PanelRepository();
        
        $panel = $stack->find("test");
        
        $this->assertEquals('{"text":"message"}', $panel["json"]);
    }

}