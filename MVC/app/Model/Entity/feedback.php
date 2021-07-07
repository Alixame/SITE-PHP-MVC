<?php

namespace App\Model\Entity;

use \WilliamCosta\DatabaseManager\Database;

class Feedback{

    /**
     * Id do usuario
     * @var integer
     */
    public $id;

    /**
     * Nome do usuario
     * @var string
     */
    public $nome;

    /**
     * Mensagem do usuario
     * @var string
     */
    public $mensagem;

    /**
     * Data do feedback enviado
     * @var string
     */
    public $data;

    /**
     * Metodo responsavel por cadastar o feedback do usuario no banco
     * @return boolean
     */ 
    public function cadastrar(){

        //define a data
        $this->data = date('Y-m-d H:i:s');

        //Insere o feedback no banco de dados
        $this->id = (new Database('feedback'))->insert([
            'nome'     =>   $this->nome,
            'mensagem' =>   $this->mensagem,
            'data'     =>   $this->data

        ]);
        //Sucesso
        return true;
    }

    /**
     * Metodo responsavel por retornar os feedbacks do banco
     * @param integer $id
     * @return Feedback
     */
    public static function  getFeedbackById($id){
        return self::getFeedbacks('id ='.$id)->fetchObject(self::class);
    }




    /**
     * Metodo responsavel por retornar os feedbacks
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return PDOSatement
     */ 
    public static function getFeedbacks($where = null, $order = null ,$limit = null ,$fields ='*' ){

        return (new Database('feedback'))->select($where,$order,$limit,$fields);

    }

     /**
     * Metodo responsavel por atualizar o feedback do usuario no banco
     * @return boolean
     */ 
    public function atualizar(){

        //ATUALIZA O FEEDBACK NO BANCO
        return (new Database('feedback'))->update('id = '.$this->id,[
            'nome'     =>   $this->nome,
            'mensagem' =>   $this->mensagem
        ]);

    }

     /**
     * Metodo responsavel por excluir um feedback do usuario no banco
     * @return boolean
     */ 
    public function excluir(){
        //ATUALIZA O FEEDBACK NO BANCO
        return (new Database('feedback'))->delete('id = '.$this->id);

    }
}