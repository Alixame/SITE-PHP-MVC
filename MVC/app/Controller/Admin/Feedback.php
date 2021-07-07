<?php

namespace App\Controller\Admin;

use \App\Utils\View;
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
		$obPagination = new Pagination($quantiadeTotal,$paginaAtual,5);

		//Resultados da Pagina
		$results = EntityFeedback::getFeedbacks(null,'id DESC',$obPagination->getLimit());

		//RENDERiZA OS ITEM
		while($obFeedback = $results->fetchObject(EntityFeedback::class)) {
			
			$itens .= View::render('admin/modules/feedback/item',[
				'id'=> $obFeedback->id,
                'nome'=> $obFeedback->nome,
				'mensagem'=> $obFeedback->mensagem,
				'data'=> date('d/m/Y H:i:s',strtotime($obFeedback->data))
			]);
		}

		return $itens;
	}

    /**
     * METODO RESPONSAVEL POR RENDERIZAR A VIEW DE LISTAGEM
     * @param Request $request
     * @return string
     * 
     */
    public static function getFeedback($request){
        //CONTEUDO DA HOME
        $content=  View::render('admin/modules/feedback/index',[
            'itens' => self::getFeedbackItens($request,$obPagination),
            'pagination' => parent::getPagination($request,$obPagination),
			'status' => self::getStatus($request)
        ]);

        //RETORNA A PAGINA COMPLETA 
        return parent::getPanel(' Comentarios - AliDEV',$content,'feedback');


    }
    
	/**
     * METODO RESPONSAVEL POR RETORNAR O FORMULARIO DE CADASTRO DE UM NOVO FEEDBACK
     * @param Request $request
     * @return string
     * 
     */
	public static function getNewFeedback($request){
        //CONTEUDO DA FORMULARIO
        $content=  View::render('admin/modules/feedback/form',[
			'title' => 'Cadastrar Feedback',
			'nome' => '',
			'mensagem' => '',
			'status' => ''
        ]);

        //RETORNA A PAGINA COMPLETA 
        return parent::getPanel('Cadastrar Comentario - AliDEV',$content,'feedback');


    }
	
	/**
     * METODO RESPONSAVEL POR CADASTRAR UM NOVO FEEDBACK
     * @param Request $request
     * @return string
     * 
     */
	public static function setNewFeedback($request){
       	//Post vars
	   	$postVars = $request->getPostVars();

		//NOVA INSTANCIA DE FEEDBACK
		$obFeedback = new EntityFeedback;
		$obFeedback->nome = $postVars['nome'] ?? '';
		$obFeedback->mensagem = $postVars['mensagem'] ?? '';
		$obFeedback->cadastrar();

		//REDIRECIONA O USUARIO
		$request->getRouter()->redirect('/admin/feedback/'.$obFeedback->id.'/edit?status=created');

	}

	/**
     * METODO RESPONSAVEL RETORNAR A MENSAGEM DE STATUS
     * @param Request $request
     * @return string
     * 
     */
	private static function getStatus($request){
		//QUERY PARAMS
		$queryParams= $request->getQueryParams();

		//Status
		if(!isset($queryParams['status'])) return '';

		//Mesangem de status
		switch ($queryParams['status']){
			case 'created':
				return Alert::getSuccess('Feedback criado com sucesso!');
			break;
			case 'updated':
				return Alert::getSuccess('Feedback atualizado com sucesso!');
			break;
			case 'deleted':
				return Alert::getSuccess('Feedback excluido com sucesso!');
			break;


		}
	}


	
	/**
     * METODO RESPONSAVEL RETORNAR O FORMULARIO DE EDIÇÃO
     * @param Request $request
	 * @param integer $id
     * @return string
     * 
     */
	public static function getEditFeedback($request,$id){
		// Obtem o feedback do banco de dados pelo id
		$obFeedback = EntityFeedback::getFeedbackById($id);

		//VALIDANDO A INSTANCIA
		if(!$obFeedback instanceof EntityFeedback){
			$request->getRouter()->redireect('/admin/feedback');
		}


		//CONTEUDO DA FORMULARIO
		$content=  View::render('admin/modules/feedback/form',[
			'title' => 'Editar Feedback',
			'nome' => $obFeedback->nome,
			'mensagem' => $obFeedback->mensagem,
			'status' => self::getStatus($request)
		]);

		//RETORNA A PAGINA COMPLETA 
		return parent::getPanel('Editar Comentario - AliDEV',$content,'feedback');

 	}

 	/**
     * METODO RESPONSAVEL POR GRAVAR A ATUALIZAÇÃO DE UM FEEDBACK
     * @param Request $request
	 * @param integer $id
     * @return string
     * 
     */
	public static function setEditFeedback($request,$id){
		// Obtem o feedback do banco de dados pelo id
		$obFeedback = EntityFeedback::getFeedbackById($id);

		//VALIDANDO A INSTANCIA
		if(!$obFeedback instanceof EntityFeedback){
			$request->getRouter()->redireect('/admin/feedback');
		}

		//POST VARS
		$postVars = $request->getPostVars();

		//Atualiza a INSTANCIA
		$obFeedback->nome = $postVars['nome'] ?? $obFeedback->nome;
		$obFeedback->mensagem = $postVars['mensagem'] ?? $obFeedback->nome;
		$obFeedback->atualizar();

		

		//REDIRECIONA O USUARIO
		$request->getRouter()->redirect('/admin/feedback/'.$obFeedback->id.'/edit?status=updated');
 	}
		
		/**
     * METODO RESPONSAVEL RETORNAR O FORMULARIO DE EXCLUSÃO
     * @param Request $request
	 * @param integer $id
     * @return string
     * 
     */
	public static function getDeleteFeedback($request,$id){
		// Obtem o feedback do banco de dados pelo id
		$obFeedback = EntityFeedback::getFeedbackById($id);

		//VALIDANDO A INSTANCIA
		if(!$obFeedback instanceof EntityFeedback){
			$request->getRouter()->redireect('/admin/feedback');
		}


		//CONTEUDO DA FORMULARIO
		$content=  View::render('admin/modules/feedback/delete',[
			
			'nome' => $obFeedback->nome,
			'mensagem' => $obFeedback->mensagem,
			
		]);

		//RETORNA A PAGINA COMPLETA 
		return parent::getPanel('Excluir Comentario - AliDEV',$content,'feedback');

 	}


	/**
    * METODO RESPONSAVEL POR EXCLUIR UM FEEDBACK
    * @param Request $request
	* @param integer $id
    * @return string
    * 
    */
	public static function setDeleteFeedback($request,$id){
		// Obtem o feedback do banco de dados pelo id
		$obFeedback = EntityFeedback::getFeedbackById($id);

		//VALIDANDO A INSTANCIA
		if(!$obFeedback instanceof EntityFeedback){
			$request->getRouter()->redireect('/admin/feedback');
		}

		//Exclui o deedback
		$obFeedback->excluir();

		

		//REDIRECIONA O USUARIO
		$request->getRouter()->redirect('/admin/feedback?status=deleted');
 	}
}