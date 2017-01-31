<?php

class EstoquesController extends AppController
{
    public $name = 'Estoques';
    public $uses = array('Produto','Estoque');
  
    public function index()
    {
        $this->isLogin();
        $this->init();

        if ($this->request->is('post')) {
            $this->parametros['msg'] = '';
            $dados = [];
            $posicao = 0;
            $i = 0;
            $aux = 0;

            foreach ($this->request->data as $value) {
                if ($i % 3 == 0 && $i <> 0)
                    $posicao++;
                
                if ($aux == 0) {
                    $dados[$posicao]['usuario_id'] = $value;
                    $aux++;
                } elseif ($aux == 1) {
                    $dados[$posicao]['produto_id'] = $value;
                    $aux++;
                } elseif ($aux >= 2) {
                    $aux = 0;
                    $dados[$posicao]['quantidade'] = $value;
                    if ($dados[$posicao]['quantidade'] > 0) {
                        $this->parametros['msg'] .= "<span style='color:green'>Produto {$dados[$posicao]['produto_id']}: + {$dados[$posicao]['quantidade']} </span><br>";
                    } elseif ($dados[$posicao]['quantidade'] < 0) {
                        $this->parametros['msg'] .= "<span style='color:red'>Produto {$dados[$posicao]['produto_id']}: {$dados[$posicao]['quantidade']} </span><br>";
                    }
                }

                $i++;
            }
          
            foreach ($dados as $value) {
                if ($value['quantidade'] <> 0) {
                    $this->request->data = $value;
                    if ($this->Estoque->save($this->request->data)) {
                        $oldQtde = $this->Produto->findById($value['produto_id'])['Produto']['quantidade'];
                        $qtde['quantidade'] = $value['quantidade'] + $oldQtde;
                        $this->parametros['msg'] .= "<span style='color:blue'>Produto {$value['produto_id']} alterado o estoque de {$oldQtde} para " . $qtde['quantidade'] . "</span><br>";
                        $this->Produto->id = $value['produto_id'];
                        $this->Produto->save($qtde);
                        $this->Estoque->clear();        
                    }
                }
            }
        }

        $this->parametros['produtos'] = $this->Produto->find('all');
        $this->parametros['usuario']  = $_SESSION['usuario'];

        $this->template = $this->twigConfig($this->diretorio, $this->arquivo);
        echo $this->template->render(array('this' => new View($this), 'parametros' => $this->parametros));
        exit;
    }
}
