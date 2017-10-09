<?php

use Xoopsmodules\instruction;

include __DIR__ . '/../../../include/cp_header.php';
include __DIR__ . '/admin_header.php';
// Admin GUI
xoops_cp_header();

$adminObject = \Xmf\Module\Admin::getInstance();

$configurator = include __DIR__ . '/../include/config.php';
foreach (array_keys($configurator->uploadFolders) as $i) {
    $utility::createFolder($configurator->uploadFolders[$i]);
    $adminObject->addConfigBoxLine($configurator->uploadFolders[$i], 'folder');
}

//$instructionHandler = xoops_getModuleHandler('instruction', 'instruction');
//$categoryHandler   = xoops_getModuleHandler('category', 'instruction');
//$pageHandler  = xoops_getModuleHandler('page', 'instruction');

$criteria      = new \CriteriaCompo();
$numrows_instr = $instructionHandler->getCount($criteria);
$numrows_cat   = $categoryHandler->getCount($criteria);
$numrows_page  = $pageHandler->getCount($criteria);
$adminObject->addInfoBox(_AM_INSTRUCTION_TOTAL);
$adminObject->addInfoBoxLine('<infolabel>' . '<a href="cat.php">' . _AM_INSTRUCTION_TOTAL_CAT . '</a>' . '&nbsp;&mdash;&nbsp;<span class="green">' . $numrows_cat . '</span></infolabel>', '', '');
$adminObject->addInfoBoxLine('<infolabel>' . '<a href="instr.php">' . _AM_INSTRUCTION_TOTAL_INSTR . '</a>' . '&nbsp;&mdash;&nbsp;<span class="green">' . $numrows_instr . '</span></infolabel>', '', '');
$adminObject->addInfoBoxLine('<infolabel>' . '<a href="instr.php">' . _AM_INSTRUCTION_TOTAL_PAGE . '</a>' . '&nbsp;&mdash;&nbsp;<span class="green">' . $numrows_page . '</span></infolabel>', '', '');

$adminObject->displayNavigation(basename(__FILE__));
$adminObject->displayIndex();

echo $utility::getServerStats();

include __DIR__ . '/admin_footer.php';
