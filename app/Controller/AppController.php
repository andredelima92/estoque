<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
  public $helpers = array('Html', 'Form');
  public $components = array('Flash', 'DebugKit.Toolbar');
  public $parametros = [];
  public $template;
  public $arquivo;
  public $diretorio;
  
  public function init(){
    if(!isset($_SESSION)) {
        session_start();
    }
    if(isset($_SESSION['usuario'])){
      $this->parametros['isAdmin'] = $_SESSION['usuario']['role'] === 'admin' ? $_SESSION['usuario']['role'] : false;
    }
    
    $this->arquivo = ($this->params->action).'.twig';
    $this->diretorio = '../View/'.(ucfirst(strtolower($this->params->controller)));
  }
  
  public function isLogin(){
    if(empty($_SESSION['usuario'])){
      $this->redirect(array('controller' => 'usuarios' , 'action' => 'index'));
      exit;
    }
  }
  
  public function isAdmin(){
    $this->isLogin();
    if($_SESSION['usuario']['role'] === 'admin')
      return true;
    else{
      $this->arquivo = 'lessLogin.twig';
      $this->diretorio = '../View/Homes/';
      return false;
    }
  }

  protected function twigConfig($dir, $file){ 
    $loader = new Twig_Loader_Filesystem(__DIR__. '/'. $dir);
    $twig = new Twig_Environment($loader, array(
        'cache' => false,
    ));
    
      return $twig->load($file);
    }
  
}
