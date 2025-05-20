<?php
// Exibir todos os erros para ambiente de teste
ini_set('display_errors', 1);
error_reporting(E_ALL | E_STRICT);

$dir = realpath(dirname(__FILE__));

// Definir constantes de caminho
defined('PROJECT_BASE') || define('PROJECT_BASE', realpath($dir.'/../').'/');
defined('SYSTEM_PATH') || define('SYSTEM_PATH', PROJECT_BASE.'system/');

// Carregar vfsStream via Composer
if (file_exists(PROJECT_BASE.'vendor/autoload.php')) {
    require_once PROJECT_BASE.'vendor/autoload.php';
    
    // Adicionar aliases com documentação para o Intelephense
    if (!class_exists('vfsStream', false)) {
        class_alias('org\bovigo\vfs\vfsStream', 'vfsStream');
        /** @class \org\bovigo\vfs\vfsStream */
        class vfsStream extends \org\bovigo\vfs\vfsStream {}
    }

    if (!class_exists('vfsStreamDirectory', false)) {
        class_alias('org\bovigo\vfs\vfsStreamDirectory', 'vfsStreamDirectory');
        /** @class \org\bovigo\vfs\vfsStreamDirectory */
        class vfsStreamDirectory extends \org\bovigo\vfs\vfsStreamDirectory {}
    }

    if (!class_exists('vfsStreamWrapper', false)) {
        class_alias('org\bovigo\vfs\vfsStreamWrapper', 'vfsStreamWrapper');
        /** @class \org\bovigo\vfs\vfsStreamWrapper */
        class vfsStreamWrapper extends \org\bovigo\vfs\vfsStreamWrapper {}
    }
} else {
    die('vfsStream não encontrado. Execute "composer require bovigo/vfsStream" para instalar.');
}


// Definir caminhos do CodeIgniter no sistema virtual
defined('BASEPATH')    || define('BASEPATH', vfsStream::url('system/'));
defined('APPPATH')     || define('APPPATH', vfsStream::url('application/'));
defined('VIEWPATH')    || define('VIEWPATH', APPPATH.'views/');
defined('ENVIRONMENT') || define('ENVIRONMENT', 'development');

// Simular acesso local
$_SERVER['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

// Carregar mocks e componentes essenciais
include_once $dir.'/mocks/core/common.php';
include_once SYSTEM_PATH.'core/Common.php';

// Configurações de codificação
ini_set('default_charset', 'UTF-8');

// Verificar extensões de caracteres
if (extension_loaded('mbstring')) {
    defined('MB_ENABLED') || define('MB_ENABLED', true);
    mb_substitute_character('none');
} else {
    defined('MB_ENABLED') || define('MB_ENABLED', false);
}

if (extension_loaded('iconv')) {
    defined('ICONV_ENABLED') || define('ICONV_ENABLED', true);
} else {
    defined('ICONV_ENABLED') || define('ICONV_ENABLED', false);
}

// Ajustes para compatibilidade com PHP 7+
if (version_compare(PHP_VERSION, '7.0', '>=')) {
    if (file_exists($testCasePath = PROJECT_BASE.'vendor/phpunit/phpunit/src/Framework/TestCase.php')) {
        $testCaseCode = file_get_contents($testCasePath);
        $testCaseCode = preg_replace('/^\s+((?:protected|public)(?: static)? function \w+\(\)): void/m', '$1', $testCaseCode);
        file_put_contents($testCasePath, $testCaseCode);
    }
}

// Carregar polyfills para funções faltantes
include_once SYSTEM_PATH.'core/compat/mbstring.php';
include_once SYSTEM_PATH.'core/compat/hash.php';
include_once SYSTEM_PATH.'core/compat/password.php';
include_once SYSTEM_PATH.'core/compat/standard.php';

// Registrar autoloader personalizado
include_once $dir.'/mocks/autoloader.php';
spl_autoload_register('autoload');

unset($dir);
