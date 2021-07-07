<?php


use \App\Http\Response;
use \App\Controller\Pages;

//ROTA HOME
$obRouter->get('/home',[
	function(){
		return new Response(200,Pages\Home::getHome());
	}
]);

//ROTA SOBRE
$obRouter->get('/sobre',[
	function(){
		return new Response(200,Pages\About::getAbout());
	}
]);

//ROTA COMENTARIOS
$obRouter->get('/comentarios',[
	function($request){
		return new Response(200,Pages\Feedback::getFeedback($request));
	}
]);

//ROTA COMENTARIOS (insert)
$obRouter->post('/comentarios',[
	function($request){
		return new Response(200,Pages\Feedback::insertFeedback($request));
	}
]);





?>