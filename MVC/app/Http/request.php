<?php

namespace App\Http;

class Request{

		/**
	 * Router
	 * @var string
	 */
	private $router;


	/**
	 * Metodo HTTP da requisição
	 * @var string
	 */
	private $httpMethod;

	/**
	 * URI da pagina
	 * @var string
	 */
	private $uri;

	/**
	 * Parametros da URL($_GET)
	 * @var array
	 */
	private $queryParams = [];

	/**
	 * Variaveis recebidas no POST da pagina($_POST)
	 * @var array
	 */
	private $postVars = [];

	/**
	 * Cabeçalho da requisição
	 * @var array
	 */
	private $headers = [];


	/**
	 * Construtor da Classe
	 */
	public function __construct($router){
		$this->router 	   = $router;
		$this->queryParams = $_GET ?? [];
		$this->postVars    = $_POST ?? [];
		$this->headers     = getallheaders();
		$this->httpMethod  = $_SERVER['REQUEST_METHOD'] ?? '';
		$this->setUri();
	}

	/**
	 * Metodo responsavel por definia a URI
	 */
	public function setUri(){
		//URI COMPELTA (COM GETS)
		 $this->uri = $_SERVER['REQUEST_URI'] ?? '';
	
		//Remove Gets da URI
		$xURI = explode('?',$this->uri);
		$this->uri = $xURI[0];
	}


	/**
	 * Metodo responsavel por retornar a instancia de Router
	 * @return Router
	 */
	public function getRouter(){
		return $this->router;
	}

	/**
	 * Metodo responsavel por retornar o metodo HTTP da requisição
	 * @return string
	 */
	public function getHttpMethod(){
		return $this->httpMethod;
	}

	/**
	 * Metodo responsavel por retornar a URI da requisição
	 * @return string
	 */
	public function getUri(){
		return $this->uri;
	}

	/**
	 * Metodo responsavel por retornar os Heardes da requisição
	 * @return string
	 */
	public function getHeaders(){
		return $this->headers;
	}

	/**
	 * Metodo responsavel por retornar os parametros da URL da requisição
	 * @return array
	 */
	public function getQueryParams(){
		return $this->queryParams;
	}

	/**
	 * Metodo responsavel por retornar as variaveis do Post da requisição
	 * @return array
	 */
	public function getPostVars(){
		return $this->postVars;
	}

}
