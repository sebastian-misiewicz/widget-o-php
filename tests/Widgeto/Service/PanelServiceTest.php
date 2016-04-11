<?php

namespace Test\Widgeto\Service;

use Widgeto\Service\PanelService;
use Test\Widgeto\DatabaseUtility;

class PanelServiceTest extends \PHPUnit_Framework_TestCase {

    public static function setUpBeforeClass() {
        DatabaseUtility::setUpDatabase();
        \dibi::query('delete from ::panel');

        \dibi::query('insert into ::panel', [
            "idpanel" => "testPanel",
            "json" => '{"text":"message","isPanel":true,"isEdit":false}'
        ]);
    }
    
    public function testShouldEnhancePanel() {
        // given
        $service = new PanelService();
        $jsonString = file_get_contents(__DIR__ . "/_files/PanelServiceTest-testShouldEnhancePanel.json");
        $jsonResultString = file_get_contents(__DIR__ . "/_files/PanelServiceTest-testShouldEnhancePanel-result.json");
        $json = json_decode($jsonString, true);
        
        // when
        $enhancedJson = $service->enhancePanels($json);
        
        // then
        $this->assertJsonStringEqualsJsonString(
                $jsonResultString, 
                json_encode($enhancedJson));
    }

}
