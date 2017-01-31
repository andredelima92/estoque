<?php

class HomesController extends AppController
{
    public $name = 'Homes';
  
    public function index()
    {
        $this->isLogin();
        $this->init();
    
        $this->template = $this->twigConfig($this->diretorio, $this->arquivo); 
        echo $this->template->render(array('this' => new View($this), 'parametros' => $this->parametros));
        exit;
    }
  
    public function lessLogin()
    {
        $this->init();
        $this->template = $this->twigConfig($this->diretorio, $this->arquivo); 
        echo $this->template->render(array('this' => new View($this), 'parametros' => $this->parametros));
        exit;
    }
}