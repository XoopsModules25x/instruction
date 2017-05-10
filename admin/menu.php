<?php
// Автор: andrey3761

if (!isset($moduleDirName)) {
    $moduleDirName = basename(dirname(__DIR__));
}

if (false !== ($moduleHelper = Xmf\Module\Helper::getHelper($moduleDirName))) {
} else {
    $moduleHelper = Xmf\Module\Helper::getHelper('system');
}
$adminObject = \Xmf\Module\Admin::getInstance();

$pathIcon32    = \Xmf\Module\Admin::menuIconPath('');

$moduleHelper->loadLanguage('modinfo');

$adminmenu = array();

// Административное меню
$i = 1;
$adminmenu[$i]['title'] = _MI_INSTRUCTION_ADMIN_HOME;
$adminmenu[$i]['link'] = "admin/index.php";
$adminmenu[$i]['desc'] = _MI_INSTRUCTION_ADMIN_HOME_DESC;
$adminmenu[$i]["icon"] =  $pathIcon32 . '/home.png';
$i++;
$adminmenu[$i]['title'] = _MI_INSTRUCTION_ADMIN_CAT;
$adminmenu[$i]['link'] = "admin/cat.php";
$adminmenu[$i]['desc'] = _MI_INSTRUCTION_ADMIN_CAT_DESC;
$adminmenu[$i]['icon'] = $pathIcon32 . '/category.png';
$i++;
$adminmenu[$i]['title'] = _MI_INSTRUCTION_ADMIN_INSTR;
$adminmenu[$i]['link'] = "admin/instr.php";
$adminmenu[$i]['desc'] = _MI_INSTRUCTION_ADMIN_INSTR_DESC;
$adminmenu[$i]['icon'] = 'assets/icons/nav_book.png';
$i++;
$adminmenu[$i]['title'] = _MI_INSTRUCTION_ADMIN_PERM;
$adminmenu[$i]['link'] = "admin/perm.php";
$adminmenu[$i]['desc'] = _MI_INSTRUCTION_ADMIN_PERM_DESC;
$adminmenu[$i]['icon'] = $pathIcon32 . '/permissions.png';
$i++;
$adminmenu[$i]['title'] = _MI_INSTRUCTION_ADMIN_ABOUT;
$adminmenu[$i]['link']  = "admin/about.php";
$adminmenu[$i]['desc'] = _MI_INSTRUCTION_ADMIN_ABOUT_DESC;
$adminmenu[$i]['icon'] =  $pathIcon32 . '/about.png';
$i++;
