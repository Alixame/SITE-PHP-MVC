<?php

namespace App\Http\Middleware;

class Api{

    /**
     * Metodo responsavel por executar o middleware
     * @param Request $request
     * @param Closure $next
     * @return Response 
     */
    public function  handle($request, $next){
        // ALTERA O CONTENT TYPE PARA JSON
        $request->getRouter()->setContentType('application/json');

        //Executa o proximo nivel do middleware
        return $next($request);

    }





}








?>