<?php

namespace Widgeto\Rest;

use Widgeto\Repository\PageRepository;
use Widgeto\Repository\PanelRepository;
use Widgeto\Service\PanelService;
use Widgeto\Service\RenderService;
use Widgeto\Service\RegularPageSourceService;
use Widgeto\Service\AwsS3PageSourceService;


class PageRest {    

    private $pageSourceService;
    
    private $panelService;
    
    private $template;
    
    /* @var $app \Slim\Slim */
    public function __construct($app) {
        $parent = $this;
        $this->template = getenv("TEMPLATE") ? getenv("TEMPLATE") . "/" : "";
        $this->panelService = new PanelService();
        
        switch (getenv("PAGE_SOURCE_HANDLER")) {
            case "AWS_S3":
                $this->pageSourceService = new AwsS3PageSourceService();
                break;
            default:
                $this->pageSourceService = new RegularPageSourceService();
                break;
        }
        
        $app->post('/rest/page', function () use ($app, $parent) {
            $page = json_decode($app->request->getBody(), true);
            
            // TODO sebastian Better handle validation errors
            if (!isset($page["idpage"]) || empty($page["idpage"])) {
                $app->error();
            }
            
            if (!isset($page["template"]) || empty($page["template"])) {
                $app->error();
            }
            
            $page["idpage"] = $page["idpage"] . ".html";
            if (PageRepository::findPage($page["idpage"]) != NULL) {
                $app->error();
            }
            $json = file_get_contents("templates/" . $parent->template . $page["template"] . ".json");
            
            $page["json"] = json_encode(
                                $parent->panelService->enhancePanels(
                                    json_decode($json, true)));
            
            \dibi::query('insert into ::page', $page);
        });
        
        $app->get('/rest/page/', function () {
            
            echo json_encode(PageRepository::getAll());
        });
        
        $app->put('/rest/page/:name+', function ($name) use ($app, $parent) {
            $idpage = implode('/', $name);
            if (!isset($idpage) || empty($idpage)) {
                $app->error();
            }
            
            $page = json_decode($app->request->getBody(), true);
            if (!isset($page["html"]) || empty($page["html"])) {
                $app->error();
            }
            
            $page["html"] = RenderService::clean($page["html"]);
            
            $parent->pageSourceService->putRendered($idpage, $page["html"]);
            
            $panelJsons = $parent->panelService->parsePanels($idpage, $page);
            if (count($panelJsons) > 0) {
                echo json_encode($panelJsons);
            }
            
            $jsonString = json_encode($page["data"]);
            \dibi::query(
                    'update ::page set', array('json' => $jsonString), 'where `idpage` = %s', $idpage);
        });
        
        $app->delete('/rest/page/:name+', function ($name) use ($app) {
            $idpage = implode('/', $name);
            if (!isset($idpage) || empty($idpage)) {
                $app->error();
            }
            
            \dibi::query(
                    'delete from ::page where idpage = %s', $idpage);
        });

        $app->get('/rest/page/:name+', function ($name) {
            $site = PageRepository::getPage($name);

            $pageJson = json_decode($site->json, true);
            foreach ($pageJson as $idPanel => $panelJson) {
                if (isset($panelJson["isPanel"]) && $panelJson["isPanel"] == true) {
                    $panel = PanelRepository::find($idPanel);
                    if ($panel != NULL) {
                        $pageJson[$idPanel] = json_decode(PanelService::find($idPanel)->json, true);
                    }
                }
            }
            
            echo json_encode($pageJson);
        });
    }
    
    
    
}
