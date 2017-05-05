<?php
// andrey3761

if (!defined('XOOPS_ROOT_PATH')) {
	die('XOOPS root path not defined');	
}
$moduleDirName = basename( dirname( __FILE__ ) ) ;

$modversion['name'] = _MI_INSTRUCTION_NAME;
$modversion['version'] = 1.05;
$modversion['description'] = _MI_INSTRUCTION_DESC;
$modversion['credits'] = "radio-hobby.org";
$modversion['author'] = 'andrey3761';
$modversion['nickname'] = '';
//$modversion['help'] = 'page=help';
$modversion['license'] = 'GNU GPL 2.0';
$modversion['license_url'] = "www.gnu.org/licenses/gpl-2.0.html/";
$modversion['official'] = 0;
//$modversion['image'] = _MI_INSTRUCTION_IMAGE;
$modversion['image'] = 'images/slogo.png';
$modversion['dirname'] = "instruction";
$modversion['dirmoduleadmin'] = 'Frameworks/moduleclasses';
$modversion['icons16'] = 'Frameworks/moduleclasses/icons/16';
$modversion['icons32'] = 'Frameworks/moduleclasses/icons/32';

// О модуле
$modversion["module_website_url"] = "radio-hobby.org";
$modversion["module_website_name"] = "radio-hobby.org";
$modversion["release_date"] = "2012/04/19";
$modversion["module_status"] = "Demo";
$modversion["author_website_url"] = "radio-hobby.org";
$modversion["author_website_name"] = "andrey3761";
$modversion['min_php']='5.2';
$modversion['min_xoops']="2.5";

// Файл базы данных
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
//$modversion['sqlfile']['postgresql'] = "sql/pgsql.sql";

// Таблицы
$i = 0;
$modversion['tables'][$i] = "instruction_cat";
$i++;
$modversion['tables'][$i] = "instruction_instr";
$i++;
$modversion['tables'][$i] = "instruction_page";
$i++;

// Имеет админку
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";
$modversion['system_menu'] = 1;

// Меню
$modversion['hasMain'] = 1;

// Search
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = "include/search.inc.php";
$modversion['search']['func'] = "instruction_search";

// Comments
$modversion['hasComments'] = 1;
$modversion['comments']['itemName'] = 'id';
$modversion['comments']['pageName'] = 'page.php';
//$modversion['comments']['extraParams'] = array('cid');
$modversion['comments']['callbackFile'] = 'include/comment_functions.php';
$modversion['comments']['callback']['approve'] = 'instruction_com_approve';
$modversion['comments']['callback']['update'] = 'instruction_com_update';

// Templates
$i = 1;
$modversion['templates'][$i]['file'] = 'instruction_admin_index.tpl';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'instruction_admin_cat.tpl';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'instruction_admin_editcat.tpl';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'instruction_admin_savecat.tpl';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'instruction_admin_viewcat.tpl';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'instruction_admin_instr.tpl';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'instruction_admin_editinstr.tpl';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'instruction_admin_saveinstr.tpl';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'instruction_admin_viewinstr.tpl';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'instruction_admin_editpage.tpl';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'instruction_admin_savepage.tpl';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'instruction_page.tpl';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'instruction_instr.tpl';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'instruction_index.tpl';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'instruction_editpage.tpl';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'instruction_savepage.tpl';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'instruction_admin_perm.tpl';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'instruction_admin_about.tpl';
$modversion['templates'][$i]['description'] = '';
$i++;

// Конфигурация
$i = 1;

$modversion['config'][$i]['name'] = 'form_options';
$modversion['config'][$i]['title'] = "_MI_INSTRUCTION_FORM_OPTIONS";
$modversion['config'][$i]['description'] = '_MI_INSTRUCTION_FORM_OPTIONS_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'dhtml';
xoops_load('xoopseditorhandler');
$editor_handler = XoopsEditorHandler::getInstance();
$modversion['config'][$i]['options'] = array_flip($editor_handler->getList());
$i++;
//
$modversion['config'][$i]['name'] = 'perpageadmin';
$modversion['config'][$i]['title'] = '_MI_INSTRUCTION_PERPAGEADMIN';
$modversion['config'][$i]['description'] = '_MI_INSTRUCTION_PERPAGEADMINDSC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 20;
$i++;
//
$modversion['config'][$i]['name'] = 'perpagemain';
$modversion['config'][$i]['title'] = '_MI_INSTRUCTION_PERPAGEMAIN';
$modversion['config'][$i]['description'] = '_MI_INSTRUCTION_PERPAGEMAINDSC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 20;
$i++;
// Теги
$modversion['config'][$i]['name'] = 'usetag';
$modversion['config'][$i]['title'] = '_MI_INSTRUCTION_USETAG';
$modversion['config'][$i]['description'] = '_MI_INSTRUCTION_USETAGDSC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 0;
$i++;
// Оценки
$modversion['config'][$i]['name'] = 'userat';
$modversion['config'][$i]['title'] = '_MI_INSTRUCTION_USERAT';
$modversion['config'][$i]['description'] = '_MI_INSTRUCTION_USERATDSC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 0;
$i++;

// Блоки
$i = 1;
// Блок последних страниц
$modversion['blocks'][$i]['file'] = "instr_lastpage.php";
$modversion['blocks'][$i]['name'] = _MI_INSTR_BLOCK_LASTPAGE;
$modversion['blocks'][$i]['description'] = _MI_INSTR_BLOCK_LASTPAGE_DESC;
$modversion['blocks'][$i]['show_func'] = "b_instr_lastpage_show";
$modversion['blocks'][$i]['edit_func']   = 'b_instr_lastpage_edit';
$modversion['blocks'][$i]['options']     = '10|20';
$modversion['blocks'][$i]['template'] = 'instruction_block_lastpage.tpl';
$i ++;
// Блок последних инструкций
$modversion['blocks'][$i]['file'] = "instr_lastinstr.php";
$modversion['blocks'][$i]['name'] = _MI_INSTR_BLOCK_LASTINSTR;
$modversion['blocks'][$i]['description'] = _MI_INSTR_BLOCK_LASTINSTR_DESC;
$modversion['blocks'][$i]['show_func'] = "b_instr_lastinstr_show";
$modversion['blocks'][$i]['edit_func']   = 'b_instr_lastinstr_edit';
$modversion['blocks'][$i]['options']     = '10|20';
$modversion['blocks'][$i]['template'] = 'instruction_block_lastinstr.tpl';
$i ++;

// Notification
$modversion['hasNotification'] = 0;

?>