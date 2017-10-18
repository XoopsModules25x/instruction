<?php

use Xmf\Request;
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

$uid  = ($GLOBALS['xoopsUser'] instanceof \XoopsUser) ? $GLOBALS['xoopsUser']->getVar('uid') : 0;
$time = time();

// Опция
$op = Request::getString('op', 'main', 'POST');

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
        $title       = Request::getString('title', '', 'POST');
        $pid         = Request::getInt('pid', 0, 'POST');
        $weight      = Request::getInt('weight', 0, 'POST');
        $hometext    = Request::getText('hometext', '', 'POST');
        $footnote    = Request::getString('footnote', '', 'POST');
        $status      = Request::getInt('status', 0, 'POST');
        $type        = Request::getInt('type', 0, 'POST');
        $keywords    = Request::getString('keywords', '', 'POST');
        $description = Request::getText('description', '', 'POST');
        $dosmiley    = (Request::getInt('dosmiley', 0, 'POST') > 0) ? 1 : 0;
        $doxcode     = (Request::getInt('doxcode', 0, 'POST') > 0) ? 1 : 0;
        $dobr        = (Request::getInt('dobr', 0, 'POST') > 0) ? 1 : 0;
        $dohtml      = (Request::getInt('dohtml', 0, 'POST') > 0) ? 1 : 0;
        //$dohtml      = Request::getInt( 'dohtml', 0, 'POST' );
        //$dosmiley    = Request::getInt( 'dosmiley', 0, 'POST' );
        //$doxcode     = Request::getInt( 'doxcode', 0, 'POST' );
        //$dobr        = Request::getInt( 'dobr', 0, 'POST' );
        $pageid  = Request::getInt('pageid', 0, 'POST');
        $instrid = Request::getInt('instrid', 0, 'POST');

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
