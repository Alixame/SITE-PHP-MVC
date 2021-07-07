<?php

namespace App\Http\Middleware;
use \App\Session\Admin\Login as SessionAdminLogin;


class RequireAdminLogin{

    /**
     * Metodo responsavel por executar o middleware
     * @param Request $request
     * @param Closure $next
     * 
     */
    public function  handle($request, $next){
        //Verifica se o usuario esta logado
        if(!SessionAdminLogin::isLogged()){
            $request->getRouter()->redirect('/admin/login');
        }
            //Continua a execução
           return $next($request);
    }







}