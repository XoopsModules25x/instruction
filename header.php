<?php

include_once dirname(dirname(__DIR__)) . '/mainfile.php';
$moduleDirName = basename(__DIR__);

xoops_load('instruction', $moduleDirName);
$helper = Xmf\Module\Helper::getHelper($moduleDirName);

//
include_once __DIR__ . '/class/utility.php';
include_once __DIR__ . '/include/common.php';
// Трей
include_once $GLOBALS['xoops']->path('class/tree.php');
