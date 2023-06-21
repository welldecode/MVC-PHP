<?php

namespace app\controllers\admin;

use app\library\View; 
use app\library\Controller; 
use DateTime;

class AdminController extends Controller
{

  public function painel()
  { 
    $this->view->setPageTitle('Páinel');
    }
}
