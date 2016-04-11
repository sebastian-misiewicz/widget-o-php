<?php

namespace Widgeto\Service;

use Widgeto\Repository\PanelRepository;
use Sunra\PhpSimple\HtmlDomParser;

class PanelService {
    
    private $pageSourceService;
    
    public function __construct() {
        switch (getenv("PAGE_SOURCE_HANDLER")) {
            case "AWS_S3":
                $this->pageSourceService = new AwsS3PageSourceService();
                break;
            default:
                $this->pageSourceService = new RegularPageSourceService();
                break;
        }
    }
    
    public function enhancePanels($json) {
        foreach ($json as $idPanel => $panelJson) {
            if (PanelRepository::exists($idPanel)) {
                $panel = PanelRepository::find($idPanel);
                $json[$idPanel] = json_decode($panel->json, true);
            }
        }
        
        return $json;
    }
    
    public function parsePanels($idPage, $page) {
        $dom = HtmlDomParser::str_get_html($page['html']);
        
        $pages = [];
        foreach (PageRepository::getAllOnlyIds() as $otherPage) {
            $idOtherPage = $otherPage['idpage'];
            if ($idOtherPage != $idPage 
                    && $this->pageSourceService->doesRenderedExist($idOtherPage)) {
                $pages[$idOtherPage] = 
                        HtmlDomParser::str_get_html($this->pageSourceService->getRendered($idOtherPage));
            }
        }
        
        $panelJsons = [];
        foreach ($page["data"] as $idPanel => $panelJson) {
            if (isset($panelJson["isPanel"]) 
                    && $panelJson["isPanel"] == true) {
                
                if (!PanelRepository::exists($idPanel) 
                        || (isset($panelJson["isEdit"]) && $panelJson["isEdit"] == true)) {
                    $panelHtmls = $dom->find('#' . $idPanel);
                    if (count($panelHtmls) > 0) {
                        foreach ($pages as $idOtherPage => $otherPageDom) {
                            $otherPagePanelHtmls = $otherPageDom->find('#' . $idPanel);
                            if (count($otherPagePanelHtmls) > 0) {
                                $otherPagePanelHtmls[0]->outertext = $panelHtmls[0]->outertext;
                            }
                        }
                    }
                    
                    $panelJson["isEdit"] = false;
                    PanelRepository::updateOrInsert($idPanel, $panelJson);
                } else {
                    $panel = PanelRepository::find($idPanel);
                    $panelJsons[$panel->idpanel] =  json_decode($panel->json, true);
                }
            }
        }
        
        foreach ($pages as $idOtherPage => $otherPageDom) {
            $this->pageSourceService->putRendered($idOtherPage, $otherPageDom);
        }
        
        return $panelJsons;
    }
    
}
