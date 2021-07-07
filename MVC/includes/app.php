<?php
require __DIR__.'/../vendor/autoload.php';


use \App\Utils\View;
use \WilliamCosta\DotEnv\Environment;
use WilliamCosta\DatabaseManager\Database;
use \App\Http\Middleware\Queue as MiddlewareQueue;


//Carrega variaveis de ambiente
Environment::load(__DIR__.'/../');

//DEFINE AS CONFIGURAÇÕES DE BANCO DE DADOS
Database::config(
    getenv('DB_HOST'),
    getenv('DB_NAME'),
    getenv('DB_USER'),
    getenv('DB_PASS'),
    getenv('DB_PORT')
);
//Define a constante de url
define('URL',getenv('URL'));

//DEFINE VALOR PADRÃO DAS VARIAVEIS
View::init([

	'URL' => URL

]);

//Define o mapeamento de Middlewares
MiddlewareQueue::setMap([
    'maintenance' => \App\Http\Middleware\Maintenance::class,
    'required-admin-logout' => \App\Http\Middleware\RequireAdminLogout::class,
    'required-admin-login' => \App\Http\Middleware\RequireAdminLogin::class,
    'api' => \App\Http\Middleware\Api::class
]);

//Define o mapeamento de Middlewares Padrões
MiddlewareQueue::setDefault([
    'maintenance'

]);