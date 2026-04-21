<?php
define('BASE_PATH', dirname(__DIR__));
define('BASE_URL',  '');

// Sobreescribir credenciales de BD desde variables de entorno (CI)
define('DB_HOST',    getenv('DB_HOST')    ?: '127.0.0.1');
define('DB_PORT',    (int)(getenv('DB_PORT') ?: 3307));
define('DB_NAME',    getenv('DB_NAME')    ?: 'swipre_med');
define('DB_USER',    getenv('DB_USER')    ?: 'root');
define('DB_PASS',    getenv('DB_PASS')    ?: '');
define('DB_CHARSET', 'utf8mb4');
define('STOCK_CRITICO_PORCENTAJE', 0.3);
define('DIAS_VENCIMIENTO_ALERTA',  90);

require_once BASE_PATH . '/core/Database.php';
require_once BASE_PATH . '/core/Model.php';
require_once BASE_PATH . '/app/models/MedicamentoModel.php';
require_once BASE_PATH . '/app/models/MovimientoModel.php';
require_once BASE_PATH . '/app/models/NotificacionModel.php';
