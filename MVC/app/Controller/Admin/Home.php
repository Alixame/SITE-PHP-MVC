<?php

namespace App\Controller\Admin;

use \App\Utils\View;


class Home extends Page{


    /**
     * METODO RESPONSAVEL POR RENDERIZAR A VIEW HOME DO PAINEL
     * @param Request $request
     * @return string
     * 
     */
    public static function getHome($request){
        //CONTEUDO DA HOME
        $content=  View::render('admin/modules/home/index',[]);

        //RETORNA A PAGINA COMPLETA 
        return parent::getPanel(' Home - AliDEV',$content,'home');


    }
    


}