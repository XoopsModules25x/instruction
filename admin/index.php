<?php
// Автор: andrey3761

include __DIR__ . '/../../../include/cp_header.php';
include __DIR__ . '/admin_header.php';
// Admin GUI
$indexAdmin = new ModuleAdmin();

xoops_cp_header();

$xoopsTpl->assign( 'insNavigation', $indexAdmin->addNavigation('index.php') );
$xoopsTpl->assign( 'insIndex', $indexAdmin->renderIndex() );

// Выводим шаблон
$GLOBALS['xoopsTpl']->display("db:instruction_admin_index.tpl");

include __DIR__ . '/admin_footer.php';
//
xoops_cp_footer();

?>