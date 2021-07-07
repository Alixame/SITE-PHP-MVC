<?php

namespace App\Controller\Api;

use \App\Model\Entity\Feedback as EntityFeedback;
use \WilliamCosta\DatabaseManager\Pagination;

class Feedback extends Api{

     /**
	 *Metodo responsavel por obter a renderização dos itens de feedback
	 *@return string 
	 */
	private static function getFeedbackItens($request,&$obPagination){
		$itens = [];

		//Quantidade total de registros
		$quantiadeTotal = EntityFeedback::getFeedbacks(null,null,null,'COUNT(*) as qtd')->fetchObject()->qtd;

		//Pagina Atual
		$queryParams = $request->getQueryParams();
		$paginaAtual= $queryParams['page'] ?? 1;

		//INSTANCIA DA PAGINAÇÃO
		$obPagination = new Pagination($quantiadeTotal,$paginaAtual,5);

		//Resultados da Pagina
		$results = EntityFeedback::getFeedbacks(null,'id DESC',$obPagination->getLimit());

		//RENDERiZA OS ITEM
		while($obFeedback = $results->fetchObject(EntityFeedback::class)) {
			
			$itens[] = [
				'id'=> (int)$obFeedback->id,
                'nome'=> $obFeedback->nome,
				'mensagem'=> $obFeedback->mensagem,
				'data'=> $obFeedback->data
			];
		}

		return $itens;
	}

    /**
     * METEODO RESPONSAVEL POR RETORNAR OS COMENTARIOS CADASTRADOS
     * @param Request $request
     * @return array
     */
    public static function getFeedback($request){
        return [
            'comentarios' => self::getFeedbackItens($request,$obPagination),
            'paginacao' => parent::getPagination($request,$obPagination)
        ];
    }

    /**
     * METEODO RESPONSAVEL POR RETORNAR OS DETALHES DE UM FEEDBACK
     * @param Request $request
     * @param integer $id
     * @return array
     */
    public static function getFeedbacks($request,$id){
        
        // VALIDA O ID DO FEEDBACK
        if(!is_numeric($id)){
            throw new \Exception("O ID não é valido", 404);  
        }

        //BUSCA FEEDBACK
        $obFeedback = EntityFeedback::getFeedbackById($id);

        //VALIDA SE O FEEDBACK EXISTE
        if(!$obFeedback instanceof EntityFeedback){
            throw new \Exception("O Feedback ".$id." não foi encontrado", 404);
        }

        return [
            'id'=> (int)$obFeedback->id,
            'nome'=> $obFeedback->nome,
			'mensagem'=> $obFeedback->mensagem,
			'data'=> $obFeedback->data
        ];

    }


}