<?php
define('CMTX_BACKEND', true);

define('CMTX_VERSION', '3.1');

header('Content-Type: text/html; charset=utf-8');

ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.use_trans_sid', 0);
ini_set('session.gc_maxlifetime', 1440);
ini_set('session.cookie_lifetime', 0);
ini_set('session.auto_start', 0);
ini_set('session.cookie_secure', 0);

session_start();

define('CMTX_HTTP_THIS', '');
define('CMTX_HTTP_ROOT', '../');
define('CMTX_HTTP_SYSTEM', CMTX_HTTP_ROOT . 'system/');
define('CMTX_HTTP_BACKUPS', CMTX_HTTP_SYSTEM . 'backups/');
define('CMTX_HTTP_VIEW', 'view/');
define('CMTX_HTTP_UPLOAD', CMTX_HTTP_ROOT . 'upload/');

define('CMTX_DIR_THIS', str_replace('\\', '/', realpath(__DIR__)) . '/');
define('CMTX_DIR_ROOT', str_replace('\\', '/', realpath(__DIR__ . '/../')) . '/');
define('CMTX_DIR_SYSTEM', CMTX_DIR_ROOT . 'system/');
define('CMTX_DIR_BACKUPS', CMTX_DIR_SYSTEM . 'backups/');
define('CMTX_DIR_DATABASE', CMTX_DIR_SYSTEM . 'database/');
define('CMTX_DIR_ENGINE', CMTX_DIR_SYSTEM . 'engine/');
define('CMTX_DIR_HELPER', CMTX_DIR_SYSTEM . 'helper/');
define('CMTX_DIR_LIBRARY', CMTX_DIR_SYSTEM . 'library/');
define('CMTX_DIR_LOGS', CMTX_DIR_SYSTEM . 'logs/');
define('CMTX_DIR_MODIFICATION', CMTX_DIR_SYSTEM . 'modification/');
define('CMTX_DIR_MOD_CACHE', CMTX_DIR_MODIFICATION . 'cache/');
define('CMTX_DIR_MOD_XML', CMTX_DIR_MODIFICATION . 'xml/');
define('CMTX_DIR_MODEL', CMTX_DIR_THIS . 'model/');
define('CMTX_DIR_VIEW', CMTX_DIR_THIS . 'view/');
define('CMTX_DIR_CONTROLLER', CMTX_DIR_THIS . 'controller/');
define('CMTX_DIR_3RDPARTY', CMTX_DIR_ROOT . '3rdparty/');
define('CMTX_DIR_UPLOAD', CMTX_DIR_ROOT . 'upload/');

if (file_exists(CMTX_DIR_ROOT . 'config.php') && filesize(CMTX_DIR_ROOT . 'config.php')) {
	require_once(CMTX_DIR_ROOT . 'config.php');
} else {
	die('<b>Error</b>: Commentics is not installed');
}

require_once(CMTX_DIR_SYSTEM . 'startup.php');

if (!$cmtx_db->isConnected()) {
	die('<b>Error</b>: ' . $cmtx_db->getConnectError() . ($cmtx_db->getConnectErrno() ? ' (' . $cmtx_db->getConnectErrno() . ')' : ''));
}

if (!$cmtx_db->isInstalled()) {
	die('<b>Error</b>: There are no database tables');
}

if (isset($cmtx_request->get['route']) && $cmtx_request->get['route'] == 'login/reset') {
	require_once(cmtx_modification(CMTX_DIR_CONTROLLER . 'login/reset.php'));

	$controller = new \Commentics\LoginResetController($cmtx_registry);

	$controller->index();
} else {
	require_once(cmtx_modification(CMTX_DIR_CONTROLLER . 'login/login.php'));

	$controller = new \Commentics\LoginLoginController($cmtx_registry);

	$controller->index();
}

if (isset($cmtx_request->get['route']) && (preg_match('/^[a-z0-9_]+\/[a-z0-9_]+$/i', $cmtx_request->get['route']) || preg_match('/^[a-z0-9_]+\/[a-z0-9_]+\/[a-z0-9_]+$/i', $cmtx_request->get['route']))) {
	$parts = explode('/', strtolower($cmtx_request->get['route']));

	if (file_exists(CMTX_DIR_CONTROLLER . $parts[0] . '/' . $parts[1] . '.php')) {
		require_once(cmtx_modification(CMTX_DIR_CONTROLLER . $parts[0] . '/' . $parts[1] . '.php'));

		$parts = str_replace('_', '', $parts);

		$class = '\Commentics\\' . $parts[0] . $parts[1] . 'Controller';

		$controller = new $class($cmtx_registry);

		if (isset($parts[2]) && substr($parts[2], 0, 2) != '__' && method_exists($controller, $parts[2]) && is_callable(array($controller, $parts[2]))) {
			if ($parts[0] == 'module' && in_array($parts[2], array('install', 'uninstall'))) {
				$cmtx_response->redirect('extension/modules');
			} else {
				$controller->{$parts[2]}();
			}
		} else {
			$controller->index();
		}
	} else {
		$cmtx_response->redirect('main/dashboard');
	}
} else {
	$cmtx_response->redirect('main/dashboard');
}
?>