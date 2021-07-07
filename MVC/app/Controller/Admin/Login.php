<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\User;
use \App\Session\Admin\Login as SessionAdminLogin;

class Login extends Page{


    /**
     * Metodo responsavel por retornar a renderização da pagina de login
     * @param Request $request
     * @param string $errorMessage
     * @return string
     */
    public static function getLogin($request,$errorMessage = null){
        //Status
        $status = !is_null($errorMessage) ? Alert::getError($errorMessage) : '';

        //Conteudo da Pagina de Login
        $content = View::render('admin/login',[
            'status' => $status
        ]);


        //Retorna a pagina completa
        return parent::getPage('Login > AliDEV',$content);
    }

    /**
     * Metodo responsavel por definir o login do usuario
     * @param Request $request
     * @return string
     */
    public static function setLogin($request){

        //Post VARS
        $postVars = $request->getPostVars();
        $email = $postVars['email'] ?? '';
        $senha = $postVars['senha'] ?? '';

       

        //Buscar o usuario pelo email       
        $obUser = User::getUserByEmail($email);
        if(!$obUser instanceof User){
            return self::getLogin($request,'E-mail ou senha invalidos!');
        }


        //VERIFICA SENHA DO USUARIO
        if(!password_verify($senha,$obUser->senha)){
            return self::getLogin($request,'E-mail ou senhas invalidos!');
        }


        //Criar a Sessao de Login
        SessionAdminLogin::login($obUser);

        //Redireciona o usuario para a home admin
        $request->getRouter()->redirect('/admin');
    }

    /**
     * Metodo responsavel por deslogar o usuario
     * 
     * 
     */
    public static function setLogout($request){
       
        //Destroi a Sessao de Login
        SessionAdminLogin::logout();

        //Redireciona o usuario para a a tela de login
        $request->getRouter()->redirect('/admin/login');

    }


}