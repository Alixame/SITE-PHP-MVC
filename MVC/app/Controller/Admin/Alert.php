<?php


namespace App\Controller\Admin;
use \App\Utils\View;


class Alert{

    /**
     *  METODO RESPOSNAVEL POR RETORNAR UMA MENSAGEM DE SUCESSO
     *  @param string $message
     *  @return string 
     */
    public static function getSuccess($message){
        return View::render('admin/alert/status',[
            'tipo' => 'success',
            'mensagem' => $message
        ]);
    }

    /**
     *  METODO RESPOSNAVEL POR RETORNAR UMA MENSAGEM DE ERRO
     *  @param string $message
     *  @return string 
     */
    public static function getError($message){
        return View::render('admin/alert/status',[
            'tipo' => 'danger',
            'mensagem' => $message
        ]);
    }



}