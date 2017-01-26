<?php
class ProdutosController extends AppController{
  public $name = 'Produtos';
  public $uses = array('Estoque','Produto','Usuario');
  
  public function add(){
    $this->init();
    if($this->request->is('post')){
      if($this->Produto->save($this->request->data)){
        $this->parametros = ['msg' => '<font color="green">Registro salvo com sucesso!</font>'];
      }
    }
    $this->template = $this->twigConfig($this->diretorio, $this->arquivo); 
    echo $this->template->render(array('this' => new View($this), 'parametros' => $this->parametros));
    exit;
  }
  
  public function edit($id = null){
    $this->init();
    $this->Produto->id = $id;
  
    if($this->request->is('get')){
      $this->parametros['produto'] = $this->Produto->findById($id)['Produto'];
      $options['joins'] = array(
        array('table' => 'usuarios',
            'alias' => 'Usuario',
            'type' => 'LEFT',
            'conditions' => array(
                'Usuario.id = Estoque.usuario_id',
            ),
        )
      );
      $options['conditions'] = array('Estoque.produto_id = '. $id );
      $options['fields'] = array('Usuario.nome', 'quantidade','created');
      
      $this->parametros['estoques'] = $this->Estoque->find('all', $options); 
    }else{
      if($this->Produto->save($this->request->data)){
        $this->parametros['msg'] = '<font color="green">Registro editado com sucesso!</font>';
        $this->arquivo = 'index.twig';
        $this->index();
      }
    }
    
    $this->template = $this->twigConfig($this->diretorio, $this->arquivo); 
    echo $this->template->render(array('this' => new View($this), 'parametros' => $this->parametros));
    exit;
  }
  
  public function delete($id){
    $this->init();
    if(!$this->request->is('post'))
      throw new MethodNotAllowedException();
  
    if($this->Produto->delete($id)){
      $this->parametros['msg'] = '<font color="red">Registro excluido com sucesso!</font>';
      $this->arquivo = 'index.twig';
      $this->index();
    }else{
      $this->parametros['msg'] = '<font color="red">Ocorreu um erro ao tentar excluir o registro!</font>';
      $this->arquivo = 'index.twig';
      $this->index();
    }
  }
  
  public function index(){
    if(empty($this->arquivo) || empty($this->diretorio))
      $this->init();
    
    $nome = isset($_GET['nome']) ? $_GET['nome'] : false;
    if($nome){
      $this->parametros['produtos'] =  $this->Produto->find('all', array('conditions' => array('Produto.nome LIKE' => '%'.$nome.'%')));
    }else
      $this->parametros['produtos'] =  $this->Produto->find('all');
    
    $this->template = $this->twigConfig($this->diretorio, $this->arquivo); 
    echo $this->template->render(array('this' => new View($this), 'parametros' => $this->parametros));
    exit;
    
  }
  
}