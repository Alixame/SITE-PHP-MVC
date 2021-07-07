<?php

namespace App\Model\Entity;

use \WilliamCosta\DatabaseManager\Database;

class User{

    /**
     * ID usuario
     * @var integer
     * 
     */
    public $id;

     /**
     * Nome usuario
     * @var string
     * 
     */
    public $nome;

     /**
     * Email usuario
     * @var string
     * 
     */
    public $email;
     
    /**
     * Senha usuario
     * @var string
     * 
     */
    public $senha;

    /**
     * METODO RESPONSAVEL POR CADASTRAR UM USUARIO NO BANCO
     * @return boolean
     */
    public function cadastrar(){
        $this->id = (new Database('usuarios'))->insert([
            'nome' => $this->nome,
            'email' => $this->email,
            'senha' => $this->senha,
        ]);
        return true;
    }

    /**
     * METODO RESPONSAVEL POR ATUALIZAR UM USUARIO NO BANCO
     * @return boolean
     */
    public function atualizar(){
        $this->id = (new Database('usuarios'))->update('id = '.$this->id,[
            'nome' => $this->nome,
            'email' => $this->email,
            'senha' => $this->senha,
        ]);
    }

     /**
     * METODO RESPONSAVEL POR EXCLUIR UM USUARIO NO BANCO
     * @return boolean
     */
    public function excluir(){
        $this->id = (new Database('usuarios'))->delete('id = '.$this->id);
    }

    /**
     * METODO RESPONSAVEL POR RETORNAR UMA ISNTANCIA COM BASE NO ID
     * @param integer @id
     * @return User
     */
    public static function getUserById($id){
        return  self::getUsers('id = '.$id)->fetchObject(self::class);
    }

     /**
     * METODO RESPONSAVEL POR RETORNAR UM USUARIO COM BASE NO SEU EMAIL
     * @param string @email
     * @return User
     */
    public static function getUserByEmail($email){
        return  self::getUsers('email = '.$email)->fetchObject(self::class);
    }

    /**
     * Metodo responsavel por retornar os usuarios
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return PDOSatement
     */ 
    public static function getUsers($where = null, $order = null ,$limit = null ,$fields ='*' ){

        return (new Database('usuarios'))->select($where,$order,$limit,$fields);

    }





}