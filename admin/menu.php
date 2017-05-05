<?php
// Автор: andrey3761

$module_handler = xoops_gethandler('module');
$xoopsModule = XoopsModule::getByDirname('instruction');
$moduleInfo = $module_handler->get($xoopsModule->getVar('mid'));
//$pathImageAdmin = XOOPS_URL .'/'. $moduleInfo->getInfo('dirmoduleadmin').'/images/admin';
$pathImageAdmin = $moduleInfo->getInfo('icons32');

$adminmenu = array();

// Административное меню
$i = 1;
$adminmenu[$i]['title'] = _MI_INSTRUCTION_ADMIN_HOME;
$adminmenu[$i]['link'] = "admin/index.php";
$adminmenu[$i]['desc'] = _MI_INSTRUCTION_ADMIN_HOME_DESC;
$adminmenu[$i]["icon"] =  '../../' . $pathImageAdmin . '/home.png';
$i++;
$adminmenu[$i]['title'] = _MI_INSTRUCTION_ADMIN_CAT;
$adminmenu[$i]['link'] = "admin/cat.php";
$adminmenu[$i]['desc'] = _MI_INSTRUCTION_ADMIN_CAT_DESC;
$adminmenu[$i]['icon'] = '../../' . $pathImageAdmin . '/category.png';
$i++;
$adminmenu[$i]['title'] = _MI_INSTRUCTION_ADMIN_INSTR;
$adminmenu[$i]['link'] = "admin/instr.php";
$adminmenu[$i]['desc'] = _MI_INSTRUCTION_ADMIN_INSTR_DESC;
$adminmenu[$i]['icon'] = 'images/icons/nav_book.png';
$i++;
$adminmenu[$i]['title'] = _MI_INSTRUCTION_ADMIN_PERM;
$adminmenu[$i]['link'] = "admin/perm.php";
$adminmenu[$i]['desc'] = _MI_INSTRUCTION_ADMIN_PERM_DESC;
$adminmenu[$i]['icon'] = '../../' . $pathImageAdmin . '/permissions.png';
$i++;
$adminmenu[$i]['title'] = _MI_INSTRUCTION_ADMIN_ABOUT;
$adminmenu[$i]['link']  = "admin/about.php";
$adminmenu[$i]['desc'] = _MI_INSTRUCTION_ADMIN_ABOUT_DESC;
$adminmenu[$i]['icon'] =  '../../' . $pathImageAdmin . '/about.png';
$i++;

?>