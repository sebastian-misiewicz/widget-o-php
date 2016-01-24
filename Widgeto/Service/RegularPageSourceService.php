<?php

namespace Widgeto\Service;

class RegularPageSourceService implements IPageSourceService {
    
    public function getRendered($page) {
        return file_get_contents("rendered/" . $page);
    }

    public function getTemplate($page) {
        return file_get_contents("templates/" . $page);
    }
    
    public function putRendered($page, $content) {
        file_put_contents("rendered/" . $page, $content);
    }
    

}

