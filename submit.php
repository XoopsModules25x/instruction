<?php

use Xmf\Request;
use Xoopsmodules\instruction;

require_once __DIR__ . '/header.php';

// Объявляем объекты
//$instructionHandler = xoops_getModuleHandler('instruction', 'instruction');
//$categoryHandler = xoops_getModuleHandler( 'category', 'instruction' );
//$pageHandler  = xoops_getModuleHandler('page', 'instruction');

//
$uid  = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getVar('uid') : 0;
$time = time();

// ID инструкции
$instrid = isset($_GET['instrid']) ? (int)$_GET['instrid'] : 0;
$instrid = isset($_POST['instrid']) ? (int)$_POST['instrid'] : $instrid;
// ID страницы
$pageid = isset($_GET['pageid']) ? (int)$_GET['pageid'] : 0;
$pageid = isset($_POST['pageid']) ? (int)$_POST['pageid'] : $pageid;
// ID категории
$cid = isset($_POST['cid']) ? (int)$_POST['cid'] : 0;
// Вес
$weight = isset($_POST['weight']) ? (int)$_POST['weight'] : 0;
//
$pid = isset($_POST['pid']) ? (int)$_POST['pid'] : 0;

// Права на добавление
$cat_submit = Xoopsmodules\instruction\Utility::getItemIds($moduleDirName . '_submit');
// Права на редактирование
$cat_edit = Xoopsmodules\instruction\Utility::getItemIds($moduleDirName . '_edit');

$op = isset($_GET['op']) ? $_GET['op'] : '';
$op = isset($_POST['op']) ? $_POST['op'] : $op;

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
        $objInspage->setVar('title', $_POST['title']);
        $objInspage->setVar('weight', $weight);
        $objInspage->setVar('hometext', $_POST['hometext']);
        // Сноска
        $objInspage->setVar('footnote', $_POST['footnote']);
        $objInspage->setVar('status', $_POST['status']);
        $objInspage->setVar('keywords', $_POST['keywords']);
        $objInspage->setVar('description', $_POST['description']);

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
                    $pageHandler->updateposts($uid, $_POST['status'], 'add');
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
