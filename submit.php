<?php

use Xmf\Request;
use XoopsModules\Instruction;

require_once __DIR__ . '/header.php';
require_once __DIR__ . '/include/common.php';
// Объявляем объекты
//$instructionHandler = xoops_getModuleHandler('instruction', 'instruction');
//$categoryHandler = xoops_getModuleHandler( 'category', 'instruction' );
//$pageHandler  = xoops_getModuleHandler('page', 'instruction');

//
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
// Права на добавление
$cat_submit = XoopsModules\Instruction\Utility::getItemIds($moduleDirName . '_submit');
// Права на редактирование
$cat_edit = XoopsModules\Instruction\Utility::getItemIds($moduleDirName . '_edit');

$op = Request::getString('op', Request::getString('op', 'main', 'GET'), 'POST');
// Load language files
$helper->loadLanguage('admin');
$helper->loadLanguage('modinfo');

switch ($op) {

    case 'editpage':

        // Задание тайтла
        $xoopsOption['xoops_pagetitle'] = '';
        // Шаблон
        $GLOBALS['xoopsOption']['template_main'] = $moduleDirName . '_editpage.tpl';
        // Заголовок
        include_once $GLOBALS['xoops']->path('header.php');

        // Если мы редактируем страницу
        if ($pageid) {
            // Получаем объект страницы
            $objInspage = $pageHandler->get($pageid);
            // ID инструкции
            $instrid = $objInspage->getVar('instrid');
            // Объект инструкции
            $objInsinstr = $instructionHandler->get($instrid);
            // Можно ли редактировать инструкцию в данной категории
            if (!in_array($objInsinstr->getVar('cid'), $cat_edit)) {
                redirect_header('index.php', 3, _MD_INSTRUCTION_NOPERM_EDITPAGE);
            }
            // Создание новой страницы
        } elseif ($instrid) {

            // Если нельзя добавлять не в одну категорию
            //if( ! count( $cat_submit ) ) redirect_header( 'index.php', 3, _MD_INSTRUCTION_NOPERM_SUBMIT_PAGE );
            // Создаём объект страницы
            $objInspage = $pageHandler->create();
            // Объект инструкции
            $objInsinstr = $instructionHandler->get($instrid);
            // Можно ли добавлять инструкции в данной категории
            if (!in_array($objInsinstr->getVar('cid'), $cat_submit)) {
                redirect_header('index.php', 3, _MD_INSTRUCTION_NOPERM_SUBMITPAGE);
            }
        } else {
            redirect_header('index.php', 3, _MD_INSTRUCTION_BADREQUEST);
        }

        // Информация об инструкции

        // Массив данных об инструкции
        $instrs = [];
        // ID инструкции
        $instrs['instrid'] = $objInsinstr->getVar('instrid');
        // Название страницы
        $instrs['title'] = $objInsinstr->getVar('title');
        // Описание
        $instrs['description'] = $objInsinstr->getVar('description');

        // Выводим в шаблон
        $GLOBALS['xoopsTpl']->assign('insInstr', $instrs);

        //

        $form = $objInspage->getForm('submit.php', $instrid);
        // Форма
        $GLOBALS['xoopsTpl']->assign('insFormPage', $form->render());

        // Подвал
        include_once $GLOBALS['xoops']->path('footer.php');

        break;
    // Сохранение страницы
    case 'savepage':

        // Проверка
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('index.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }

        $err         = false;
        $message_err = '';

        // Если мы редактируем
        if ($pageid) {
            $objInspage = $pageHandler->get($pageid);
            // Объект инструкции
            $objInsinstr = $instructionHandler->get($objInspage->getVar('instrid'));
            // Можно ли редактировать инструкцию в данной категории
            if (!in_array($objInsinstr->getVar('cid'), $cat_edit)) {
                redirect_header('index.php', 3, _MD_INSTRUCTION_NOPERM_EDITPAGE);
            }
        } elseif ($instrid) {
            $objInspage = $pageHandler->create();
            // Объект инструкции
            $objInsinstr = $instructionHandler->get($instrid);
            // Можно ли добавлять инструкции в данной категории
            if (!in_array($objInsinstr->getVar('cid'), $cat_submit)) {
                redirect_header('index.php', 3, _MD_INSTRUCTION_NOPERM_SUBMITPAGE);
            }

            // Если мы создаём страницу необходимо указать к какой инструкции
            $objInspage->setVar('instrid', $instrid);
            // Указываем дату создания
            $objInspage->setVar('datecreated', $time);
            // Указываем пользователя
            $objInspage->setVar('uid', $uid);
        } else {
            redirect_header('index.php', 3, _MD_INSTRUCTION_BADREQUEST);
        }

        // Родительская страница
        $objInspage->setVar('pid', $pid);
        // Дата обновления
        $objInspage->setVar('dateupdated', $time);
        //
        $objInspage->setVar('title', Request::getString('title', '', 'POST'));
        $objInspage->setVar('weight', $weight);
        $objInspage->setVar('hometext', Request::getText('hometext', '', 'POST'));
        // Сноска
        $objInspage->setVar('footnote', Request::getText('footnote', '', 'POST'));
        $objInspage->setVar('status', Request::getInt('status', 0, 'POST'));
        $objInspage->setVar('type', Request::getInt('type', 0, 'POST'));
        $objInspage->setVar('keywords', Request::getString('keywords', '', 'POST'));
        $objInspage->setVar('description', Request::getText('description', '', 'POST'));
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
        // Проверка категорий
        if (!$pageid && !$instrid) {
            $err         = true;
            $message_err .= _MD_INSTRUCTION_ERR_INSTR . '<br>';
        }
        // Проверка веса
        if (0 == $weight) {
            $err         = true;
            $message_err .= _MD_INSTRUCTION_ERR_WEIGHT . '<br>';
        }
        // Проверка родительской страницы
        if ($pageid && ($pageid == $pid)) {
            $err         = true;
            $message_err .= _MD_INSTRUCTION_ERR_PPAGE . '<br>';
        }
        // Если были ошибки
        if (true === $err) {
            // Задание тайтла
            $xoopsOption['xoops_pagetitle'] = '';
            // Шаблон
            $GLOBALS['xoopsOption']['template_main'] = $moduleDirName . '_savepage.tpl';
            // Заголовок
            include_once $GLOBALS['xoops']->path('header.php');
            // Сообщение об ошибке
            $message_err = '<div class="errorMsg" style="text-align: left;">' . $message_err . '</div>';
            // Выводим ошибки в шаблон
            $GLOBALS['xoopsTpl']->assign('insErrorMsg', $message_err);
            // Если небыло ошибок
        } else {
            // Вставляем данные в БД
            if ($pageHandler->insert($objInspage)) {
                // Если мы редактируем
                if ($pageid) {
                    // Обновление даты
                    $sql = sprintf('UPDATE %s SET `dateupdated` = %u WHERE `instrid` = %u', $GLOBALS['xoopsDB']->prefix($moduleDirName . '_instr'), $time, $instrid);
                    $GLOBALS['xoopsDB']->query($sql);
                    // Запись в лог
                    xoops_loadLanguage('main', 'userslog');
                    //userslog_insert( $objInsinstr->getVar('title') . ': ' . $objInspage->getVar('title'), _MD_USERSLOG_MODIFY_PAGE );
                    //
                    redirect_header('index.php', 3, _MD_INSTRUCTION_PAGEMODIFY);
                    // Если мы добавляем
                } else {
                    // Инкримент комментов
                    $pageHandler->updatePosts($uid, Request::getInt('status', 0, 'POST'), 'add');
                    // Инкремент страниц и обновление даты
                    $sql = sprintf('UPDATE %s SET `pages` = `pages` + 1, `dateupdated` = %u WHERE `instrid` = %u', $GLOBALS['xoopsDB']->prefix($moduleDirName . '_instr'), $time, $instrid);
                    $GLOBALS['xoopsDB']->query($sql);
                    // Запись в лог
                    xoops_loadLanguage('main', 'userslog');
                    //userslog_insert( $objInsinstr->getVar('title') . ': ' . $objInspage->getVar('title'), _MD_USERSLOG_SUBMIT_PAGE );
                    //
                    redirect_header('index.php', 3, _MD_INSTRUCTION_PAGEADDED);
                }
            }

            // Задание тайтла
            $xoopsOption['xoops_pagetitle'] = '';
            // Шаблон
            $GLOBALS['xoopsOption']['template_main'] = $moduleDirName . '_savepage.tpl';
            // Заголовок
            include_once $GLOBALS['xoops']->path('header.php');

            // Выводим ошибки в шаблон
            $GLOBALS['xoopsTpl']->assign('insErrorMsg', $objInspage->getHtmlErrors());
        }
        // Получаем форму
        $form = $objInspage->getForm('submit.php', $instrid);

        // Форма
        $GLOBALS['xoopsTpl']->assign('insFormPage', $form->render());

        // Подвал
        include_once $GLOBALS['xoops']->path('footer.php');

        break;
}
