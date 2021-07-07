<?php

namespace App\Http\Middleware;

class Queue{

    /**
     * Mapeamento de middlewares
     * @var array 
     */
    private static $map = [];

    /**
     * Mapeamento de middlewares que serão carregados em todas as rotas
     * @var array 
     */
    private static $default = [];


    /**
     * Fila de middlewares a serem executados 
     * @var array 
     */
    private $middlewares = [];

    /**
     * Funcao de execução do controlador
     * @var Closure 
     */
    private $controller;

     /**
     * Argumentos da função do controlador
     * @var array 
     */
    private $controllerArgs = [];

    /**
     * Metodo responsavel por construir a classe de fila de middlewares
     * @param array $middlewares
     * @param Closure $controller
     * @param array $controllerArgs
     */
    public function __construct($middlewares,$controller,$controllerArgs){
        $this->middlewares = array_merge(self::$default,$middlewares);
        $this->controller = $controller;
        $this->controllerArgs = $controllerArgs;
    }

    /**
     * Metodo responsavel por definir o mapeamento de middlewares
     * @param arry $map
     */
    public static function setMap($map){
        self::$map = $map;
    }

    /**
     * Metodo responsavel por definir o mapeamento de middlewares padrões
     * @param arry $default
     */
    public static function setDefault($default){
        self::$default = $default;
    }

    /**
     * Metodo responsavel por executar o proximo nuvel da fila de middlewares
     * @param Request $request
     * @return Response
     */
    public function next($request){
        //Verifica se a fila esta vazia
        if(empty($this->middleware)) return call_user_func_array($this->controller,$this->controllerArgs);

        //Middleware
        $middlewares = array_shift($this->middleware);

        //Verifica o mapeamento
        if(!isset(self::$map[$middlewares])){
            throw new \Exception("Problemas ao processar o middleware da requisição", 500);
            
        }

        //Next
        $queue = $this;
        $next = function($request) use($queue){
            return $queue->next($request);
        };
    
        return (new self::$map[$middlewares])->handle($request,$next);
    }


}

?>