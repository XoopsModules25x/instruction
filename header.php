<?php

use Xoopsmodules\instruction;

include_once dirname(dirname(__DIR__)) . '/mainfile.php';
$moduleDirName = basename(__DIR__);

//xoops_load('instruction', $moduleDirName);

//
//include_once __DIR__ . '/class/utility.php';
include_once __DIR__ . '/include/common.php';

//$helper = new \Xoopsmodules\instruction\Helper::getHelper($moduleDirName);
// Трей
include_once $GLOBALS['xoops']->path('class/tree.php');
