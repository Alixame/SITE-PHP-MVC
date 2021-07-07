<?php

require __DIR__.'/includes/app.php';

use \App\Http\Router;

//INICIA O ROUYTER
$obRouter = new Router(URL);

//INCLUI AS ROTAS DE PAGINAS
include __DIR__.'/routes/pages.php';

//INCLUI AS ROTAS do painel
include __DIR__.'/routes/admin.php';

//INCLUI AS ROTAS DA API
include __DIR__.'/routes/api.php';

//Imprime Response da Pagina
$obRouter->run()
		 ->sendResponse();
