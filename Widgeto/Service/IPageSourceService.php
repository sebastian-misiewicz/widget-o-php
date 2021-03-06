<?php

namespace Widgeto\Service;

interface IPageSourceService {
    
    public function getTemplate($page);
    
    public function getRendered($page);
    
    public function doesRenderedExist($page);
    
    public function putRendered($page, $content);
}

