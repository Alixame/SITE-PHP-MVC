<?php

use \App\Http\Response;
use \App\Controller\Api;

// ROTA DE LISTAGEM DE FEEDBACK 
$obRouter->get('/api/v1/feedback',[
    'middlewares' => [
        'api'
    ],

    function($request){
        return new Response(200, Api\Feedback::getFeedback($request),'application/json');
    }
]);

// ROTA DE LISTAGEM DE FEEDBACK 
$obRouter->get('/api/v1/feedback/{id}',[
    'middlewares' => [
        'api'
    ],
    
    function($request,$id){
        return new Response(200, Api\Feedback::getFeedbacks($request,$id),'application/json');
    }
]);