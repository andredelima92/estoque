<?php
class UsuariosController extends AppController{
  public $name = 'Usuarios';
  
  public function index(){
    if(!empty($_SESSION['usuario'])){
      $this->redirect(array('controller' => 'homes' , 'action' => 'index'));
      exit;
    }
    
    if(!isset($this->arquivo) || !isset($this->diretorio))
      $this->init();
    
    $this->template = $this->twigConfig($this->diretorio, $this->arquivo); 
    echo $this->template->render(array('this' => new View($this), 'parametros' => $this->parametros));
    exit;
  }
<<<<<<< Updated upstream
  //Nova alteração
=======
  // teste de comentario para o git
>>>>>>> Stashed changes
  public function alterarSenha(){
    $this->isLogin();
    $this->init();
    $this->parametros['usuario'] = $_SESSION['usuario'];

    if ($_POST) {    
      if ($this->request->data['password'] !== $this->request->data['password_confirm']) {
        $this->parametros['msg'] = '<font color="red">Senhas não conferem!</font>';
      }elseif ($this->Usuario->save($this->request->data)) {
        $this->parametros['msg'] = '<font color="green">Senha alterada com sucesso!</font>';
        $this->arquivo = 'index.twig';
        $this->diretorio = '../View/Homes/';
      }
    }
    
    $this->template = $this->twigConfig($this->diretorio, $this->arquivo); 
    echo $this->template->render(array('this' => new View($this), 'parametros' => $this->parametros));
		exit;
  }
  
  public function add($id = null){
    if($this->isAdmin()){
      $this->init();
      if($_POST){ // Incluir novo usuario ou salvar edição
        
        if(!empty($this->Usuario->findByUsername($this->request->data['username'])) && empty($this->request->data['id'])){
          $this->parametros['msg'] = '<font color="red">E-mail ja cadastrado!</font>';
        }else{
          if($this->Usuario->save($this->request->data))
            $this->parametros['msg'] = '<font color="green">Operação executada com sucesso!</font>';
          else
            $this->parametros['msg'] = '<font color="red">Falha ao executar a operação!</font>';
        
          $this->parametros['usuarios'] = $this->Usuario->find('all');
          $this->arquivo = 'view.twig';  
        }
      }else{ //Visualizar ou editar
        if(!empty($id))
          $this->parametros['usuario'] = $this->Usuario->findById($id)['Usuario'];  
      }
    }
 
    $this->template = $this->twigConfig($this->diretorio, $this->arquivo);
    echo $this->template->render(array('this' => new View($this), 'parametros' => $this->parametros));
    exit;
  }
  
  public function delete($id){
    if($this->isAdmin()){
      $this->init();
      if(!$this->request->is('post'))
        throw new MethodNotAllowedException();
      
      if($this->Usuario->delete($id))
        $this->parametros['msg'] = '<font color="green">Registro excluido com sucesso!</font>';
      else
        $this->parametros['msg'] = '<font color="red">Falha ao excluir registro</font>';
        
      $this->arquivo = 'view.twig';
      $this->view();
    }else{
      $this->template = $this->twigConfig($this->diretorio, $this->arquivo);
      echo $this->template->render(array('this' => new View($this), 'parametros' => $this->parametros));
      exit;
    }
  }
  
  public function view(){
    if($this->isAdmin()){
      $busca 		    = !empty($_GET['busca']) ? $_GET['busca'] : '';
      $palavraBuscada = !empty($_GET['word']) ? $_GET['word'] : '';
      $perfil         = !empty($_GET['perfil']) ? $_GET['perfil'] : '';
      $ordenar        = !empty($_GET['ordenar']) ? $_GET['ordenar'] : '';
      $condicao = '';
      $operador = '';
      
      if($palavraBuscada){
        $condicao .= " AND Usuario.".$busca." LIKE  '%".$palavraBuscada."%' ";
        $operador = 'AND ';
      }if($perfil)
        $condicao .= $operador. "Usuario.role = '{$perfil}' ";
      if($ordenar)
        $condicao .= "ORDER BY {$ordenar} ";
      
      $this->parametros['usuarios'] = $this->Usuario->find('all', array('conditions' => array('1 = 1 '. $condicao)));  

      if(empty($this->arquivo) || empty($this->diretorio))
        $this->init();
    }
    
    $this->template = $this->twigConfig($this->diretorio, $this->arquivo);
    echo $this->template->render(array('this' => new View($this), 'parametros' => $this->parametros));
   exit;
  }
  
  public function login() {
    $this->init();
    $username = isset($_GET['username']) ? $_GET['username'] : false;
    $password = isset($_GET['password']) ? $_GET['password'] : false;
    if($username && $password){
      $usuario = $this->Usuario->findAllByUsernameAndPassword($username,$password);
      
      if(!empty($usuario)){
        $_SESSION['usuario'] = $usuario[0]['Usuario'];
        $this->redirect(array('controller' => 'homes' , 'action' => 'index'));
        exit;  
      }
      
      $this->parametros['msg'] = '<font color="red">Usuario ou senha invalido!</font>';  
    }else{
      $this->parametros['msg'] = '<font color="red">Ocorreu um erro inesperado!</font>';
    }
    
    $this->arquivo = 'index.twig';
    $this->index();
  }
  
  public function logout(){
    $this->init();
    $this->arquivo = 'index.twig';
    session_destroy();
    $this->index();
    exit;
  }
  
}