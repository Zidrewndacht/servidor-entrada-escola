<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');

use App\Controllers\AdminController;
use App\Controllers\AlunoController;
use App\Controllers\RespController;
use App\Controllers\MQTTController;

$routes->get('/',                    [AdminController::class, 'redir']);            //força redirecionamento para filtragem de admin;
$routes->get('/status_resp/(:any)',  [RespController::class, 'statusResp/$1']);     //em views/tabela_resp
$routes->get('/status_aluno/(:any)', [AlunoController::class, 'statusAluno/$1']);   //em views/tabela_alunos
$routes->post('/rfid_lido',          [MQTTController::class, 'POSTevtAluno']);     //a implementar

$routes->get('/resp/(:any)',         [RespController::class, 'statusRespUser/$1']); //em auth/loginRedirect();
$routes->get('/ip',                  [AdminController::class, 'getIP']);

$routes->group('admin', ['filter' => 'superadmin'], function($routes) { //adicionar filtro para acesso somente de superadmin aqui.
    //status:    
    $routes->get('novo_aluno',          [AlunoController::class, 'novoAluno']);   
    $routes->post('novo_aluno',         [AlunoController::class, 'POSTnovoAluno']); 

    $routes->get('cad_ban/(:any)',      [AlunoController::class, 'removeAluno/$1']);  
    $routes->post('cad_ban/(:any)',     [AlunoController::class, 'POSTremoveAluno/$1']); 

    $routes->get('cad_csv',             [AlunoController::class, 'cadastraCSV']);  
    $routes->post('cad_csv',            [AlunoController::class, 'POSTcadastraCSV']); 

    $routes->get('write_tag/(:any)',            [MQTTController::class, 'writeTag/$1']);           
    $routes->get('mqtt_write_request/(:any)',   [MQTTController::class, 'cadNovoPublish/$1']); 

    // responsável:
    $routes->get('novo_resp',           [RespController::class, 'novoResp']);
    $routes->post('novo_resp',/*POST */ [RespController::class, 'POSTnovoResp']);

    $routes->get('email_reset/(:any)',  [RespController::class, 'alteraRespEmail/$1']); 
    $routes->post('email_reset/(:any)', [RespController::class, 'POSTalteraRespEmail/$1']);  

    $routes->get('resp_ban/(:any)',     [RespController::class, 'removeResp/$1']);  
    $routes->post('resp_ban/(:any)',    [RespController::class, 'POSTremoveResp/$1']);  

    $routes->get('assoc/(:any)',        [RespController::class, 'assocResp/$1']);
    $routes->post('assoc/(:any)',       [RespController::class, 'POSTassocResp/$1']); 

    $routes->get('pw_reset/(:any)',     [RespController::class, 'pwRespReset/$1']);            //a fazer, criar popup via JS (sem servidor) para confirmação
    
    //painel principal admin:
    $routes->get('/',                  [AdminController::class, 'adminIndex']);
    $routes->get('tabela_alunos',      [AdminController::class, 'tabelaAlunos']);
    $routes->get('tabela_resp',        [AdminController::class, 'tabelaResp']);
    $routes->get('tabela_evt',         [AdminController::class, 'tabelaEvt']);

    //config admin:
    $routes->get('setup_admin',       [AdminController::class, 'setupAdmin']);
    $routes->post('setup_admin',      [AdminController::class, 'POSTsetupAdmin']);
    
    $routes->get('setup_mqtt',        [AdminController::class, 'setupMQTT']);
    $routes->post('setup_mqtt',       [AdminController::class, 'POSTsetupMQTT']);
});

service('auth')->routes($routes);
