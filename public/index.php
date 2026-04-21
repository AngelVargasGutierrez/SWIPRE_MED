<?php
define('BASE_PATH', dirname(__DIR__));
define('BASE_URL', '/swipre-med/public');

require_once BASE_PATH . '/core/Router.php';
require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/core/Model.php';
require_once BASE_PATH . '/core/View.php';
require_once BASE_PATH . '/core/Database.php';
require_once BASE_PATH . '/config/config.php';

session_start();

$router = new Router();

// Auth routes
$router->get('/', 'AuthController', 'loginForm');
$router->get('/login', 'AuthController', 'loginForm');
$router->post('/login', 'AuthController', 'login');
$router->get('/logout', 'AuthController', 'logout');

// Dashboard
$router->get('/dashboard', 'DashboardController', 'index');

// Medicamentos
$router->get('/medicamentos', 'MedicamentoController', 'index');
$router->get('/medicamentos/create', 'MedicamentoController', 'create');
$router->post('/medicamentos/store', 'MedicamentoController', 'store');
$router->get('/medicamentos/edit/{id}', 'MedicamentoController', 'edit');
$router->post('/medicamentos/update/{id}', 'MedicamentoController', 'update');
$router->post('/medicamentos/delete/{id}', 'MedicamentoController', 'delete');
$router->get('/medicamentos/show/{id}', 'MedicamentoController', 'show');
$router->get('/medicamentos/search', 'MedicamentoController', 'search');

// Inventario
$router->get('/inventario', 'InventarioController', 'index');
$router->post('/inventario/entrada', 'InventarioController', 'entrada');
$router->post('/inventario/salida', 'InventarioController', 'salida');
$router->get('/inventario/movimientos', 'InventarioController', 'movimientos');

// Notificaciones
$router->get('/notificaciones', 'NotificacionController', 'index');
$router->post('/notificaciones/marcar-leida/{id}', 'NotificacionController', 'marcarLeida');
$router->post('/notificaciones/marcar-todas', 'NotificacionController', 'marcarTodas');

// Reportes
$router->get('/reportes', 'ReporteController', 'index');
$router->get('/reportes/exportar', 'ReporteController', 'exportar');

// Analytics
$router->get('/analytics', 'AnalyticsController', 'index');
$router->get('/analytics/data', 'AnalyticsController', 'getData');

// Usuarios
$router->get('/usuarios', 'UsuarioController', 'index');
$router->get('/usuarios/create', 'UsuarioController', 'create');
$router->post('/usuarios/store', 'UsuarioController', 'store');
$router->get('/usuarios/edit/{id}', 'UsuarioController', 'edit');
$router->post('/usuarios/update/{id}', 'UsuarioController', 'update');
$router->post('/usuarios/delete/{id}', 'UsuarioController', 'delete');

$router->dispatch();
