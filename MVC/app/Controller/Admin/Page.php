<?php 


namespace App\Controller\Admin;

use \App\Utils\View;

class Page{

    /**
     *  MODULOS DISPONIVEIS NO PAINEL
     * 
     * 
     */
    private static $modules = [
        'home' => [
            'label' => 'Home',
            'link' => URL.'/admin'
        ],

        'feedback' => [
            'label' => 'Comentarios',
            'link' => URL.'/admin/feedback'
        ],

        'users' => [
            'label' => 'Usuarios',
            'link' => URL.'/admin/users'
        ]
    ];

    /**
     * Método responsavel por retornar o conteudo (view) da estrutura genetica de pagina do painel
     * @param string $title
     * @param string $content
     * @return string
     */
    public static function getPage($title,$content){
        return View::render('admin/page',[
            'title' => $title,
            'content' => $content

        ]);

    }

    private static function getMenu($currentModule){
        // links do menu
        $links = '';

        foreach(self::$modules as $hash=>$module){
            $links .= View::render('admin/menu/link',[
                'label' => $module['label'],
                'link' => $module['link'],
                'current' => $hash == $currentModule ? 'text-info' : ''
            ]);      
        }

        return View::render('admin/menu/box',[
            'links'=> $links
        ]);


    }




    /**
     * Método responsavel por renderizar a view do painel com conteudo dinamico
     * @param string $title
     * @param string $content
     * @param string $currentModule
     * @return string
     */
    public static function getPanel($title,$content,$currentModule){
        
        $contentPanel = View::render('admin/panel',[
            'menu' => self::getMenu($currentModule),
            'content' => $content

        ]);

        //Retorna a Pagina renderizada
        return self::getPage($title,$contentPanel);
    }


    /**
	 *Metodo responsavel por renderizar o layout da paginação
	 *@return string 
	 */
	public static function getPagination($request,$obPagination){
		//Paginas
		$pages = $obPagination->getPages();

		//Verifica a quantidade de paginas
		if(count($pages) <= 1) return '';

		//Links
		$links ='';

		//URL atual (sem GETS)
		$url = $request->getRouter()->getCurrentUrl();

		//GET
		$queryParams = $request->getQueryParams();

		//Renderiza os links
		foreach($pages as $page){
			//Altera pagina
			$queryParams['page'] = $page['page'];

			//Link
			$link = $url.'?'.http_build_query($queryParams);

			//View
			$links .=  View::render('admin/pagination/link',[
				'page' => $page['page'],
				'link' => $link,
				'active' => $page['current'] ? 'active' : ' '
				]);
		}

		//Renderiza Box de Paginacao
		return View::render('admin/pagination/box',[
			'links' => $links
			]);

	}



}