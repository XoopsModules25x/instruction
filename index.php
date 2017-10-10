<?php

use Xmf\Request;
use Xoopsmodules\instruction;

require_once __DIR__ . '/header.php';

// Пагинатор
include_once $GLOBALS['xoops']->path('class/pagenav.php');

// Задание тайтла
$xoopsOption['xoops_pagetitle'] = $GLOBALS['xoopsModule']->name();
// Шаблон
$GLOBALS['xoopsOption']['template_main'] = $moduleDirName . '_index.tpl';
// Заголовок
include_once $GLOBALS['xoops']->path('header.php');
// Стили
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $moduleDirName . '/assets/css/style.css');
//
$cid = Request::getInt('cid', 0, 'GET');
//
$start = Request::getInt('start', 0, 'GET');
//

//mb $limit = xoops_getModuleOption('perpagemain', 'instruction');
$limit = xoops_getModuleOption('perpagemain', 'instruction');

// Права на просмотр
$categories = Xoopsmodules\instruction\Utility::getItemIds();
// Права на добавление
$cat_submit = Xoopsmodules\instruction\Utility::getItemIds($moduleDirName . '_submit');
// Права на редактирование
$cat_edit = Xoopsmodules\instruction\Utility::getItemIds($moduleDirName . '_edit');

// Находим список категорий
$criteria = new \CriteriaCompo();
$criteria->add(new \Criteria('cid', '( ' . implode(', ', $categories) . ' )', 'IN'));
$criteria->setSort('weight ASC, title');
$criteria->setOrder('ASC');
$inscat_arr = $categoryHandler->getall($criteria);
unset($criteria);
$mytree = new \XoopsObjectTree($inscat_arr, 'cid', 'pid');
// Выводим в шаблон
//$GLOBALS['xoopsTpl']->assign('insFormSelCat', $mytree->makeSelBox('cid', 'title', '--', $cid, true, 0, "onChange='javascript: document.insformselcat.submit()'"));
$moduleDirName = basename(__DIR__);

//$helper = \Helper::getInstance();
$module = $helper->getModule();

if (Xoopsmodules\instruction\Utility::checkVerXoops($module, '2.5.9')) {
    $cat_select = $mytree->makeSelectElement('cid', 'title', '--', $cid, true, 0, "onChange='javascript: document.insformselcat.submit()'", '');
    $GLOBALS['xoopsTpl']->assign('insFormSelCat', $cat_select->render());
} else {
    $cat_select = $mytree->makeSelBox('cid', 'title', '--', $cid, true, 0, "onChange='javascript: document.insformselcat.submit()'");
    $GLOBALS['xoopsTpl']->assign('insFormSelCat', $cat_select);
}

// Находим список всех инструкций
// Критерий выборки
$criteria = new \CriteriaCompo();
// Все активные
$criteria->add(new \Criteria('status', '0', '>'));
// Если есть категория
if ($cid) {
    // Если нельзя просматривать эту категорию
    if (!in_array($cid, $categories)) {
        redirect_header('index.php', 3, _MD_INSTRUCTION_NOPERM_CAT);
    }
    $criteria->add(new \Criteria('cid', $cid, '='));
    // Иначе находим список всех
} else {
    $criteria->add(new \Criteria('cid', '( ' . implode(', ', $categories) . ' )', 'IN'));
}

// Число инструкций, удовлетворяющих данному условию
$numrows = $instructionHandler->getCount($criteria);
// Число выборки
$criteria->setLimit($limit);
// Начинасть с данного элемента
$criteria->setStart($start);
// Сортировать по
$criteria->setSort('instrid');
// Порядок сортировки
$criteria->setOrder('DESC');
// Находим все инструкции
$instr_arr = $instructionHandler->getall($criteria);
// Если записей больше чем $limit, то выводим пагинатор
if ($numrows > $limit) {
    $pagenav = new \XoopsPageNav($numrows, $limit, $start, 'start', 'cid=' . $cid);
    $pagenav = $pagenav->renderNav(4);
} else {
    $pagenav = '';
}
// Выводим пагинатор в шаблон
$GLOBALS['xoopsTpl']->assign('insPagenav', $pagenav);

// Мета-теги страницы
$index_metakeywords = [];
$index_metadescript = [];

// Если есть записи
if ($numrows > 0) {
    $class = 'odd';
    foreach (array_keys($instr_arr) as $i) {

        //
        $class = ('even' === $class) ? 'odd' : 'even';
        // ID
        $insinstr_instrid = $instr_arr[$i]->getVar('instrid');
        // Название
        $insinstr_title = $instr_arr[$i]->getVar('title');
        // Статус
        $insinstr_status = $instr_arr[$i]->getVar('status');
        // Количество страниц
        $insinstr_pages = $instr_arr[$i]->getVar('pages');
        // Категория
        $insinstr_cid = $instr_arr[$i]->getVar('cid');
        $insinstr_cat = $categoryHandler->get($insinstr_cid);
        // Права на добавление
        $perm_submit = in_array($insinstr_cid, $cat_submit) ? true : false;
        // Права на редактирование
        $perm_edit = in_array($insinstr_cid, $cat_edit) ? true : false;
        //Мета-теги ключевых слов
        $insinstr_metakeywords = $instr_arr[$i]->getVar('metakeywords');
        // Если есть - добавляем в мета-теги страницы
        if ($insinstr_metakeywords) {
            $index_metakeywords[] = $insinstr_metakeywords;
        }
        // Мета-теги описания
        $insinstr_metadescript = $instr_arr[$i]->getVar('metadescription');
        // Если есть - добавляем в мета-теги страницы
        if ($insinstr_metadescript) {
            $index_metadescript[] = $insinstr_metadescript;
        }

        // Выводим в шаблон
        $GLOBALS['xoopsTpl']->append('insListInstr',
                                     ['instrid' => $insinstr_instrid, 'title' => $insinstr_title, 'status' => $insinstr_status, 'pages' => $insinstr_pages, 'ctitle' => $insinstr_cat->getVar('title'), 'cid' => $insinstr_cid, 'permsubmit' => $perm_submit, 'permedit' => $perm_edit, 'class' => $class]
        );
    }

    // Языковые константы
}

// Если есть мета-теги
if (count($index_metakeywords)) {
    $xoTheme->addMeta('meta', 'keywords', implode(', ', $index_metakeywords));
}
if (count($index_metadescript)) {
    $xoTheme->addMeta('meta', 'description', implode(', ', $index_metadescript));
}

// Подвал
include_once $GLOBALS['xoops']->path('footer.php');
