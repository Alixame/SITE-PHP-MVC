<?php

namespace App\Controller\Api;

class Api{

    /**
     *  METEODO RESPONSAVEL POR RETORNAR OS DETALHES DA API
     * @param Request $request
     * @return array
     */
    public static function getDetails($request){
        return [
            'nome' => 'API - AliDEV',
            'versao' => 'v1.0.0',
            'autor' => 'Lucas Alixame',
            'email' => 'lucasali2003@gmail.com'
        ];
    }
    
    /**
     *  METEODO RESPONSAVEL POR RETORNAR OS DETALHES DA PAGINAÇÃO
     * @param Request $request
     * @param Pagination $obPagination
     * @return array
     */
    protected static function getPagination($request, $obPagination){
        // query params
        $queryParams = $request->getQueryParams();

        //PAGINA 
        $pages = $obPagination->getPages();

        //RETORNO
        return [
            'paginaAtual' => isset($queryParams['page']) ? (int)$queryParams['page'] : 1,
            'quantidadePaginas' => !empty($pages) ? count($pages) : 1
        ];
    }

}