<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use Xoopsmodules\instruction;

//include __DIR__ . '/../preloads/autoloader.php';

/**
 * @copyright    XOOPS Project https://xoops.org/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 */

//if (!defined('INSTRUCTION_MODULE_PATH')) {
//    define('INSTRUCTION_DIRNAME', basename(dirname(__DIR__)));
//    define('INSTRUCTION_URL', XOOPS_URL . '/modules/' . INSTRUCTION_DIRNAME);
//    define('INSTRUCTION_IMAGE_URL', INSTRUCTION_URL . '/assets/images/');
//    define('INSTRUCTION_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/' . INSTRUCTION_DIRNAME);
//    define('INSTRUCTION_IMAGE_PATH', INSTRUCTION_ROOT_PATH . '/assets/images');
//    define('INSTRUCTION_ADMIN_URL', INSTRUCTION_URL . '/admin/');
//    define('INSTRUCTION_UPLOAD_URL', XOOPS_UPLOAD_URL . '/' . INSTRUCTION_DIRNAME);
//    define('INSTRUCTION_UPLOAD_PATH', XOOPS_UPLOAD_PATH . '/' . INSTRUCTION_DIRNAME);
//}
//xoops_loadLanguage('common', INSTRUCTION_DIRNAME);

//require_once INSTRUCTION_ROOT_PATH . '/class/Utility.php';
//require_once INSTRUCTION_ROOT_PATH . '/include/constants.php';
//require_once INSTRUCTION_ROOT_PATH . '/include/seo_functions.php';
//require_once INSTRUCTION_ROOT_PATH . '/class/metagen.php';
//require_once INSTRUCTION_ROOT_PATH . '/class/session.php';
//require_once INSTRUCTION_ROOT_PATH . '/class/xoalbum.php';
//require_once INSTRUCTION_ROOT_PATH . '/class/request.php';

//require_once INSTRUCTION_ROOT_PATH . '/class/Helper.php';
//require_once INSTRUCTION_ROOT_PATH . '/class/InstructionHandler.php';
//require_once INSTRUCTION_ROOT_PATH . '/class/CategoryHandler.php';
//require_once INSTRUCTION_ROOT_PATH . '/class/PageHandler.php';

require_once __DIR__  . '/../class/Helper.php';
require_once __DIR__  . '/../class/Utility.php';
require_once __DIR__  . '/../class/InstructionHandler.php';
require_once __DIR__  . '/../class/CategoryHandler.php';
require_once __DIR__  . '/../class/PageHandler.php';

//xoops_load('constants', INSTRUCTION_DIRNAME);
//xoops_load('utility', INSTRUCTION_DIRNAME);

///** @var Xoopsmodules\instruction\Helper $helper */
//$helper = instruction\Helper::getInstance();

// Объявляем объекты
//$instructionHandler = xoops_getModuleHandler('instruction', 'instruction');
//$categoryHandler   = xoops_getModuleHandler('category', 'instruction');
//$pageHandler = xoops_getModuleHandler( 'page', 'instruction' );

$db = \XoopsDatabaseFactory::getDatabase();

$helper = instruction\Helper::getInstance();
/** @var \Xoopsmodules\instruction\Utility $utility */
$utility = new instruction\Utility();
/** @var \Xoopsmodules\instruction\InstructionHandler $instructionHandler */
$instructionHandler = new instruction\InstructionHandler($db);
/** @var \Xoopsmodules\instruction\CategoryHandler $categoryHandler */
$categoryHandler = new instruction\CategoryHandler($db);
/** @var \Xoopsmodules\instruction\PageHandler $pageHandler */
$pageHandler = new instruction\PageHandler($db);

$helper->loadLanguage('common');

$pathIcon16    = Xmf\Module\Admin::iconUrl('', 16);
$pathIcon32    = Xmf\Module\Admin::iconUrl('', 32);
$pathModIcon16 = $helper->getModule()->getInfo('modicons16');
$pathModIcon32 = $helper->getModule()->getInfo('modicons32');


$debug = false;

if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof \XoopsTpl)) {
    require_once $GLOBALS['xoops']->path('class/template.php');
    $xoopsTpl = new \XoopsTpl();
}

$moduleDirName = basename(dirname(__DIR__));
$GLOBALS['xoopsTpl']->assign('mod_url', XOOPS_URL . '/modules/' . $moduleDirName);

// Local icons path
$GLOBALS['xoopsTpl']->assign('pathModIcon16', XOOPS_URL . '/modules/' . $moduleDirName . '/' . $pathModIcon16);
$GLOBALS['xoopsTpl']->assign('pathModIcon32', $pathModIcon32);
