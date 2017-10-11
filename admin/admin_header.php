<?php

use Xoopsmodules\instruction;

// Автор: andrey3761
$moduleDirName = basename(dirname(__DIR__));
require_once __DIR__ . '/../../../include/cp_header.php';
require_once __DIR__ . '/../include/common.php';

$myts = \MyTextSanitizer::getInstance();
$db   = \XoopsDatabaseFactory::getDatabase();

//if (($GLOBALS['xoopsUser'] instanceof \XoopsUser)) {
if ($GLOBALS['xoopsUser'] instanceof \XoopsUser) {
    if (!$helper->isUserAdmin()) {
        $helper->redirect(XOOPS_URL . '/', 3, _NOPERM);
    }
} else {
    $helper->redirect(XOOPS_URL . '/user.php', 1, _NOPERM);
}

/** @var Xmf\Module\Admin $adminObject */
$adminObject = \Xmf\Module\Admin::getInstance();

if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof \XoopsTpl)) {
    require_once $GLOBALS['xoops']->path('class/template.php');
    $xoopsTpl = new \XoopsTpl();
}

$pathIcon16    = \Xmf\Module\Admin::iconUrl('', 16);
$pathIcon32    = \Xmf\Module\Admin::iconUrl('', 32);
$pathModIcon32 = $helper->getModule()->getInfo('modicons32');


// Load language files
$helper->loadLanguage('admin');
$helper->loadLanguage('modinfo');
//$helper->loadLanguage('main');
