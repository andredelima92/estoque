<?php

class UsuarioModel extends AppModel{
  public $name = 'Usuario';
  public $hasMany = array('Estoque');
  
    
  public $validate = array(
    'username' => array('rule' => 'notBlank'),
    'password'  => array('rule' => 'notBlank')
  );
  
}