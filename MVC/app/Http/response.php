<?php

namespace App\Http;

class Response{

	/**
	 * Codigo do status HTTP
	 * @var integer
	 */
	private $httpCode = 200;

	/**
	 * Cabeçalho do Response
	 * @var array
	 */
	private $headers = [];

	/**
	 * Tipo de conteudo retornado
	 * @var string
	 */
	private $contentType = 'text/html';

	/**
	 * Conteudo do Response
	 * @var mixed
	 */
	private $content;



	/**
	 * Metodo responsavel por iniciar a classe e definir os valores
	 * @var integer $httpCode
	 * @var mixed $content
	 * @var string $contentType
	 */
	public function __construct($httpCode,$content,$contentType = 'text/html'){

		$this->httpCode = $httpCode;
		$this->content = $content;
		$this->setContentType($contentType);
		
	}


	/**
	 * Metodo responsavel por aletar o contentType do Response
	 * @param string
	 */
	public function setContentType($contentType){

		$this->contentType = $contentType;
		$this->addHeader('Content-Type',$contentType);

	}

	/**
	 * Metodo responsavel por adicionar um registro no cabeçalho do response
	 * @param string $key
	 * @param string $value
	 */
	public function addHeader($key,$value){
		
		$this->headers[$key] = $value;
	}

	/**
	 * Metodo responsavel por enviar ps headers para o navegador
	 */
	private function sendHeaders(){
		//Status
		http_response_code($this->httpCode);

		//Enviar Headers
		foreach ($this->headers as $key => $value){
			header($key.': '.$value);
		}

	}

	/**
	 * Metodo responsavel por enviar a resposta para o usuario
	 */
	public function sendResponse(){
		//Envia os headers
		$this->sendHeaders();

		//Imprime conteudo
		switch ($this->contentType) {
			case 'text/html':
				echo $this->content;
			exit;
			case 'application/json':
				echo json_encode($this->content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
			exit;
		}
	}
	
}
