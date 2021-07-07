<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;

class Home extends Page{

	/**
	 *Metodo responsavel por retornar o conteudo da nossa home
	 *@return string 
	 */
	public static function getHome(){

		$obOrganization = new Organization;

		//View Home
		$content = View::render('pages/home',[
		'name'=> $obOrganization->name,
		]);

		//Retorna a view da pagina
		return parent::getPage('HOME - AliDEV',$content);
	}

}
