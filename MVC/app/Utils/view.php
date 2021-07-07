<?php

namespace App\Utils;

class View{

	/**
	 *Variaveis padrao da view
	 *@param array $vars
	 */
	private static $vars = [];

	/**
	 *Metodo responsavel por definir os dados inicias da classe
	 *@param array $vars
	 */
	public static function init($vars = []){
			
		self::$vars = $vars;

	}


	/**
	 *Metodo responsavel por retornar o conteudo de uma view
	 *@param string $view
	 *@return string 
	 */
	private static function getContentView($view){
		$file = __DIR__.'/../../resources/views/'.$view.'.html';
		return file_exists($file) ? file_get_contents($file) : '';

	}



	/**
	 *Metodo responsavel por retornar o conteudo renderizado de uma view
	 *@param string $view
	 *@param array $vars (string/numeric)
	 *@return string 
	 */
	public static function render($view, $vars = []){
		//Conteudo da VIEW
		$contentView = self::getContentView($view);

		//Merge de variveis da View
		$vars = array_merge(self::$vars,$vars);

		//chave dos array de variaveis
		$keys = array_keys($vars);
		$keys = array_map(function($item){
			return '{{'.$item.'}}';
		},$keys);

		return str_replace($keys, array_values($vars), $contentView);

	}

}
