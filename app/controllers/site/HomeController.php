<?php

namespace app\controllers\site;

use app\library\View; 
use app\library\Controller;
use app\library\Auth;
use app\library\Cache\Cache;
use DateTime;

class HomeController  extends Controller
{

  public function index()
  {
 
    $this->view->setPageTitle('Página Inicial');
  }
}
