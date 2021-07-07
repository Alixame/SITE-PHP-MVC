<?php

namespace App\Controller\Pages;

use \App\Utils\View;

class Page{

	/**
	 *Metodo responsavel por retornar o topo da pagina
	 *@return string 
	 */
	private static function getHeader(){
		return View::render('pages/header');
	}

	/**
	 *Metodo responsavel por retornar o rodape da pagina
	 *@return string 
	 */
	private static function getFooter(){
		return View::render('pages/footer');
	}

	/**
	 *Metodo responsavel por renderizar o layout da paginação
	 *@return string 
	 */
	public static function getPagination($request,$obPagination){
		//Paginas
		$pages = $obPagination->getPages();

		//Verifica a quantidade de paginas
		if(count($pages) <= 1) return '';

		//Links
		$links ='';

		//URL atual (sem GETS)
		$url = $request->getRouter()->getCurrentUrl();

		//GET
		$queryParams = $request->getQueryParams();

		//Renderiza os links
		foreach($pages as $page){
			//Altera pagina
			$queryParams['page'] = $page['page'];

			//Link
			$link = $url.'?'.http_build_query($queryParams);

			//View
			$links .=  View::render('pages/pagination/link',[
				'page' => $page['page'],
				'link' => $link,
				'active' => $page['current'] ? 'active' : ' '
				]);
		}

		//Renderiza Box de Paginacao
		return View::render('pages/pagination/box',[
			'links' => $links
			]);

	}



	/**
	 *Metodo responsavel por retornar o conteudo da nossa pagina generica
	 *@return string 
	 */
	public static function getPage($title,$content){
		return View::render('pages/page',[
		'title'=>$title,
		'header'=>self::getHeader(),
		'content'=>$content,
		'footer'=>self::getFooter(),
		]);
		
	}

}
