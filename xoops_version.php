<?php

use Xmf\Request;

// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

$moduleDirName = basename(__DIR__);
xoops_load('xoopseditorhandler');
$editorHandler = XoopsEditorHandler::getInstance();
$xoops_url     = parse_url(XOOPS_URL);

$modversion = [
    'name'                => _MI_INSTRUCTION_NAME,
    'version'             => 1.06,
    'description'         => _MI_INSTRUCTION_DESC,
    'credits'             => 'radio-hobby.org, www.shmel.org',
    'author'              => 'andrey3761, aerograf',
    'nickname'            => '',
    'help'                => 'page=help',
    'license'             => 'GNU GPL 2.0',
    'license_url'         => 'www.gnu.org/licenses/gpl-2.0.html/',
    'official'            => 0,
    'image'               => 'assets/images/slogo.png',
    'dirname'             => $moduleDirName,
    'dirmoduleadmin'      => 'Frameworks/moduleclasses',
    'icons16'             => 'Frameworks/moduleclasses/icons/16',
    'icons32'             => 'Frameworks/moduleclasses/icons/32',
    // О модуле
    'module_website_url'  => 'radio-hobby.org',
    'module_website_name' => 'radio-hobby.org',
    'release_date'        => '2017/05/11',
    'module_status'       => 'Beta 2',
    'author_website_url'  => 'radio-hobby.org',
    'author_website_name' => 'andrey3761',
    'module_website_url'  => 'www.xoops.org',
    'module_website_name' => 'Support site',
    'min_php'             => '5.5',
    'min_xoops'           => '2.5.8',
    'min_admin'           => '1.1',
    'min_db'              => ['mysql' => '5.5'],
    // Файл базы данных
    'sqlfile'             => ['mysql' => 'sql/mysql.sql'],
    // Таблицы
    'tables'              => [
        $moduleDirName . '_cat',
        $moduleDirName . '_instr',
        $moduleDirName . '_page'
    ],
    // Имеет админку
    'hasAdmin'            => 1,
    'adminindex'          => 'admin/index.php',
    'adminmenu'           => 'admin/menu.php',
    'system_menu'         => 1,
    // Меню
    'hasMain'             => 1
];

// Search
$modversion['hasSearch']      = 1;
$modversion['search']['file'] = 'include/search.inc.php';
$modversion['search']['func'] = $moduleDirName . '_search';

//  Help files
$modversion['helpsection'] = [
    ['name' => _MI_INSTRUCTION_HELP_OVERVIEW, 'link' => 'page=help'],
    ['name' => _MI_INSTRUCTION_DISCLAIMER, 'link' => 'page=disclaimer'],
    ['name' => _MI_INSTRUCTION_LICENSE, 'link' => 'page=license'],
    ['name' => _MI_INSTRUCTION_SUPPORT, 'link' => 'page=support'],
];

// Comments
$modversion['hasComments']                     = 1;
$modversion['comments']['itemName']            = 'id';
$modversion['comments']['pageName']            = 'page.php';
$modversion['comments']['callbackFile']        = 'include/comment_functions.php';
$modversion['comments']['callback']['approve'] = $moduleDirName . '_com_approve';
$modversion['comments']['callback']['update']  = $moduleDirName . '_com_update';

// Templates
$modversion['templates'] = [
    [
        'file'        => 'admin/' . $moduleDirName . '_admin_index.tpl',
        'description' => ''
    ],
    [
        'file'        => 'admin/' . $moduleDirName . '_admin_cat.tpl',
        'description' => ''
    ],
    [
        'file'        => 'admin/' . $moduleDirName . '_admin_editcat.tpl',
        'description' => ''
    ],
    [
        'file'        => 'admin/' . $moduleDirName . '_admin_savecat.tpl',
        'description' => ''
    ],
    [
        'file'        => 'admin/' . $moduleDirName . '_admin_viewcat.tpl',
        'description' => ''
    ],
    [
        'file'        => 'admin/' . $moduleDirName . '_admin_instr.tpl',
        'description' => ''
    ],
    [
        'file'        => 'admin/' . $moduleDirName . '_admin_editinstr.tpl',
        'description' => ''
    ],
    [
        'file'        => 'admin/' . $moduleDirName . '_admin_saveinstr.tpl',
        'description' => ''
    ],
    [
        'file'        => 'admin/' . $moduleDirName . '_admin_viewinstr.tpl',
        'description' => ''
    ],
    [
        'file'        => 'admin/' . $moduleDirName . '_admin_editpage.tpl',
        'description' => ''
    ],
    [
        'file'        => 'admin/' . $moduleDirName . '_admin_savepage.tpl',
        'description' => ''
    ],
    [
        'file'        => 'admin/' . $moduleDirName . '_admin_perm.tpl',
        'description' => ''
    ],
    [
        'file'        => 'admin/' . $moduleDirName . '_admin_about.tpl',
        'description' => ''
    ],
    [
        'file'        => $moduleDirName . '_page.tpl',
        'description' => ''
    ],
    [
        'file'        => $moduleDirName . '_instr.tpl',
        'description' => ''
    ],
    [
        'file'        => $moduleDirName . '_index.tpl',
        'description' => ''
    ],
    [
        'file'        => $moduleDirName . '_editpage.tpl',
        'description' => ''
    ],
    [
        'file'        => $moduleDirName . '_savepage.tpl',
        'description' => ''
    ],
];
// Конфигурация
$i = 1;

$modversion['config'][$i]['name']        = 'form_options';
$modversion['config'][$i]['title']       = '_MI_INSTRUCTION_FORM_OPTIONS';
$modversion['config'][$i]['description'] = '_MI_INSTRUCTION_FORM_OPTIONS_DESC';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = 'dhtml';
xoops_load('xoopseditorhandler');
$editor_handler                      = XoopsEditorHandler::getInstance();
$modversion['config'][$i]['options'] = array_flip($editor_handler->getList());
$i++;
//
$modversion['config'][$i]['name']        = 'perpageadmin';
$modversion['config'][$i]['title']       = '_MI_INSTRUCTION_PERPAGEADMIN';
$modversion['config'][$i]['description'] = '_MI_INSTRUCTION_PERPAGEADMINDSC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 20;
$i++;
//
$modversion['config'][$i]['name']        = 'perpagemain';
$modversion['config'][$i]['title']       = '_MI_INSTRUCTION_PERPAGEMAIN';
$modversion['config'][$i]['description'] = '_MI_INSTRUCTION_PERPAGEMAINDSC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 20;
$i++;
// Теги
$modversion['config'][$i]['name']        = 'usetag';
$modversion['config'][$i]['title']       = '_MI_INSTRUCTION_USETAG';
$modversion['config'][$i]['description'] = '_MI_INSTRUCTION_USETAGDSC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 0;
$i++;
// Оценки
$modversion['config'][$i]['name']        = 'userat';
$modversion['config'][$i]['title']       = '_MI_INSTRUCTION_USERAT';
$modversion['config'][$i]['description'] = '_MI_INSTRUCTION_USERATDSC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 0;
$i++;

// Блоки
// Блок последних страниц
$modversion['blocks'][] = [
    'file'        => 'instr_lastpage.php',
    'name'        => _MI_INSTR_BLOCK_LASTPAGE,
    'description' => _MI_INSTR_BLOCK_LASTPAGE_DESC,
    'show_func'   => 'b_instr_lastpage_show',
    'edit_func'   => 'b_instr_lastpage_edit',
    'options'     => '10|20',
    'template'    => $moduleDirName . '_block_lastpage.tpl'
];
// Блок последних инструкций
$modversion['blocks'][] = [
    'file'        => 'instr_lastinstr.php',
    'name'        => _MI_INSTR_BLOCK_LASTINSTR,
    'description' => _MI_INSTR_BLOCK_LASTINSTR_DESC,
    'show_func'   => 'b_instr_lastinstr_show',
    'edit_func'   => 'b_instr_lastinstr_edit',
    'options'     => '10|20',
    'template'    => $moduleDirName . '_block_lastinstr.tpl'
];

// Notification
$modversion['hasNotification'] = 0;
