<?php

use Xmf\Request;
use Xoopsmodules\instruction;

//
include __DIR__ . '/admin_header.php';
// Функции модуля
//include __DIR__ . '/../class/utility.php';
//include __DIR__ . '/../include/common.php';

// Admin Gui
$adminObject = \Xmf\Module\Admin::getInstance();

// Объявляем объекты
//$categoryHandler = xoops_getModuleHandler('category', 'instruction');
//$instructionHandler       = xoops_getModuleHandler('instruction', 'instruction');

$time = time();

// ID категории
$cid = Request::getInt('cid', 0);
// ID родителя
$pid = Request::getInt('pid', 0);
// Вес
$weight = Request::getInt('weight', 0);
// Опция
$op = Request::getString('op', 'main');
// Выбор
switch ($op) {

    case 'main':

        // Подключаем трей

        //        include_once __DIR__  . '/../class/Tree.php';
        //include_once $GLOBALS['xoops']->path('modules/instruction/class/Tree.php');

        // Заголовок админки
        xoops_cp_header();
        // Навигация
        $adminObject->displayNavigation(basename(__FILE__));

        // Находим ID-категории => Число страниц
        $cidinstrids = [];
        $sql         = "SELECT `cid`, COUNT( `instrid` ) FROM {$instructionHandler->table} GROUP BY `cid`";
        $result      = $GLOBALS['xoopsDB']->query($sql);
        while (list($cid, $count) = $GLOBALS['xoopsDB']->fetchRow($result)) {
            // Заполняем массив
            $cidinstrids[$cid] = $count;
        }

        // Выбираем категории из БД
        $criteria = new \CriteriaCompo();
        $criteria->setSort('weight ASC, title');
        $criteria->setOrder('ASC');
        $ins_cat = $categoryHandler->getall($criteria);
        unset($criteria);

        // Инициализируем
        $cattree = new instruction\Tree($ins_cat, 'cid', 'pid');
        // Выводим списко категорий в шаблон
        $GLOBALS['xoopsTpl']->assign('insListCat', $cattree->makeCatsAdmin('--', $cidinstrids));

        // Создание новой категории
        $objInstructioncat = $categoryHandler->create();
        $form              = $objInstructioncat->getForm('cat.php');
        // Форма
        $GLOBALS['xoopsTpl']->assign('insFormCat', $form->render());
        // Выводим шаблон
        $GLOBALS['xoopsTpl']->display('db:admin/instruction_admin_cat.tpl');

        // Текст внизу админки
        include __DIR__ . '/admin_footer.php';

        break;

    // Редактирование категории
    case 'editcat':

        // Заголовок админки
        xoops_cp_header();
        // Навигация
        $adminObject->displayNavigation(basename(__FILE__));

        $objInstructioncat = $categoryHandler->get($cid);
        $form              = $objInstructioncat->getForm('cat.php');
        // Форма
        //$GLOBALS['xoopsTpl']->assign( 'insFormCat', $form->render() );
        echo $form->render();
        // Выводим шаблон
        $GLOBALS['xoopsTpl']->display('db:admin/instruction_admin_editcat.tpl');

        // Текст внизу админки
        include __DIR__ . '/admin_footer.php';

        break;

    // Сохранение категорий
    case 'savecat':

        // Проверка
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('cat.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        // Если мы редактируем
        if ($cid) {
            $objInstructioncat = $categoryHandler->get($cid);
        } else {
            $objInstructioncat = $categoryHandler->create();
            // Указываем дату создания
            $objInstructioncat->setVar('datecreated', $time);
        }

        $err         = false;
        $message_err = '';

        // Дата обновления
        $objInstructioncat->setVar('dateupdated', $time);
        $objInstructioncat->setVar('pid', $pid);
        $objInstructioncat->setVar('title', Request::getString('title', '', 'POST'));
        $objInstructioncat->setVar('description', Request::getString('description', '', 'POST'));
        $objInstructioncat->setVar('weight', $weight);
        $objInstructioncat->setVar('metakeywords', Request::getString('metakeywords', '', 'POST'));
        $objInstructioncat->setVar('metadescription', Request::getString('metadescription', '', 'POST'));

        // Проверка веса
        if (0 == $weight) {
            $err         = true;
            $message_err .= _AM_INSTRUCTION_ERR_WEIGHT . '<br>';
        }
        // Проверка категорий
        if ($cid && ($cid == $pid)) {
            $err         = true;
            $message_err .= _AM_INSTRUCTION_ERR_PCAT . '<br>';
        }
        // Если были ошибки
        if (true === $err) {
            xoops_cp_header();
            // Навигация
            $adminObject->displayNavigation(basename(__FILE__));

            $message_err = '<div class="errorMsg" style="text-align: left;">' . $message_err . '</div>';
            // Выводим ошибки в шаблон
            $GLOBALS['xoopsTpl']->assign('insErrorMsg', $message_err);
            // Если небыло ошибок
        } else {
            // Вставляем данные в БД
            if ($categoryHandler->insert($objInstructioncat)) {

                // ID категории. Если редактируем - то не изменяется. Если создаём новую - то получаем ID созданной записи.
                $new_cid = $cid ?: $objInstructioncat->getNewInstertId();

                // ===============
                // ==== Права ====
                // ===============

                $gpermHandler = xoops_getHandler('groupperm');

                // Если мы редактируем категорию, то старые права нужно удалить
                if ($cid) {
                    // Права на просмотр
                    $criteria = new\ CriteriaCompo();
                    $criteria->add(new \Criteria('gperm_itemid', $new_cid, '='));
                    $criteria->add(new \Criteria('gperm_modid', $GLOBALS['xoopsModule']->getVar('mid'), '='));
                    $criteria->add(new \Criteria('gperm_name', 'instruction_view', '='));
                    $gpermHandler->deleteAll($criteria);
                    // Права на добавление
                    $criteria = new \CriteriaCompo();
                    $criteria->add(new \Criteria('gperm_itemid', $new_cid, '='));
                    $criteria->add(new \Criteria('gperm_modid', $GLOBALS['xoopsModule']->getVar('mid'), '='));
                    $criteria->add(new \Criteria('gperm_name', 'instruction_submit', '='));
                    $gpermHandler->deleteAll($criteria);
                    // Права на редактирование
                    $criteria = new \CriteriaCompo();
                    $criteria->add(new \Criteria('gperm_itemid', $new_cid, '='));
                    $criteria->add(new \Criteria('gperm_modid', $GLOBALS['xoopsModule']->getVar('mid'), '='));
                    $criteria->add(new \Criteria('gperm_name', 'instruction_edit', '='));
                    $gpermHandler->deleteAll($criteria);
                }

                // Добавляем права
                // Права на просмотр
                if (Request::hasVar('groups_instr_view', 'POST')) {
                    foreach (Request::getArray('groups_instr_view', '', 'POST') as $onegroup_id) {
                        $gpermHandler->addRight('instruction_view', $new_cid, $onegroup_id, $GLOBALS['xoopsModule']->getVar('mid'));
                    }
                }
                // Права на добавление
                if (Request::hasVar('groups_instr_submit', 'POST')) {
                    foreach (Request::getArray('groups_instr_submit', '', 'POST') as $onegroup_id) {
                        $gpermHandler->addRight('instruction_submit', $new_cid, $onegroup_id, $GLOBALS['xoopsModule']->getVar('mid'));
                    }
                }
                // Права на редактирование
                if (Request::hasVar('groups_instr_edit', 'POST')) {
                    foreach (Request::getArray('groups_instr_edit', '', 'POST') as $onegroup_id) {
                        $gpermHandler->addRight('instruction_edit', $new_cid, $onegroup_id, $GLOBALS['xoopsModule']->getVar('mid'));
                    }
                }

                //
                redirect_header('cat.php', 3, _AM_INSTRUCTION_NEWCATADDED);
            }
            xoops_cp_header();
            // Навигация
            $adminObject->displayNavigation(basename(__FILE__));
            // Выводим ошибки в шаблон
            $GLOBALS['xoopsTpl']->assign('insErrorMsg', $objInstructioncat->getHtmlErrors());
        }
        // Выводим шаблон
        $GLOBALS['xoopsTpl']->display('db:admin/instruction_admin_savecat.tpl');
        // Выводим форму
        $form = $objInstructioncat->getForm();
        // Форма
        echo $form->render();
        // Текст внизу админки
        include __DIR__ . '/admin_footer.php';

        break;

    // Удаление категории
    case 'delcat':

        // Находим число инструкций в данной категории
        // Критерий выборки
        $criteria = new \CriteriaCompo();
        // Все инструкции в данной категории
        $criteria->add(new \Criteria('cid', $cid, '='));
        $numrows = $instructionHandler->getCount($criteria);
        //
        unset($criteria);
        // Если есть хоть одна инструкция
        if ($numrows) {
            redirect_header('cat.php', 3, _AM_INSTRUCTION_ERR_CATNOTEMPTY);
        }

        $objInscat = $categoryHandler->get($cid);
        // Если нет такой категории
        if (!is_object($objInscat)) {
            redirect_header('cat.php', 3, _AM_INSTRUCTION_ERR_CATNOTSELECT);
        }

        // Нельзя удалять пока есть доченрии категории
        // Подключаем трей
        include_once $GLOBALS['xoops']->path('class/tree.php');
        $inscat_arr   = $categoryHandler->getall();
        $mytree       = new \XoopsObjectTree($inscat_arr, 'cid', 'pid');
        $ins_childcat = $mytree->getAllChild($cid);
        // Если есть дочернии категории
        if (count($ins_childcat)) {
            redirect_header('cat.php', 3, _AM_INSTRUCTION_ERR_CATCHILDREN);
        }

        // Нажали ли мы на кнопку OK
        $ok = Request::getInt('ok', 0, 'POST');
        // Если мы нажали на кнопку
        if ($ok) {

            // Проверка
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('cat.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            // Пытаемся удалить категорию
            if ($categoryHandler->delete($objInscat)) {

                // Удалить права доступа к категории
                // =================================

                // Редирект
                redirect_header('cat.php', 3, _AM_INSTRUCTION_CATDELETED);
                // Если не смогли удалить категорию
            } else {
                // Редирект
                redirect_header('cat.php', 3, _AM_INSTRUCTION_ERR_DELCAT);
            }
        } else {

            // Заголовок админки
            xoops_cp_header();
            // Навигация
            $adminObject->displayNavigation(basename(__FILE__));

            xoops_confirm(['ok' => 1, 'cid' => $cid, 'op' => 'delcat'], 'cat.php', sprintf(_AM_INSTRUCTION_FORMDELCAT, $objInscat->getVar('title')));

            // Текст внизу админки
            include __DIR__ . '/admin_footer.php';
        }

        break;

}
