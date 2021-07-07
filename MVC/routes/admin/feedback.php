<?php

namespace Routes\Admin;

use \App\Http\Response;
use \App\Controller\Admin;


//ROTA DE LISTAGEM DE COMENTARIOS
$obRouter->get('/admin/feedback',[	
	'middlewares' => [
		'required-admin-login'
	],

	function($request){
		return new Response(200,Admin\Feedback::getFeedback($request));
	}
]);

//ROTA DE CADASTRO DE UM NOVO DEPOIMENTO
$obRouter->get('/admin/feedback/new',[	
	'middlewares' => [
		'required-admin-login'
	],

	function($request){
		return new Response(200,Admin\Feedback::getNewFeedback($request));
	}
]);

//ROTA DE CADASTRO DE UM NOVO DEPOIMENTO(POST)
$obRouter->post('/admin/feedback/new',[	
	'middlewares' => [
		'required-admin-login'
	],

	function($request){
		return new Response(200,Admin\Feedback::setNewFeedback($request));
	}
]);

//ROTA DE EDIÇÃO DE UM DEPOIMENTO
$obRouter->get('/admin/feedback/{id}/edit',[	
	'middlewares' => [
		'required-admin-login'
	],

	function($request,$id){
		return new Response(200,Admin\Feedback::getEditFeedback($request,$id));
	}
]);

//ROTA DE EDIÇÃO DE UM DEPOIMENTO (POST)
$obRouter->post('/admin/feedback/{id}/edit',[	
	'middlewares' => [
		'required-admin-login'
	],

	function($request,$id){
		return new Response(200,Admin\Feedback::setEditFeedback($request,$id));
	}
]);

//ROTA DE EXCLUSÃO DE UM DEPOIMENTO
$obRouter->get('/admin/feedback/{id}/delete',[	
	'middlewares' => [
		'required-admin-login'
	],

	function($request,$id){
		return new Response(200,Admin\Feedback::getDeleteFeedback($request,$id));
	}
]);

//ROTA DE EXCLUSÃO DE UM DEPOIMENTO (POST)
$obRouter->post('/admin/feedback/{id}/delete',[	
	'middlewares' => [
		'required-admin-login'
	],

	function($request,$id){
		return new Response(200,Admin\Feedback::setDeleteFeedback($request,$id));
	}
]);