<?php

namespace Coluna\Controller;

use Zend\View\Model\ViewModel;
use Nucleo\Controller\ControllerGenerico;

class IndexController extends ControllerGenerico{
    
    public function indexAction()
    {
        return new ViewModel();
    }
}
