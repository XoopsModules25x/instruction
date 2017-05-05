<?php
// Автор: andrey3761

include '../../../include/cp_header.php';
include '../../../class/xoopsformloader.php';
include 'admin_header.php';
//
$aboutAdmin = new ModuleAdmin();

xoops_cp_header();

//$module_info =& $module_handler->get($xoopsModule->getVar("mid"));

$xoopsTpl->assign( 'insNavigation', $aboutAdmin->addNavigation('about.php') );
$xoopsTpl->assign( 'insAbout', $aboutAdmin->renderabout('', false) );

//echo $aboutAdmin->addNavigation('about.php');
//echo $aboutAdmin->renderabout('', false);

// Выводим шаблон
$GLOBALS['xoopsTpl']->display("db:instruction_admin_about.tpl");

include 'admin_footer.php';
//
xoops_cp_footer();

?>