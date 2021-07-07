<?php

namespace App\Http\Middleware;

class Maintenance{

    /**
     * Metodo responsavel por executar o middleware
     * @param Request $request
     * @param Closure $next
     * 
     */
    public function  handle($request, $next){
        //Verifica o estado de manutenção da pagina
        if(getenv('MAINTENANCE')== 'true'){
            throw new \Exception("Pagina em Manutenção , tente novamente mais tarde.", 200);
            
        }

        //Executa o proximo nivel do middleware
        return $next($request);

    }





}








?>