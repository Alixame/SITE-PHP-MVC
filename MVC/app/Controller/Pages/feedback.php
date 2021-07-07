<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;
use \App\Model\Entity\Feedback as EntityFeedback;
use \WilliamCosta\DatabaseManager\Pagination;

class Feedback extends Page{

	/**
	 *Metodo responsavel por obter a renderização dos itens de feedback
	 *@return string 
	 */
	private static function getFeedbackItens($request,&$obPagination){
		$itens ='';

		//Quantidade total de registros
		$quantiadeTotal = EntityFeedback::getFeedbacks(null,null,null,'COUNT(*) as qtd')->fetchObject()->qtd;

		//Pagina Atual
		$queryParams = $request->getQueryParams();
		$paginaAtual= $queryParams['page'] ?? 1;

		//INSTANCIA DA PAGINAÇÃO
		$obPagination = new Pagination($quantiadeTotal,$paginaAtual,3);

		//Resultados da Pagina
		$results = EntityFeedback::getFeedbacks(null,'id DESC',$obPagination->getLimit());

		//RENDERiZA OS ITEM
		while($obFeedback = $results->fetchObject(EntityFeedback::class)) {
			
			$itens .= View::render('pages/feedback/item',[
				'nome'=> $obFeedback->nome,
				'mensagem'=> $obFeedback->mensagem,
				'data'=> date('d/m/Y H:i:s',strtotime($obFeedback->data))
			]);
		}

		return $itens;
	}




	/**
	 *Metodo responsavel por retornar o conteudo da pagina de comentarios
	 *@param Request $request
	 *@return string 
	 */
	public static function getFeedback($request){


		//View de Comentarios
		$content = View::render('pages/feedback',[
			'itens' => self::getFeedbackItens($request,$obPagination),
			'pagination' => parent::getPagination($request,$obPagination)

		]);


		//Retorna a view da pagina
		return parent::getPage('COMENTARIOS - AliDEV',$content);
	}

	/**
     * Metodo responsavel pro cadastar um Feedback
     * @param Request $request
	 * @return string
     */
	public static function insertFeedback($request){
		//Dados do post
		$postVars = $request->getPostVars();

		//Nova INSTANCIA DE DEPOIMENTO
		$obFeedback = new EntityFeedback;
		$obFeedback->nome = $postVars['nome'];
		$obFeedback->mensagem = $postVars['mensagem'];
		$obFeedback->cadastrar();

		return self::getFeedback($request);
	}



}
