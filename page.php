<?php

use Xmf\Request;
use Xoopsmodules\instruction;

require_once __DIR__ . '/header.php';
// Подключаем трей
include_once __DIR__ . '/class/Tree.php';

$groups       = ($GLOBALS['xoopsUser'] instanceof \XoopsUser) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
$gpermHandler = xoops_getHandler('groupperm');

// Права на просмотр страницы
// ==========================

// Объявляем объекты
//$instructionHandler = xoops_getModuleHandler('instruction', 'instruction');
//$categoryHandler   = xoops_getModuleHandler('category', 'instruction');
//$pageHandler  = xoops_getModuleHandler('page', 'instruction');

// Получаем данные
// ID страницы
$pageid = Request::getInt('id', 0, 'GET');
// Без кэша
$nocache = Request::getInt('nocache', 0, 'GET');

// Существует ли такая страница
$criteria = new \CriteriaCompo();
$criteria->add(new \Criteria('pageid ', $pageid));
$criteria->add(new \Criteria('status ', '0', '>'));
if (0 == $pageHandler->getCount($criteria)) {
    redirect_header('index.php', 3, _MD_INSTRUCTION_PAGENOTEXIST);
    exit();
}
//
unset($criteria);

// Находим данные о странице
$objInspage = $pageHandler->get($pageid);
// Находим данные об инструкции
$objInsinstr = $instructionHandler->get($objInspage->getVar('instrid'));

// Если админ и ссылка на отключение кэша
if (($GLOBALS['xoopsUser'] instanceof \XoopsUser) && $GLOBALS['xoopsUser']->isAdmin() && $nocache) {
    // Отключаем кэш
    $GLOBALS['xoopsConfig']['module_cache'][$GLOBALS['xoopsModule']->getVar('mid')] = 0;
}

// Задание тайтла
$xoopsOption['xoops_pagetitle'] = $GLOBALS['xoopsModule']->name() . ' - ' . $objInsinstr->getVar('title') . ' - ' . $objInspage->getVar('title');
// Шаблон
$GLOBALS['xoopsOption']['template_main'] = $moduleDirName . '_page.tpl';
// Заголовок
include_once $GLOBALS['xoops']->path('header.php');
// Стили
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $moduleDirName . '/assets/css/style.css');
// Скрипты
$xoTheme->addScript(XOOPS_URL . '/modules/' . $moduleDirName . '/assets/js/tree.js');

// Права на просмотр инструкции
$categories = Xoopsmodules\instruction\Utility::getItemIds();
if (!in_array($objInsinstr->getVar('cid'), $categories)) {
    redirect_header(XOOPS_URL . '/modules/' . $moduleDirName . '/', 3, _NOPERM);
    exit();
}

// Массив данных о странице
$pages = [];
// Название страницы
$pages['title'] = $objInspage->getVar('title');
// ID страницы
$pages['pageid'] = $objInspage->getVar('pageid');
// ID инструкции
$pages['instrid'] = $objInspage->getVar('instrid');
// Основной текст
$pages['hometext'] = $objInspage->getVar('hometext');
// Сноска - массив строк
$footnote = $objInspage->getVar('footnote');
// Если есть сноски
if ($footnote) {
    $pages['footnotes'] = explode('|', $objInspage->getVar('footnote'));
} else {
    $pages['footnotes'] = false;
}
// Мета-теги ключевых слов
$pages['keywords'] = $objInspage->getVar('keywords');
// Мета-теги описания
$pages['description'] = $objInspage->getVar('description');
//
// Если админ, рисуем админлинк
if (($GLOBALS['xoopsUser'] instanceof \XoopsUser) && $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid())) {
    $pages['adminlink'] = '&nbsp;<a href="'
                          . XOOPS_URL
                          . '/modules/'
                          . $moduleDirName
                          . '/admin/instr.php?op=editpage&pageid='
                          . $pages['pageid']
                          . '"><img style="width:16px;" src="./assets/icons/edit_mini.png" alt='
                          . _EDIT
                          . ' title='
                          . _EDIT
                          . '></a>&nbsp;<a href="'
                          . XOOPS_URL
                          . '/modules/'
                          . $moduleDirName
                          . '/admin/instr.php?op=delpage&pageid='
                          . $pages['pageid']
                          . '"><img style="width:16px;" src="./assets/icons/delete_mini.png" alt='
                          . _DELETE
                          . ' title='
                          . _DELETE
                          . '></a>&nbsp;';
} else {
    $pages['adminlink'] = '&nbsp;';
    // Если можно редактировать
    if ($gpermHandler->checkRight($moduleDirName . '_edit', $objInsinstr->getVar('cid'), $groups, $GLOBALS['xoopsModule']->getVar('mid'))) {
        $pages['adminlink'] .= '<a href="' . XOOPS_URL . '/modules/' . $moduleDirName . '/submit.php?op=editpage&pageid=' . $pages['pageid'] . '"><img style="width:16px;" src="./assets/icons/edit_mini.png" alt=' . _EDIT . ' title=' . _EDIT . '></a>';
    }

    $pages['adminlink'] .= '&nbsp;';
    // Если нет админлика
    if ('[&nbsp;&nbsp;]' === $pages['adminlink']) {
        $pages['adminlink'] = '';
    }
}
// Выводим в шаблон
$GLOBALS['xoopsTpl']->assign('insPage', $pages);

// Находим данные об категории
$objInscat = $categoryHandler->get($objInsinstr->getVar('cid'));

// Навигация
$criteria = new \CriteriaCompo();
$criteria->setSort('weight ASC, title');
$criteria->setOrder('ASC');
$inscat_arr    = $categoryHandler->getall($criteria);
$mytree        = new \XoopsObjectTree($inscat_arr, 'cid', 'pid');
$nav_parent_id = $mytree->getAllParent($objInsinstr->getVar('cid'));
$titre_page    = $nav_parent_id;
$nav_parent_id = array_reverse($nav_parent_id);
$navigation    = '<a href="' . XOOPS_URL . '/modules/' . $moduleDirName . '/">' . $GLOBALS['xoopsModule']->name() . '</a>&nbsp;:&nbsp;';
foreach (array_keys($nav_parent_id) as $i) {
    $navigation .= '<a href="' . XOOPS_URL . '/modules/' . $moduleDirName . '/index.php?cid=' . $nav_parent_id[$i]->getVar('cid') . '">' . $nav_parent_id[$i]->getVar('title') . '</a>&nbsp;:&nbsp;';
}
$navigation .= '<a href="' . XOOPS_URL . '/modules/' . $moduleDirName . '/index.php?cid=' . $objInscat->getVar('cid') . '">' . $objInscat->getVar('title') . '</a>&nbsp;:&nbsp;';
$navigation .= '<a href="' . XOOPS_URL . '/modules/' . $moduleDirName . '/instr.php?id=' . $pages['instrid'] . '">' . $objInsinstr->getVar('title') . '</a>';
$xoopsTpl->assign('insNav', $navigation);

unset($criteria);

// Список страниц в данной справке
$criteria = new \CriteriaCompo();
$criteria->add(new \Criteria('instrid', $pages['instrid'], '='));
$criteria->add(new \Criteria('status ', '0', '>'));
$criteria->setSort('weight');
$criteria->setOrder('ASC');
$ins_page = $pageHandler->getall($criteria);
unset($criteria);
// Предыдущая и следующая страницы
$prevpages = [];
$nextpages = [];
// Инициализируем
$instree = new Xoopsmodules\instruction\Tree($ins_page, 'pageid', 'pid');
// Выводим список страниц в шаблон
$GLOBALS['xoopsTpl']->assign('insListPage', $instree->makePagesUser($pageid, $prevpages, $nextpages));
// Выводим в шаблон
$xoopsTpl->assign('insPrevpages', $prevpages);
$xoopsTpl->assign('insNextpages', $nextpages);
// Языковые константы
//$xoopsTpl->assign( 'lang_listpages', _MD_INSTRUCTION_LISTPAGES );
$xoopsTpl->assign('lang_menu', _MD_INSTRUCTION_MENU);

// Рейтинг
if (xoops_getModuleOption('userat', 'instruction')) {
    $xoopsTpl->assign('insUserat', true);
} else {
    $xoopsTpl->assign('insUserat', false);
}

// Мета теги
$xoTheme->addMeta('meta', 'keywords', $objInspage->getVar('keywords'));
$xoTheme->addMeta('meta', 'description', $objInspage->getVar('description'));

// Комментарии
include_once $GLOBALS['xoops']->path('include/comment_view.php');
// Подвал
include_once $GLOBALS['xoops']->path('footer.php');
