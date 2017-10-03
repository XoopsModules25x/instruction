<?php

use Xmf\Request;

// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

xoops_load('XoopsEditorHandler');
$editorHandler = XoopsEditorHandler::getInstance();
$moduleDirName = basename(__DIR__);
$xoops_url     = parse_url(XOOPS_URL);

$modversion = [
    'version'             => 1.06,
    'module_status'       => 'Beta 3',
    'release_date'        => '2017/05/11',
    'name'                => _MI_INSTRUCTION_NAME,
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
    'modicons16'          => 'assets/images/icons/16',
    'modicons32'          => 'assets/images/icons/32',
    // О модуле
    'module_website_url'  => 'radio-hobby.org',
    'module_website_name' => 'radio-hobby.org',

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
    'hasMain'             => 1,
    // Search
    'hasSearch'           => 1,
    'search'              => [
        'file' => 'include/search.inc.php',
        'func' => $moduleDirName . '_search',
    ],
];
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
$modversion['config'][] = [
    'name'        => 'form_options',
    'title'       => '_MI_INSTRUCTION_FORM_OPTIONS',
    'description' => '_MI_INSTRUCTION_FORM_OPTIONS_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'default'     => 'dhtml',
    'options'     => array_flip($editorHandler->getList())
];
$modversion['config'][] = [
    'name'        => 'perpageadmin',
    'title'       => '_MI_INSTRUCTION_PERPAGEADMIN',
    'description' => '_MI_INSTRUCTION_PERPAGEADMINDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 20
];
$modversion['config'][] = [
    'name'        => 'perpagemain',
    'title'       => '_MI_INSTRUCTION_PERPAGEMAIN',
    'description' => '_MI_INSTRUCTION_PERPAGEMAINDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 20
];
// Теги
$modversion['config'][] = [
    'name'        => 'usetag',
    'title'       => '_MI_INSTRUCTION_USETAG',
    'description' => '_MI_INSTRUCTION_USETAGDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0
];
// Оценки
$modversion['config'][] = [
    'name'        => 'userat',
    'title'       => '_MI_INSTRUCTION_USERAT',
    'description' => '_MI_INSTRUCTION_USERATDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0
];

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
