<?php

include_once dirname(dirname(__DIR__)) . '/mainfile.php';
$moduleDirName = basename(__DIR__);

xoops_load('instruction', $moduleDirName);
$xfHelper = Xmf\Module\Helper::getHelper($moduleDirName);
//
include_once __DIR__ . '/include/functions.php';
// Трей
include_once $GLOBALS['xoops']->path('class/tree.php');

