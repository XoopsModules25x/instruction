<?php

use Xoopsmodules\instruction;

//
include __DIR__ . '/admin_header.php';
// Функции модуля
//include __DIR__ . '/../class/utility.php';
// Отключаем дебугер
$GLOBALS['xoopsLogger']->activated = false;

// Объявляем объекты
//$instructionHandler = xoops_getModuleHandler('instruction', 'instruction');
//$categoryHandler   = xoops_getModuleHandler('category', 'instruction');
//$pageHandler  = xoops_getModuleHandler('page', 'instruction');

$uid  = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getVar('uid') : 0;
$time = time();

// Опция
$op = Xoopsmodules\instruction\Utility::cleanVars($_POST, 'op', 'main', 'string');

// Выбор
switch ($op) {
    // Сохранение страницы
    case 'savepage':
        // Выходной массив
        $ret = [];

        // Ошибки
        $err         = false;
        $message_err = '';

        //
        $title       = Xoopsmodules\instruction\Utility::cleanVars($_POST, 'title', '', 'string');
        $pid         = Xoopsmodules\instruction\Utility::cleanVars($_POST, 'pid', 0, 'int');
        $weight      = Xoopsmodules\instruction\Utility::cleanVars($_POST, 'weight', 0, 'int');
        $hometext    = Xoopsmodules\instruction\Utility::cleanVars($_POST, 'hometext', '', 'string');
        $footnote    = Xoopsmodules\instruction\Utility::cleanVars($_POST, 'footnote', '', 'string');
        $status      = Xoopsmodules\instruction\Utility::cleanVars($_POST, 'status', 0, 'int');
        $type        = Xoopsmodules\instruction\Utility::cleanVars($_POST, 'type', 0, 'int');
        $keywords    = Xoopsmodules\instruction\Utility::cleanVars($_POST, 'keywords', '', 'string');
        $description = Xoopsmodules\instruction\Utility::cleanVars($_POST, 'description', '', 'string');
        $dosmiley    = (isset($_POST['dosmiley']) && (int)$_POST['dosmiley'] > 0) ? 1 : 0;
        $doxcode     = (isset($_POST['doxcode']) && (int)$_POST['doxcode'] > 0) ? 1 : 0;
        $dobr        = (isset($_POST['dobr']) && (int)$_POST['dobr'] > 0) ? 1 : 0;
        $dohtml      = (isset($_POST['dohtml']) && (int)$_POST['dohtml'] > 0) ? 1 : 0;
        //$dohtml      = Xoopsmodules\instruction\Utility::cleanVars( $_POST, 'dohtml', 0, 'int' );
        //$dosmiley    = Xoopsmodules\instruction\Utility::cleanVars( $_POST, 'dosmiley', 0, 'int' );
        //$doxcode     = Xoopsmodules\instruction\Utility::cleanVars( $_POST, 'doxcode', 0, 'int' );
        //$dobr        = Xoopsmodules\instruction\Utility::cleanVars( $_POST, 'dobr', 0, 'int' );
        $pageid  = Xoopsmodules\instruction\Utility::cleanVars($_POST, 'pageid', 0, 'int');
        $instrid = Xoopsmodules\instruction\Utility::cleanVars($_POST, 'instrid', 0, 'int');

        // Проверка
        if (!$GLOBALS['xoopsSecurity']->check()) {
            $err         = true;
            $err_txt     = implode(', ', $GLOBALS['xoopsSecurity']->getErrors());
            $message_err .= $err_txt . '<br>' . _AM_INSTR_TRY_AGAIN . '<br>';
        }
        // =========================
        // Устанавливаем новый token
        $token_name    = 'XOOPS_TOKEN';
        $token_timeout = 0;
        // $token_name . '_REQUEST' - название элемента формы
        $ret['toket'] = $GLOBALS['xoopsSecurity']->createToken($token_timeout, $token_name);
        // =========================

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
            // Устанавливаем сообщение
            $ret['message'] = _AM_INSTRUCTION_BADREQUEST;
            // Возвращаем ответ скрипту через JSON
            echo json_encode($ret);
            // Прерываем выполнение
            exit();
        }

        // Родительская страница
        $objInspage->setVar('pid', $pid);
        // Дата обновления
        $objInspage->setVar('dateupdated', $time);
        // Название
        $objInspage->setVar('title', $title);
        // Вес
        $objInspage->setVar('weight', $weight);
        // Текст
        $objInspage->setVar('hometext', $hometext);
        // Сноска
        $objInspage->setVar('footnote', $footnote);
        // Статус
        $objInspage->setVar('status', $status);
        // Тип
        $objInspage->setVar('type', $type);
        // Мета-теги ключевых слов
        $objInspage->setVar('keywords', $keywords);
        // Мета-теги описания
        $objInspage->setVar('description', $description);
        //
        $objInspage->setVar('dohtml', $dohtml);
        $objInspage->setVar('dosmiley', $dosmiley);
        $objInspage->setVar('doxcode', $doxcode);
        $objInspage->setVar('dobr', $dobr);

        // Проверка категорий
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
        if (!$title) {
            $err         = true;
            $message_err .= _AM_INSTR_ERR_TITLE . '<br>';
        }
        // Проверка основного текста
        if (!$hometext) {
            $err         = true;
            $message_err .= _AM_INSTR_ERR_HOMETEXT . '<br>';
        }

        // Если были ошибки
        if (true === $err) {
            //
            $message_err = '<div class="errorMsg" style="text-align: left;">' . $message_err . '</div>';
            // Устанавливаем сообщение
            $ret['message'] = $message_err;
            // Возвращаем ответ скрипту через JSON
            echo json_encode($ret);
            // Прерываем выполнение
            exit();
            // Если небыло ошибок
        } else {
            // Вставляем данные в БД
            if ($pageHandler->insert($objInspage)) {
                // Находим ID созданной записи
                $pageid_new = $pageid ?: $objInspage->getNewInstertId();
                //
                $ret['pageid'] = $pageid_new;
                // Получаем ID инструкции
                $instrid = $objInspage->getInstrid();
                // Обновляем в инструкции число страниц и дату
                $instructionHandler->updatePages($instrid);
                // Если мы редактируем
                if ($pageid) {
                    // Устанавливаем сообщение
                    $ret['message'] = '<div class="successMsg" style="text-align: left;">' . _AM_INSTRUCTION_PAGEMODIFY . '</div>';
                    // Возвращаем ответ скрипту через JSON
                    echo json_encode($ret);
                    // Прерываем выполнение
                    exit();

                    // Если мы добавляем
                } else {
                    // Инкримент комментов
                    $pageHandler->updateposts($uid, $status, 'add');

                    // Устанавливаем сообщение
                    $ret['message'] = '<div class="successMsg" style="text-align: left;">' . _AM_INSTRUCTION_PAGEADDED . '</div>';
                    // Возвращаем ответ скрипту через JSON
                    echo json_encode($ret);
                    // Прерываем выполнение
                    exit();
                }
                // Если не получилось вставить данные
            } else {

                //
                $message_err = '<div class="errorMsg" style="text-align: left;">' . $objInspage->getHtmlErrors() . '</div>';
                // Устанавливаем сообщение
                $ret['message'] = $message_err;
                // Возвращаем ответ скрипту через JSON
                echo json_encode($ret);
                // Прерываем выполнение
                exit();
            }
        }

        break;
}
