<?php
namespace XoopsModules\Instruction;
use Xmf\Request;
use XoopsModules\Instruction;

//
include __DIR__ . '/admin_header.php';
// Функции модуля
//include dirname(__DIR__) . '/class/Utility.php';
// Пагинатор
include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
require_once __DIR__ . '/../include/common.php';

// Admin Gui
$adminObject = \Xmf\Module\Admin::getInstance();
// Объявляем объекты
//$instructionHandler = xoops_getModuleHandler('instruction', 'instruction');
//$categoryHandler   = xoops_getModuleHandler('category', 'instruction');
//$pageHandler  = xoops_getModuleHandler('page', 'instruction');

$uid  = ($GLOBALS['xoopsUser'] instanceof \XoopsUser) ? $GLOBALS['xoopsUser']->getVar('uid') : 0;
$time = time();

// ID инструкции
$instrid = Request::getInt('instrid', 0);
// ID страницы
$pageid = Request::getInt('pageid', 0);
// ID категории
$cid = Request::getInt('cid', 0);
// Вес
$weight = Request::getInt('weight', 0, 'POST');
//
$pid = Request::getInt('pid', 0);
//
$start = Request::getInt('start', 0, 'GET');
//
$limit = xoops_getModuleOption('perpageadmin', 'instruction');

$op = Request::getString('op', Request::getString('op', 'main', 'GET'), 'POST');

// Выбор
switch ($op) {

    case 'main':

        // Заголовок админки
        xoops_cp_header();
        // Меню
        $adminObject->displayNavigation(basename(__FILE__));
        $adminObject->addItemButton(_AM_INSTRUCTION_ADDINSTR, 'instr.php?op=editinstr', 'add');
        $adminObject->displayButton('left', '');

        //
        $criteria = new \CriteriaCompo();

        // Если была передана категория
        if ($cid) {
            // Добавляем в выборку ID категории
            $criteria->add(new \Criteria('cid', $cid, '='));
            // Получаем объект категории
            $objInscat = $categoryHandler->get($cid);
            // Если нет такой категории
            if (!is_object($objInscat)) {
                redirect_header('cat.php', 3, _AM_INSTRUCTION_ERR_CATNOTSELECT);
            }
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
        // Находим все справки
        $instr_arr = $instructionHandler->getall($criteria);
        // Если записей больше чем $limit, то выводим пагинатор
        if ($numrows > $limit) {
            $pagenav = new \XoopsPageNav($numrows, $limit, $start, 'start', 'op=' . $op . '&amp;cid=' . $cid);
            $pagenav = $pagenav->renderNav(4);
        } else {
            $pagenav = '';
        }
        // Выводим пагинатор в шаблон
        $GLOBALS['xoopsTpl']->assign('insPagenav', $pagenav);

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
                $insinstr_cat = $categoryHandler->get($instr_arr[$i]->getVar('cid'));

                // Выводим в шаблон
                $GLOBALS['xoopsTpl']->append('insListInstr', ['instrid' => $insinstr_instrid, 'title' => $insinstr_title, 'status' => $insinstr_status, 'pages' => $insinstr_pages, 'ctitle' => $insinstr_cat->getVar('title'), 'cid' => $insinstr_cat->getVar('cid'), 'class' => $class]);
            }

            //
            $inshead = isset($objInscat) && is_object($objInscat) ? sprintf(_AM_INSTR_LISTINSTRINCAT, $objInscat->getVar('title')) : _AM_INSTR_LISTINSTRALL;
            $GLOBALS['xoopsTpl']->assign('insHead', $inshead);
            // Языковые константы
            $GLOBALS['xoopsTpl']->assign('lang_title', _AM_INSTRUCTION_TITLE);
            $GLOBALS['xoopsTpl']->assign('lang_cat', _AM_INSTRUCTION_CAT);
            $GLOBALS['xoopsTpl']->assign('lang_pages', _AM_INSTRUCTION_PAGES);
            $GLOBALS['xoopsTpl']->assign('lang_action', _AM_INSTRUCTION_ACTION);
            $GLOBALS['xoopsTpl']->assign('lang_display', _AM_INSTRUCTION_DISPLAY);
            $GLOBALS['xoopsTpl']->assign('lang_edit', _AM_INSTRUCTION_EDIT);
            $GLOBALS['xoopsTpl']->assign('lang_del', _AM_INSTRUCTION_DEL);
            $GLOBALS['xoopsTpl']->assign('lang_lock', _AM_INSTRUCTION_LOCK);
            $GLOBALS['xoopsTpl']->assign('lang_unlock', _AM_INSTRUCTION_UNLOCK);
            $GLOBALS['xoopsTpl']->assign('lang_addpage', _AM_INSTRUCTION_ADDPAGE);
            $GLOBALS['xoopsTpl']->assign('lang_addinstr', _AM_INSTRUCTION_ADDINSTR);
        }

        // Выводим шаблон
        $GLOBALS['xoopsTpl']->display('db:admin/instruction_admin_instr.tpl');

        // Текст внизу админки
        include __DIR__ . '/admin_footer.php';

        break;

    // Редактирование категории
    case 'editinstr':

        // Заголовок админки
        xoops_cp_header();
        // Меню
        $adminObject->displayNavigation(basename(__FILE__));

        // Если мы редактируем инструкцию
        if ($instrid) {
            $objInsinstr = $instructionHandler->get($instrid);
            // Создание новой страницы
        } else {
            $objInsinstr = $instructionHandler->create();
        }

        // Выводим шаблон
        $GLOBALS['xoopsTpl']->display('db:admin/instruction_admin_editinstr.tpl');
        $form = $objInsinstr->getForm('instr.php');
        // Форма
        echo $form->render();

        // Текст внизу админки
        include __DIR__ . '/admin_footer.php';

        break;

    // Сохранение инструкций
    case 'saveinstr':

        // Проверка
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('instr.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        // Если мы редактируем
        if ($instrid) {
            $objInsinstr = $instructionHandler->get($instrid);
        } else {
            $objInsinstr = $instructionHandler->create();
            // Указываем дату создания
            $objInsinstr->setVar('datecreated', $time);
            // Указываем пользователя
            $objInsinstr->setVar('uid', $uid);
        }

        $err         = false;
        $message_err = '';
        //
        $instr_title       = Request::getString('title', '', 'POST');
        $instr_description = Request::getText('description', '', 'POST');

        // Дата обновления
        $objInsinstr->setVar('dateupdated', $time);
        //
        $objInsinstr->setVar('cid', $cid);
        $objInsinstr->setVar('title', $instr_title);
        $objInsinstr->setVar('status', Request::getInt('status', 0));
        $objInsinstr->setVar('description', $instr_description);
        $objInsinstr->setVar('metakeywords', Request::getString('metakeywords', ''));
        $objInsinstr->setVar('metadescription', Request::getString('metadescription', ''));

        // Проверка категорий
        if (!$cid) {
            $err         = true;
            $message_err .= _AM_INSTRUCTION_ERR_CAT . '<br>';
        }
        // Проверка названия
        if (!$instr_title) {
            $err         = true;
            $message_err .= _AM_INSTR_ERR_TITLE . '<br>';
        }
        // Проверка основного текста
        if (!$instr_description) {
            $err         = true;
            $message_err .= _AM_INSTR_ERR_DESCRIPTION . '<br>';
        }

        // Если были ошибки
        if (true === $err) {
            xoops_cp_header();
            // Меню страницы
            $adminObject->displayNavigation(basename(__FILE__));

            $message_err = '<div class="errorMsg" style="text-align: left;">' . $message_err . '</div>';
            // Выводим ошибки в шаблон
            $GLOBALS['xoopsTpl']->assign('insErrorMsg', $message_err);
            // Если небыло ошибок
        } else {
            // Вставляем данные в БД
            if ($instructionHandler->insert($objInsinstr)) {
                // Получаем ID созданной записи
                $instrid_new = $instrid ?: $objInsinstr->getNewInstertId();
                // Обновление даты в категории
                $categoryHandler->updateDateupdated($cid, $time);
                // Тэги
                if (xoops_getModuleOption('usetag', 'instruction')) {
                    $tagHandler = xoops_getModuleHandler('tag', 'tag');
                    $tagHandler->updateByItem($_POST['tag'], $instrid_new, $GLOBALS['xoopsModule']->getVar('dirname'), 0);
                }

                // Если мы редактируем
                if ($instrid) {
                    redirect_header('instr.php', 3, _AM_INSTRUCTION_INSTRMODIFY);
                } else {
                    redirect_header('instr.php', 3, _AM_INSTRUCTION_INSTRADDED);
                }
            }
            xoops_cp_header();
            // Меню страницы
            $adminObject->displayNavigation(basename(__FILE__));

            // Выводим ошибки в шаблон
            $GLOBALS['xoopsTpl']->assign('insErrorMsg', $objInstructioncat->getHtmlErrors());
        }
        // Выводим шаблон
        $GLOBALS['xoopsTpl']->display('db:admin/instruction_admin_saveinstr.tpl');
        // Выводим форму
        $form = $objInsinstr->getForm();
        // Форма
        echo $form->render();
        // Текст внизу админки
        include __DIR__ . '/admin_footer.php';

        break;

    // Просмотр категории
    case 'viewinstr':

        // Подключаем трей
        include_once XOOPS_ROOT_PATH . '/modules/instruction/class/Tree.php';

        // Заголовок админки
        xoops_cp_header();
        // Меню
        $adminObject->displayNavigation(basename(__FILE__));
        // Кнопки
        $adminObject->addItemButton(_AM_INSTRUCTION_ADDPAGE, 'instr.php?op=editpage&instrid=' . $instrid, 'add');
        $adminObject->displayButton('left', '');

        //
        $objInsinstr = $instructionHandler->get($instrid);

        // Находим все страницы в данной инструкции
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('instrid', $instrid, '='));
        $criteria->setSort('weight');
        $criteria->setOrder('ASC');
        $ins_page = $pageHandler->getall($criteria);
        //
        unset($criteria);

        // Инициализируем
        $instree = new Instruction\Tree($ins_page, 'pageid', 'pid');
        // Выводим список страниц в шаблон
        $GLOBALS['xoopsTpl']->assign('insListPage', $instree->makePagesAdmin($objInsinstr, '--'));

        // Выводим шаблон
        $GLOBALS['xoopsTpl']->display('db:admin/instruction_admin_viewinstr.tpl');

        // Текст внизу админки
        include __DIR__ . '/admin_footer.php';

        break;

    // Удаление категории
    case 'delinstr':

        // Проверка на instrid
        // ==================
        // Объект инструкций
        $objInsinstr = $instructionHandler->get($instrid);

        // Нажали ли мы на кнопку OK
        $ok = Request::getInt('ok', 0, 'POST');
        //
        if ($ok) {

            // Проверка
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('instr.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            // Находим все страницы, пренадлежащие этой инструкции
            $criteria = new \CriteriaCompo();
            $criteria->add(new \Criteria('instrid', $instrid));
            $ins_page = $pageHandler->getall($criteria);
            //
            unset($criteria);
            // Перебираем все страницы в данной инструкции
            foreach (array_keys($ins_page) as $i) {
                // Декримент комментов
                // Делает дикримент одного коммента, а не всех в цикле...
                // Удаляем комментарии
                xoops_comment_delete($GLOBALS['xoopsModule']->getVar('mid'), $ins_page[$i]->getVar('pageid'));
                // Декримент страниц (Опционально)
                // ==============================

                // Удаляем страницу
                // Сделать проверку на удалённость страницы
                // ========================================
                $pageHandler->delete($ins_page[$i]);
            }
            // Пытаемся удалить инструкцию
            if ($instructionHandler->delete($objInsinstr)) {
                // Редирект
                redirect_header('instr.php', 3, _AM_INSTRUCTION_INSTRDELETED);
            } else {
                // Редирект
                redirect_header('instr.php', 3, _AM_INSTRUCTION_ERR_DELINSTR);
            }
        } else {
            xoops_cp_header();

            $adminObject->displayNavigation(basename(__FILE__));
            // Форма
            xoops_confirm(['ok' => 1, 'instrid' => $instrid, 'op' => 'delinstr'], 'instr.php', sprintf(_AM_INSTRUCTION_FORMDELINSTR, $objInsinstr->getVar('title')));
            // Текст внизу админки
            include __DIR__ . '/admin_footer.php';
        }

        break;

    // Добавление страницы
    case 'editpage':

        // Заголовок админки
        xoops_cp_header();
        // Скрипты
        $xoTheme->addScript(XOOPS_URL . '/modules/instruction/assets/js/admin.js');
        // Меню
        $adminObject->displayNavigation(basename(__FILE__));

        // Если мы редактируем страницу
        if ($pageid) {
            // Получаем объект страницы
            $objInspage = $pageHandler->get($pageid);
            // ID инструкции
            $instrid = $objInspage->getVar('instrid');
            // Создание новой страницы
        } elseif ($instrid) {
            // Создаём объект страницы
            $objInspage = $pageHandler->create();
            // Устанавливаем родительскую страницу
            $objInspage->setVar('pid', $pid);
        } else {
            redirect_header('instr.php', 3, _AM_INSTRUCTION_BADREQUEST);
        }
        // Форма
        $form = $objInspage->getForm('instr.php', $instrid);
        // Форма
        echo $form->render();
        // Выводим шаблон
        $GLOBALS['xoopsTpl']->display('db:admin/instruction_admin_editpage.tpl');

        // Текст внизу админки
        include __DIR__ . '/admin_footer.php';

        break;

    // Сохранение страницы
    case 'savepage':
        // Ошибки
        $err         = false;
        $message_err = '';

        // Проверка сессии
        if (!$GLOBALS['xoopsSecurity']->check()) {
            $err         = true;
            $err_txt     = implode(', ', $GLOBALS['xoopsSecurity']->getErrors());
            $message_err .= $err_txt . '<br>';
        }

        // Если мы редактируем
        if ($pageid) {
            $objInspage = $pageHandler->get($pageid);
        } elseif ($instrid) {
            $objInspage = $pageHandler->create();
            // Если мы создаём страницу необходимо указать к какой инструкции
            $objInspage->setVar('instrid', $instrid);
            // Указываем дату создания
            $objInspage->setVar('datecreated', $time);
            // Указываем пользователя
            $objInspage->setVar('uid', $uid);
        } else {
            redirect_header('instr.php', 3, _AM_INSTRUCTION_BADREQUEST);
        }

        //
        $page_title    = Request::getString('title', '', 'POST');
        $page_hometext = Request::getText('hometext', '', 'POST');

        // Родительская страница
        $objInspage->setVar('pid', $pid);
        // Дата обновления
        $objInspage->setVar('dateupdated', $time);
        // Название страницы
        $objInspage->setVar('title', $page_title);
        // Вес страницы
        $objInspage->setVar('weight', $weight);
        // Основной текст
        $objInspage->setVar('hometext', $page_hometext);
        // Сноска
        $objInspage->setVar('footnote', Request::getString('footnote', '', 'POST'));
        //$objInspage->setVar('footnote', Request::getText('footnote', '', 'POST'));
        // Статус
        $objInspage->setVar('status', Request::getInt('status', 0, 'POST'));
        // Тип
        $objInspage->setVar('type', Request::getInt('type', 0, 'POST'));
        // Мета-теги описания
        $objInspage->setVar('keywords', Request::getString('keywords', '', 'POST'));
        // Мета-теги ключевых слов
        $objInspage->setVar('description', Request::getText('description', '', 'POST'));
        //
        $dosmiley = (Request::getInt('dosmiley', 0, 'POST') > 0) ? 1 : 0;
        $doxcode  = (Request::getInt('doxcode', 0, 'POST') > 0) ? 1 : 0;
        $dobr     = (Request::getInt('dobr', 0, 'POST') > 0) ? 1 : 0;
        $dohtml   = (Request::getInt('dohtml', 0, 'POST') > 0) ? 1 : 0;
        //$doimage = ( isset( $_POST['doimage'] ) && intval( $_POST['doimage'] ) > 0 ) ? 1 : 0;
        $objInspage->setVar('dohtml', $dohtml);
        $objInspage->setVar('dosmiley', $dosmiley);
        $objInspage->setVar('doxcode', $doxcode);
        //$objInspage->setVar( 'doimage', $doimage );
        $objInspage->setVar('dobr', $dobr);

        //
        if (!$pageid && !$instrid) {
            $err         = true;
            $message_err .= _AM_INSTRUCTION_ERR_INSTR . '<br>';
        }
        // Проверка веса
        if (0 == $weight) {
            $err         = true;
            $message_err .= _AM_INSTRUCTION_ERR_WEIGHT . '<br>';
        }
        // Проверка родительской страницы
        if ($pageid && ($pageid == $pid)) {
            $err         = true;
            $message_err .= _AM_INSTRUCTION_ERR_PPAGE . '<br>';
        }
        // Проверка названия
        if (!$page_title) {
            $err         = true;
            $message_err .= _AM_INSTR_ERR_TITLE . '<br>';
        }
        // Проверка основного текста
        if (!$page_hometext) {
            $err         = true;
            $message_err .= _AM_INSTR_ERR_HOMETEXT . '<br>';
        }

        // Если были ошибки
        if (true === $err) {
            xoops_cp_header();
            // Меню страницы
            $adminObject->displayNavigation(basename(__FILE__));

            $message_err = '<div class="errorMsg" style="text-align: left;">' . $message_err . '</div>';
            // Выводим ошибки в шаблон
            $GLOBALS['xoopsTpl']->assign('insErrorMsg', $message_err);
            // Если небыло ошибок
        } else {
            // Вставляем данные в БД
            if ($pageHandler->insert($objInspage)) {
                // Ссылка для редиректа
                $redirect_url = 'instr.php?op=viewinstr&amp;instrid=' . $instrid . '#pageid_' . $pid;
                // Получаем ID инструкции
                $instrid = $objInspage->getInstrid();
                // Обновляем в инструкции число страниц и дату
                $instructionHandler->updatePages($instrid);
                // Если мы редактируем
                if ($pageid) {
                    // Редирект
                    redirect_header($redirect_url, 3, _AM_INSTRUCTION_PAGEMODIFY);
                    // Если мы добавляем
                } else {
                    // Инкримент комментов
                    $pageHandler->updatePosts($uid, Request::getInt('status', 0, 'POST'), 'add');
                    // Редирект
                    redirect_header($redirect_url, 3, _AM_INSTRUCTION_PAGEADDED);
                }
            }
            xoops_cp_header();
            // Меню страницы
            $adminObject->displayNavigation(basename(__FILE__));

            // Выводим ошибки в шаблон
            $GLOBALS['xoopsTpl']->assign('insErrorMsg', $objInspage->getHtmlErrors());
        }
        // Скрипты
        $xoTheme->addScript(XOOPS_URL . '/modules/instruction/assets/js/admin.js');
        // Выводим шаблон
        $GLOBALS['xoopsTpl']->display('db:admin/instruction_admin_savepage.tpl');
        // Выводим форму
        $form = $objInspage->getForm('instr.php', $instrid);
        // Форма
        echo $form->render();
        // Текст внизу админки
        include __DIR__ . '/admin_footer.php';

        break;

    // Удаление страницы
    case 'delpage':

        // Проверка на pageid
        // ==================

        $objInspage = $pageHandler->get($pageid);
        // Нажали ли мы на кнопку OK
        $ok = Request::getInt('ok', 0, 'POST');
        // Если мы нажали на кнопку
        if ($ok) {

            // Проверка
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('instr.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            // ID инструкции
            $page_instrid = $objInspage->getVar('instrid');
            // Декримент комментов
            $pageHandler->updatePosts($objInspage->getVar('uid'), $objInspage->getVar('status'), 'delete');
            // Пытаемся удалить страницу
            if ($pageHandler->delete($objInspage)) {
                // Обновляем в инструкции число страниц и дату
                $instructionHandler->updatePages($page_instrid);
                // Удаляем комментарии
                xoops_comment_delete($GLOBALS['xoopsModule']->getVar('mid'), $pageid);
                //
                redirect_header('instr.php?op=viewinstr&amp;instrid=' . $page_instrid, 3, _AM_INSTRUCTION_PAGEDELETED);
                // Если не смогли удалить страницу
            } else {
                redirect_header('instr.php?op=viewinstr&amp;instrid=' . $page_instrid, 3, _AM_INSTRUCTION_ERR_DELPAGE);
            }
        } else {

            // Заголовок админки
            xoops_cp_header();
            // Меню
            $adminObject->displayNavigation(basename(__FILE__));
            // Форма
            xoops_confirm(['ok' => 1, 'pageid' => $pageid, 'op' => 'delpage'], 'instr.php', sprintf(_AM_INSTRUCTION_FORMDELPAGE, $objInspage->getVar('title')));
            // Текст внизу админки
            include __DIR__ . '/admin_footer.php';
        }

        break;

    // Удаление страницы
    case 'updpage':

        // Принимаем данные
        $pageids = Request::getArray('pageids', 0, 'POST');
        $weights = Request::getArray('weights', 0, 'POST');
        // Перебираем все значения
        foreach ($pageids as $key => $pageid) {

            // Объявляем объект
            $objInspage = $pageHandler->get($pageid);
            // Устанавливаем вес
            $objInspage->setVar('weight', $weights[$key]);
            // Вставляем данные в БД
            $pageHandler->insert($objInspage);
            // Удаляем объект
            unset($objInspage);
        }
        // Редирект
        redirect_header('instr.php?op=viewinstr&instrid=' . $instrid, 3, _AM_INSTRUCTION_PAGESUPDATE);

        break;

}
