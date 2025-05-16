<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'Clientes';
$route['404_override'] = 'Errors/error_404'; // Corrigido para controller/method
$route['translate_uri_dashes'] = FALSE;

// Rotas para Clientes (CRUD completo)
$route['clientes'] = 'Clientes/index';
$route['clientes/novo'] = 'Clientes/create';
$route['clientes/editar/(:num)'] = 'Clientes/edit/$1';
$route['clientes/visualizar/(:num)'] = 'Clientes/view/$1';
$route['clientes/remover/(:num)'] = 'Clientes/delete/$1';
$route['clientes/salvar'] = 'Clientes/store';
$route['clientes/atualizar/(:num)'] = 'Clientes/update/$1';

// Rotas para Pedidos (Gestão de pedidos)
$route['pedidos'] = 'Pedidos/index';
$route['pedidos/novo'] = 'Pedidos/create';
$route['pedidos/editar/(:num)'] = 'Pedidos/edit/$1';
$route['pedidos/detalhes/(:num)'] = 'Pedidos/view/$1';
$route['pedidos/cancelar/(:num)'] = 'Pedidos/cancel/$1';

// Rotas para Produtos (Catálogo)
$route['produtos'] = 'Produtos/index';
$route['produtos/novo'] = 'Produtos/create';
$route['produtos/editar/(:num)'] = 'Produtos/edit/$1';
$route['produtos/estoque/(:num)'] = 'Produtos/stock/$1';

// Rotas para Estoque (Gestão de inventário)
$route['estoque'] = 'Estoque/index';
$route['estoque/entrada'] = 'Estoque/adicionar';
$route['estoque/ajuste'] = 'Estoque/ajustar';
$route['estoque/historico'] = 'Estoque/historico';

// Rotas para Cupons (Promocionais)
$route['cupons'] = 'Cupons/index';
$route['cupons/novo'] = 'Cupons/create';
$route['cupons/validar'] = 'Cupons/validar';
$route['cupons/(:num)'] = 'Cupons/view/$1';

// Rotas para Carrinho (Checkout)
$route['carrinho'] = 'Carrinho/index';
$route['carrinho/adicionar/(:num)'] = 'Carrinho/add/$1';
$route['carrinho/remover/(:num)'] = 'Carrinho/remove/$1';
$route['carrinho/atualizar'] = 'Carrinho/update';
$route['carrinho/checkout'] = 'Carrinho/checkout';

// Rotas para Webhook (Integrações)
$route['webhook/pedidos'] = 'Webhook/atualizar';
$route['webhook/pagamentos'] = 'Webhook/pagamentos';

// Rotas para Email (Comunicação)
$route['email/enviar/(:num)'] = 'Email/enviar/$1';
$route['email/template/(:any)'] = 'Email/template/$1';

// Rotas para Autenticação (Se necessário)
$route['login'] = 'Auth/login';
$route['logout'] = 'Auth/logout';
$route['perfil'] = 'Auth/profile';

// Rotas para Erros (Tratamento centralizado)
$route['erro/(:num)'] = 'Errors/error/$1';
$route['erro/404'] = 'Errors/error_404';
$route['erro/403'] = 'Errors/error_403';
$route['erro/500'] = 'Errors/error_500';

$route['404_override'] = 'Errors/error_404';
$route['500_override'] = 'Errors/error_500'; 
$route['403_override'] = 'Errors/error_403';

$route['carrinho/atualizar/(:num)'] = 'Carrinho/atualizar/$1';
$route['carrinho/remover/(:num)'] = 'Carrinho/remover/$1';

$route['cupons/edit/(:num)'] = 'Cupons/edit/$1';
$route['cupons/delete/(:num)'] = 'Cupons/delete/$1';

$route['pedidos/view/(:num)'] = 'Pedidos/view/$1';
$route['pedidos/cancelar/(:num)'] = 'Pedidos/cancelar/$1';

$route['produtos/edit/(:num)'] = 'Produtos/edit/$1';
$route['produtos/delete/(:num)'] = 'Produtos/delete/$1';

$route['erro/error_db'] = 'Errors/error_db';

// Rota genérica para páginas estáticas
$route['(:any)'] = 'Pages/view/$1';
