<?php
/**
 * Index
 *
 * The Front Controller for handling every request
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.webroot
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */


/****************************************************************************
 * Check and modefy the following configuration
 ***************************************************************************/

if(!function_exists('get_htdocs_dir')){
    /**
     * The directory who has `www` or `public_html`,
     * `cakephp` and `cakeapp` as its children.
     *
     * This function works only not on test.
     *
     * @return string
     * @see get_htdocs_dir_test()
     */
    function get_htdocs_dir(){
        return dirname(dirname(__DIR__));
    }
}


if(!function_exists('get_htdocs_dir_test')){
    /**
     * The directory who has `www` or `public_html`,
     * `cakephp` and `cakeapp` as its children.
     *
     * This function works only on test.
     *
     * @return string
     * @see get_htdocs_dir()
     */
    function get_htdocs_dir_test(){
        return dirname(dirname(dirname(__DIR__)));
    }
}


if(!defined('CAKEPHP_VERSION')){
    /**
     * The name, one of `cakephp` children
     * `cakephp` has one or more CakePHP main directories,
     * and this is one of them.
     *
     * You can switch the version easily with rewriting this.
     *
     * @var string
     */
    define('CAKEPHP_VERSION', '2.x');
}


if (!defined('APP_DIR')) {
    /**
     * The name, one of `cakeapp` children
     * `cakeapp` has one or more CakeApp directories,
     * and this is one of them.
     *
     * This name should be the same of the directory of entry point.
     *
     * @var string
     */
    define('APP_DIR', basename(dirname(__FILE__)));
}


/********************** Thanks for cooperation *****************************/




/***************************************************************************
 * You don't have to change following if you don't understand
 ***************************************************************************/


if(!defined('IN_TEST_DIR')){
    /**
     * Whether the access is from a test directory.
     * If so, the webroot is in `tests` directory then there is difference
     * in its directory depth from when it's false.
     *
     * @var bool
     */
    define('IN_TEST_DIR', basename(dirname(__DIR__)) === 'tests');
}


if(!defined('HTDOC_DIR')){
    /**
     * The directory who has `www` or `public_html`,
     * `cakephp` and `cakeapp` as its children.
     *
     * @var string
     */
    define('HTDOC_DIR', IN_TEST_DIR ? get_htdocs_dir_test()  : get_htdocs_dir() );
}


/**
 * Use the DS to separate the directories in other defines
 */
if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

if(!defined('APP')){
    /**
     * The path to one of `cakephp` sub directories
     *
     * NOTE: the path has "/" at the last.
     *
     * @var string
     */
    define('APP', HTDOC_DIR . DS . 'cakeapp' . DS . APP_DIR . DS);
}

/**
 * These defines should only be edited if you have CakePHP installed in
 * a directory layout other than the way it is distributed.
 * When using custom settings be sure to use the DS and do not add a trailing DS.
 */

/**
 * The full path to the directory which holds "app", WITHOUT a trailing DS.
 *
 */
if (!defined('ROOT')) {
	define('ROOT', HTDOC_DIR . DS . 'cakephp' . DS . CAKEPHP_VERSION);
}


/**
 * The absolute path to the "cake" directory, WITHOUT a trailing DS.
 *
 * Un-comment this line to specify a fixed path to CakePHP.
 * This should point at the directory containing `Cake`.
 *
 * For ease of development CakePHP uses PHP's include_path. If you
 * cannot modify your include_path set this value.
 *
 * Leaving this constant undefined will result in it being defined in Cake/bootstrap.php
 *
 * The following line differs from its sibling
 * /lib/Cake/Console/Templates/skel/webroot/index.php
 */
//define('CAKE_CORE_INCLUDE_PATH', ROOT . DS . 'lib');

/**
 * This auto-detects CakePHP as a composer installed library.
 * You may remove this if you are not planning to use composer (not recommended, though).
 */
$vendorPath = APP . DS . 'Vendor' . DS . 'cakephp' . DS . 'cakephp' . DS . 'lib';
$dispatcher = 'Cake' . DS . 'Console' . DS . 'ShellDispatcher.php';
if (!defined('CAKE_CORE_INCLUDE_PATH') && file_exists($vendorPath . DS . $dispatcher)) {
	define('CAKE_CORE_INCLUDE_PATH', $vendorPath);
}

/**
 * Editing below this line should NOT be necessary.
 * Change at your own risk.
 *
 */
if (!defined('WEBROOT_DIR')) {
	define('WEBROOT_DIR', basename(dirname(__FILE__)));
}
if (!defined('WWW_ROOT')) {
	define('WWW_ROOT', dirname(__FILE__) . DS);
}

// for built-in server
if (php_sapi_name() === 'cli-server') {
	if ($_SERVER['REQUEST_URI'] !== '/' && file_exists(WWW_ROOT . $_SERVER['PHP_SELF'])) {
		return false;
	}
	$_SERVER['PHP_SELF'] = '/' . basename(__FILE__);
}

if (!defined('CAKE_CORE_INCLUDE_PATH')) {
	if (function_exists('ini_set')) {
		ini_set('include_path', ROOT . DS . 'lib' . PATH_SEPARATOR . ini_get('include_path'));
	}
	if (!include 'Cake' . DS . 'bootstrap.php') {
		$failed = true;
	}
} else {
	if (!include CAKE_CORE_INCLUDE_PATH . DS . 'Cake' . DS . 'bootstrap.php') {
		$failed = true;
	}
}
if (!empty($failed)) {
	trigger_error("CakePHP core could not be found. Check the value of CAKE_CORE_INCLUDE_PATH in APP/webroot/index.php. It should point to the directory containing your " . DS . "cake core directory and your " . DS . "vendors root directory.", E_USER_ERROR);
}

App::uses('Dispatcher', 'Routing');

$Dispatcher = new Dispatcher();
$Dispatcher->dispatch(
	new CakeRequest(),
	new CakeResponse()
);
