<?php

use XoopsModules\Instruction;

require_once dirname(dirname(__DIR__)) . '/mainfile.php';
$moduleDirName = basename(__DIR__);

//xoops_load('Instruction', $moduleDirName);

//
//include_once __DIR__ . '/class/Utility.php';
include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
require_once __DIR__ . '/include/common.php';

//$helper = new \XoopsModules\Instruction\Helper::getHelper($moduleDirName);
// Трей
include_once $GLOBALS['xoops']->path('class/Tree.php');
