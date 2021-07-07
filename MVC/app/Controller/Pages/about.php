<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;

class About extends Page{

	/**
	 *Metodo responsavel por retornar o conteudo da nossa Sobre
	 *@return string 
	 */
	public static function getAbout(){

		$obOrganization = new Organization;



		//View Home
		$content = View::render('pages/about',[
		'name'=> $obOrganization->name,
		'description'=>$obOrganization->description,
		'site'=> $obOrganization->site
		]);

		//Retorna a view da pagina
		return parent::getPage('SOBRE - AlixDEV',$content);
	}

}
