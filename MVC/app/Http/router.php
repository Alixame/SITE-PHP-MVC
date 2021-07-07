<?php

namespace App\Http;

use \Closure;
use \Exception;
use \ReflectionFunction;
use \App\Http\Middleware\Queue as MiddlewareQueue;

class Router{

	/**
	 * URL completa do projeto (raiz)
	 * @var integer
	 */
	private $url = '';

	/**
	 * Prefixo de todas as rotas
	 * @var string
	 */
	private $prefix = '';

	/**
	 * Indice de rotas
	 * @var array
	 */
	private $routes = [];

	/**
	 * Instancia de Request
	 * @var Request
	 */
	private $request;

	/**
	 * CONTENT TYPE PADRÃO DO RESPOSE
	 * @var string
	 */
	private $contentType = 'text/html';

	/**
	 * Metodo responsavel por iniciar a classe
	 * @param string $url
	 */
	public function __construct($url){

		$this->request = new  Request($this);
		$this->url = $url;
		$this->setPrefix();
		
	}

	/**
	 * Metodo responsavel por alterar o valor da content type
	 * @param string $contentType
	 */
	public function setContentType($contentType){
		$this->contentType = $contentType;

	}

	/**
	 * Metodo responsavel por definior o prefixo das rotas 
	 */
	private function setPrefix(){
		//Informações da URL ATUAL
		$parseUrl = parse_url($this->url);

		//Define o prefixo
		$this->prefix = $parseUrl['path'] ?? '';
	}
	
	/**
	 * Metodo responsavel por adicionar uma rota na classe
	 * @param string $method
	 * @param string $route
	 * @param array $params
	 */
	public function addRoute($method,$route,$params = []){
		//Validação dos Parametros
		foreach ($params as $key => $value) {
			if($value instanceof Closure){
				$params['controller'] = $value;
				unset($params[$key]);
				continue;
			} 
		}

		// Middleware da rota
		$params['middlewares'] = $params['middlewares'] ?? [] ;

		//VARIAVEIS DA ROTA
		$params['variables'] = [];

		//PADRAO DE VALIDAÇÃO DAS VARIAVEIS DAS ROTAS
		$patternVariable = '/{(.*?)}/';
		if(preg_match_all($patternVariable,$route,$matches)){

			$route = preg_replace($patternVariable,'(.*?)',$route);
			$params['variables'] = $matches[1];
		}



		// Padrao de validação da URL
		$patternRoute = '/^'.str_replace('/', '\/', $route).'$/';

		//Adiciona a rota dentro da classe
		$this->routes[$patternRoute][$method] = $params;
	}

	/**
	 * Metodo responsavel por definior uma rota de GET
	 * @param string $route
	 * @param array $params
	 */
	public function get($route,$params = []){

		return $this->addRoute('GET',$route,$params);


	}

	/**
	 * Metodo responsavel por definior uma rota de POST
	 * @param string $route
	 * @param array $params
	 */
	public function post($route,$params = []){

		return $this->addRoute('POST',$route,$params);


	}

	/**
	 * Metodo responsavel por definior uma rota de PUT
	 * @param string $route
	 * @param array $params
	 */
	public function put($route,$params = []){

		return $this->addRoute('PUT',$route,$params);


	}

	/**
	 * Metodo responsavel por definior uma rota de DELETE
	 * @param string $route
	 * @param array $params
	 */
	public function delete($route,$params = []){

		return $this->addRoute('DELETE',$route,$params);


	}

	/**
	 * Metodo responsavel por retornar a URI desconsiderando o prefixo
	 * @return string
	 */
	private function getUri(){
		//URI Da REQUEST
		$uri = $this->request->getUri();

		//URI Fatiada
		$xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];

		// retorna a uri sem prefixo
		return rtrim(end($xUri), '/');
	}

	/**
	 * Metodo responsavel por retornar os dados da rota atual
	 * @return array
	 */
	private function getRoute(){
		//URI
		$uri = $this->getUri();

		//Method
		$httpMethod = $this->request->getHttpMethod();

		//valida as rotas
		foreach($this->routes as $patternRoute=>$methods){

			//verifica se a URI bate com o padrao
			if(preg_match($patternRoute, $uri,$matches)){
				if (isset($methods[$httpMethod])) {
					//REMOVENDO A PRIMEIRA POSIÇÃO
					unset($matches[0]);

					//VARIAVEIS Processadas
					$keys = $methods[$httpMethod]['variables'];
					$methods[$httpMethod]['variables'] = array_combine($keys,$matches);
					$methods[$httpMethod]['variables']['request'] = $this->request;	
					
					//Retorno dos Parametros da Rota
					return $methods[$httpMethod];
				}

				throw new Exception("Metodo não permitido", 405);
			}
		}
		//URL Não encontrada
		throw new Exception("URL não econtrada", 404);
	}



	/**
	 * Metodo responsavel por executar a rota atual
	 * @return Response
	 */
	public function run(){
		try {
			
			//Obtem rota atual
			$route = $this->getRoute();
			
			//Verifica o controller
			if(!isset($route['controller'])){
				throw new Exception("A URL não pôde ser processada", 500);
			}

			//Argumentos da Função
			$args = [];

			//Reflection
			$reflection = new ReflectionFunction($route['controller']);
			foreach($reflection->getParameters() as $parameter){
				$name = $parameter ->getName();
				$args[$name] = $route['variables'][$name] ?? ''; 

			}

			//Retorna a execução da fila de middlewares
			return (new MiddlewareQueue($route['middlewares'],$route['controller'],$args))->next($this->request);

		} catch (Exception $e) {
			return new Response($e->getCode(),$this->getErrorMessage($e->getMessage()),$this->contentType);
		}

	}


	private function getErrorMessage($message){
		switch ($this->contentType) {
			case 'application/json':
				return [
					'error' => $message
				];
				break;
			
			default:
				return $message;
				break;
		}
	}

	public function getCurrentUrl(){
		return $this->url.$this->getUri();
	}

	public function redirect($route){
		$url = $this->url.$route;

		//Executa o redirect
		header('location:'.$url);
		exit;
	}
}
