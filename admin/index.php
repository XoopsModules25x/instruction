<?php
// Автор: andrey3761

require_once '../../../include/cp_header.php';
include 'admin_header.php';
// Admin GUI
$indexAdmin = new ModuleAdmin();

xoops_cp_header();

$xoopsTpl->assign( 'insNavigation', $indexAdmin->addNavigation('index.php') );
$xoopsTpl->assign( 'insIndex', $indexAdmin->renderIndex() );

// Выводим шаблон
$GLOBALS['xoopsTpl']->display("db:instruction_admin_index.tpl");

include "admin_footer.php";
//
xoops_cp_footer();

?>