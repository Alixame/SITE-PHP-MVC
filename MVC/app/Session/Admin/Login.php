<?php

namespace App\Session\Admin;

class Login{


    /**
     *  METODO RESPONSAVEL POR INICIAR A SESSAO
     */
    private static function init(){
        //Verifica se a sessao não esta ativa
        if(session_status() != PHP_SESSION_ACTIVE){
            session_status();
        }
    }

    /**
     *  METODO RESPONSAVEL POR CRIAR O LOGIN DO USUARIO
     *  @param User $obUser
     *  @return boolean
     */
    public static function login($obUser){
        //Inicia a Sessao
        self::init();

        //Define a sessao do usuario
        $_SESSION['admin']['usuario'] = [
            'id'    => $obUser->id,
            'nome'  => $obUser->nome,
            'email' => $obUser->email
        ];

        //Sucesso
        return true;
    }


     /**
     *  METODO RESPONSAVEL POR VERIFICAR SE O USUARIO ESTÁ LOGADO
     *  @return boolean
     */
    public static function isLogged(){
        //Inicia a Sessao
        self::init();

        //Retorna a verificação
        return isset($_SESSION['admin']['usuario']['id']);

    }

    /**
     *  METODO RESPONSAVEL POR EXECUTAR O LOGOUT DO USUARIO
     *  @return boolean
     */
    public static function logout(){
        //Inicia a Sessao
        self::init();

        //Desloga o usuario
        unset($_SESSION['admin']['usuario']);
        
        //Sucesso
        return true;
    }

}