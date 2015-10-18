<?php

namespace Widgeto\Service;

class PanelService {
    
    /* @var $idPanel String */
    public static function updateOrInsert($idPanel, $panelJson) {
        $panelJson = json_encode($panelJson);
        if(self::exist($idPanel)) {
            \dibi::query(
                    'update `panel` set', array('json' => $panelJson), 'where `idpanel` = %s', $idPanel);
        } else {
            $panel["idpanel"] = $idPanel;
            $panel["json"] = $panelJson;
            \dibi::query('insert into `panel`', $panel);
        }
        
    }
    
    /* @var $idPanel String */
    public static function find($idPanel) {
        $result = \dibi::query('select idpanel, json FROM `panel` where idpanel = %s', $idPanel);

        $panels = $result->fetchAll();
        if (sizeof($panels) == 0) {
            return NULL;
        }
        
        return $result->fetchAll()[0];
    }
    
    public static function exist($idPanel) {
        $result = \dibi::query('select 1 FROM `panel` where idpanel = %s', $idPanel);

        $panels = $result->fetchAll();
        if (sizeof($panels) == 0) {
            return false;
        }
        
        return true;
    }
    
}

