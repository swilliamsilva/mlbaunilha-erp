<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// ====================
// CONFIGURAÇÕES GLOBAIS
// ====================
$route['default_controller'] = 'Clientes';
$route['404_override'] = 'Errors/error_404';
$route['translate_uri_dashes'] = FALSE;

// ====================
// CLIENTES (CRUD)
// ====================
$route['clientes']                  = 'Clientes/index';
$route['clientes/novo']             = 'Clientes/create';
$route['clientes/editar/(:num)']    = 'Clientes/edit/$1';
$route['clientes/visualizar/(:num)']= 'Clientes/view/$1';
$route['clientes/salvar']           = 'Clientes/store';          // POST
$route['clientes/atualizar/(:num)'] = 'Clientes/update/$1';      // POST
$route['clientes/remover/(:num)']   = 'Clientes/delete/$1';      // POST

// ====================
// PEDIDOS
// ====================
$route['pedidos']               = 'Pedidos/index';
$route['pedidos/novo']          = 'Pedidos/create';
$route['pedidos/editar/(:num)'] = 'Pedidos/edit/$1';
$route['pedidos/detalhes/(:num)'] = 'Pedidos/view/$1';
$route['pedidos/cancelar/(:num)'] = 'Pedidos/cancelar/$1';      // POST

// ====================
// PRODUTOS
// ====================
$route['produtos']               = 'Produtos/index';
$route['produtos/novo']          = 'Produtos/create';
$route['produtos/editar/(:num)'] = 'Produtos/edit/$1';
$route['produtos/estoque/(:num)']= 'Produtos/stock/$1';
$route['produtos/remover/(:num)']= 'Produtos/delete/$1';        // POST

// ====================
// ESTOQUE
// ====================
$route['estoque']            = 'Estoque/index';
$route['estoque/entrada']    = 'Estoque/adicionar';            // POST
$route['estoque/ajuste']     = 'Estoque/ajustar';              // POST
$route['estoque/historico']  = 'Estoque/historico';

// ====================
// CUPONS
// ====================
$route['cupons']             = 'Cupons/index';
$route['cupons/novo']        = 'Cupons/create';
$route['cupons/editar/(:num)'] = 'Cupons/edit/$1';
$route['cupons/validar']     = 'Cupons/validar';               // POST
$route['cupons/remover/(:num)'] = 'Cupons/delete/$1';          // POST

// ====================
// CARRINHO
// ====================
$route['carrinho']                   = 'Carrinho/index';
$route['carrinho/adicionar/(:num)']  = 'Carrinho/add/$1';      // POST
$route['carrinho/remover/(:num)']    = 'Carrinho/remove/$1';   // POST
$route['carrinho/atualizar']         = 'Carrinho/update';      // POST
$route['carrinho/checkout']          = 'Carrinho/checkout';    // POST

// ====================
// WEBHOOKS (APIs)
// ====================
$route['webhook/pedidos']    = 'Webhook/atualizar';            // POST
$route['webhook/pagamentos']= 'Webhook/pagamentos';            // POST

// ====================
// EMAIL
// ====================
$route['email/enviar/(:num)'] = 'Email/enviar/$1';             // POST
$route['email/template/(:any)']= 'Email/template/$1';

// ====================
// AUTENTICAÇÃO
// ====================
$route['login']  = 'Auth/login';
$route['logout'] = 'Auth/logout';                              // POST
$route['perfil'] = 'Auth/profile';

// ====================
// ERROS (CENTRALIZADO)
// ====================
$route['erro/404']       = 'Errors/error_404';
$route['erro/403']       = 'Errors/error_403';
$route['erro/500']       = 'Errors/error_500';
$route['erro/error_db']  = 'Errors/error_db';

// ====================
// ROTA GENÉRICA (SEMPRE A ÚLTIMA!)
// ====================
$route['paginas/(:any)'] = 'Pages/view/$1';  // Ex: /paginas/sobre