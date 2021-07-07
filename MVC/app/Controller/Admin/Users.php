<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\User as EntityUser;
use \WilliamCosta\DatabaseManager\Pagination;


class Users extends Page{

    /**
	 *Metodo responsavel por obter a renderização dos itens de usuarios
	 *@return string 
	 */
	private static function getUserItems($request,&$obPagination){
		$itens ='';

		//Quantidade total de registros
		$quantiadeTotal = EntityUser::getUsers(null,null,null,'COUNT(*) as qtd')->fetchObject()->qtd;

		//Pagina Atual
		$queryParams = $request->getQueryParams();
		$paginaAtual= $queryParams['page'] ?? 1;

		//INSTANCIA DA PAGINAÇÃO
		$obPagination = new Pagination($quantiadeTotal,$paginaAtual,5);

		//Resultados da Pagina
		$results = EntityUser::getUsers(null,'id DESC',$obPagination->getLimit());

		//RENDERiZA OS ITEM
		while($obUser = $results->fetchObject(EntityUser::class)) {
			
			$itens .= View::render('admin/modules/users/item',[
				'id'=> $obUser->id,
                'nome'=> $obUser->nome,
				'email'=> $obUser->email
			]);
		}

		return $itens;
	}

    /**
     * METODO RESPONSAVEL POR RENDERIZAR A VIEW DE LISTAGEM de usuarios
     * @param Request $request
     * @return string
     * 
     */
    public static function getUsers($request){
        //CONTEUDO DA PAGINA DE USUARIOS
        $content=  View::render('admin/modules/users/index',[
            'itens' => self::getUserItems($request,$obPagination),
            'pagination' => parent::getPagination($request,$obPagination),
			'status' => self::getStatus($request)
        ]);

        //RETORNA A PAGINA COMPLETA 
        return parent::getPanel('Usuarios - AliDEV',$content,'users');


    }
    
	/**
     * METODO RESPONSAVEL POR RETORNAR O FORMULARIO DE CADASTRO DE UM NOVO FEEDBACK
     * @param Request $request
     * @return string
     * 
     */
	public static function getNewUser($request){
        //CONTEUDO DA FORMULARIO
        $content=  View::render('admin/modules/users/form',[
			'title' => 'Cadastrar Usuario',
			'nome' => '',
			'email' => '',
			'status' => self::getStatus($request)
        ]);

        //RETORNA A PAGINA COMPLETA 
        return parent::getPanel('Cadastrar Usuarios - AliDEV',$content,'users');


    }
	
	/**
     * METODO RESPONSAVEL POR CADASTRAR UM NOVO Usuario
     * @param Request $request
     * @return string
     * 
     */
	public static function setNewUser($request){
       	//Post vars
	   	$postVars = $request->getPostVars();
		$nome = $postVars['nome'] ?? '';
		$email = $postVars['email'] ?? '';
		$senha = $postVars['senha'] ?? '';

		//Valida email
		$obUser = EntityUser::getUserByEmail($email);
		if($obUser instanceof EntityUser){

			$request->getRouter()->redirect('/admin/users/new?status=duplicated');
		}

		//NOVA INSTANCIA DE FEEDBACK
		$obUser = new EntityUser;
		$obUser->nome = $nome;
		$obUser->email = $email;
		$obUser->senha = password_hash($senha,PASSWORD_DEFAULT);
		$obUser->cadastrar();

		//REDIRECIONA O USUARIO
		$request->getRouter()->redirect('/admin/users/'.$obUser->id.'/edit?status=created');

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
				return Alert::getSuccess('Usuario criado com sucesso!');
			break;
			case 'updated':
				return Alert::getSuccess('Usuario atualizado com sucesso!');
			break;
			case 'deleted':
				return Alert::getSuccess('Usuario excluido com sucesso!');
			break;
			case 'duplicated':
				return Alert::getError('O email digitado já esta sendo usado');
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
	public static function getEditUser($request,$id){
		// Obtem o usuario do banco de dados pelo id
		$obUser = EntityUser::getUserById($id);

		//VALIDANDO A INSTANCIA
		if(!$obUser instanceof EntityUser){
			$request->getRouter()->redireect('/admin/users');
		}


		//CONTEUDO DA FORMULARIO
		$content=  View::render('admin/modules/users/form',[
			'title' => 'Editar Usuario',
			'nome' => $obUser->nome,
			'email' => $obUser->email,
			'status' => self::getStatus($request)
		]);

		//RETORNA A PAGINA COMPLETA 
		return parent::getPanel('Editar Usuario - AliDEV',$content,'users');

 	}

 	/**
     * METODO RESPONSAVEL POR GRAVAR A ATUALIZAÇÃO DE UM USUARIO
     * @param Request $request
	 * @param integer $id
     * @return string
     * 
     */
	public static function setEditUser($request,$id){
		// Obtem o usuario do banco de dados pelo id
		$obUser = EntityUser::getUserById($id);

		//VALIDANDO A INSTANCIA
		if(!$obUser instanceof EntityUser){
			$request->getRouter()->redireect('/admin/users');
		}

		//POST VARS
		$postVars = $request->getPostVars();
		$nome = $postVars['nome'] ?? '';
		$email = $postVars['email'] ?? '';
		$senha = $postVars['senha'] ?? '';

		//Valida email
		$obUserEmail = EntityUser::getUserByEmail($email);
		if($obUserEmail instanceof EntityUser && $obUser->id != $id ){

			$request->getRouter()->redirect('/admin/users/'.$id.'/edit?status=duplicated');
		}

		//Atualiza a INSTANCIA
		$obUser->nome = $nome;
		$obUser->email = $email;
		$obUser->senha = password_hash($senha, PASSWORD_DEFAULT);
		$obUser->atualizar();

		

		//REDIRECIONA O USUARIO
		$request->getRouter()->redirect('/admin/users/'.$obUser->id.'/edit?status=updated');
 	}
		
		/**
     * METODO RESPONSAVEL RETORNAR O FORMULARIO DE USUARIO
     * @param Request $request
	 * @param integer $id
     * @return string
     * 
     */
	public static function getDeleteUser($request,$id){
		// Obtem o feedback do banco de dados pelo id
		$obUser = EntityUser::getUserById($id);

		//VALIDANDO A INSTANCIA
		if(!$obUser instanceof EntityUser){
			$request->getRouter()->redireect('/admin/users');
		}


		//CONTEUDO DA FORMULARIO
		$content=  View::render('admin/modules/users/delete',[
			
			'nome' => $obUser->nome,
			'email'=> $obUser->email
			
		]);

		//RETORNA A PAGINA COMPLETA 
		return parent::getPanel('Excluir Usuario - AliDEV',$content,'users');

 	}


	/**
    * METODO RESPONSAVEL POR EXCLUIR UM USUARIO
    * @param Request $request
	* @param integer $id
    * @return string
    * 
    */
	public static function setDeleteUser($request,$id){
		// Obtem o feedback do banco de dados pelo id
		$obUser = EntityUser::getUserById($id);

		//VALIDANDO A INSTANCIA
		if(!$obUser instanceof EntityUser){
			$request->getRouter()->redireect('/admin/users');
		}

		//Exclui o deedback
		$obUser->excluir();

		

		//REDIRECIONA O USUARIO
		$request->getRouter()->redirect('/admin/users?status=deleted');
 	}
}