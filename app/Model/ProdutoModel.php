<?php

class ProdutoModel extends AppModel{
  public $name = 'Produto';
  public $hasMany = array('Estoque');
  
  public $validate = array(
    'nome' => array('rule' => 'notBlank')
  );
}