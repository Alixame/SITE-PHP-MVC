<?php

namespace Routes\Admin;

use \App\Http\Response;
use \App\Controller\Admin;


//ROTA DE LISTAGEM DE USUARIOS
$obRouter->get('/admin/users',[	
	'middlewares' => [
		'required-admin-login'
	],

	function($request){
		return new Response(200,Admin\Users::getUsers($request));
	}
]);

//ROTA DE CADASTRO DE UM NOVO USUARIOS
$obRouter->get('/admin/users/new',[	
	'middlewares' => [
		'required-admin-login'
	],

	function($request){
		return new Response(200,Admin\Users::getNewUser($request));
	}
]);

//ROTA DE CADASTRO DE UM NOVO USUARIOS(POST)
$obRouter->post('/admin/users/new',[	
	'middlewares' => [
		'required-admin-login'
	],

	function($request){
		return new Response(200,Admin\Users::setNewUser($request));
	}
]);

//ROTA DE EDIÇÃO DE UM USUARIOS
$obRouter->get('/admin/users/{id}/edit',[	
	'middlewares' => [
		'required-admin-login'
	],

	function($request,$id){
		return new Response(200,Admin\Users::getEditUser($request,$id));
	}
]);

//ROTA DE EDIÇÃO DE UM USUARIOS (POST)
$obRouter->post('/admin/users/{id}/edit',[	
	'middlewares' => [
		'required-admin-login'
	],

	function($request,$id){
		return new Response(200,Admin\Users::setEditUser($request,$id));
	}
]);

//ROTA DE EXCLUSÃO DE UM USUARIOS
$obRouter->get('/admin/users/{id}/delete',[	
	'middlewares' => [
		'required-admin-login'
	],

	function($request,$id){
		return new Response(200,Admin\Users::getDeleteUser($request,$id));
	}
]);

//ROTA DE EXCLUSÃO DE UM USUARIOS (POST)
$obRouter->post('/admin/users/{id}/delete',[	
	'middlewares' => [
		'required-admin-login'
	],

	function($request,$id){
		return new Response(200,Admin\Users::setDeleteUser($request,$id));
	}
]);