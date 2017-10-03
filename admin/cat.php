<?php

use Xmf\Request;

//
include __DIR__ . '/admin_header.php';
// Функции модуля
//include __DIR__ . '/../class/utility.php';

// Admin Gui
$adminObject = \Xmf\Module\Admin::getInstance();

// Объявляем объекты
$instructioncatHandler = xoops_getModuleHandler('category', 'instruction');
$insinstrHandler       = xoops_getModuleHandler('instruction', 'instruction');

$time = time();

// ID категории
$cid    = InstructionUtility::cleanVars($_REQUEST, 'cid', 0, 'int');
// ID родителя
$pid    = InstructionUtility::cleanVars($_REQUEST, 'pid', 0, 'int');
// Вес
$weight = InstructionUtility::cleanVars($_REQUEST, 'weight', 0, 'int');
// Опция
$op     = InstructionUtility::cleanVars($_REQUEST, 'op', 'main', 'string');
// Выбор
switch ($op) {

    case 'main':

        // Подключаем трей

        include_once __DIR__  . '/../class/tree.php';
        //include_once $GLOBALS['xoops']->path('modules/instruction/class/tree.php');

        // Заголовок админки
        xoops_cp_header();
        // Навигация
        $adminObject->displayNavigation(basename(__FILE__));

        // Находим ID-категории => Число страниц
        $cidinstrids = [];
        $sql         = "SELECT `cid`, COUNT( `instrid` ) FROM {$insinstrHandler->table} GROUP BY `cid`";
        $result      = $GLOBALS['xoopsDB']->query($sql);
        while (list($cid, $count) = $GLOBALS['xoopsDB']->fetchRow($result)) {
            // Заполняем массив
            $cidinstrids[$cid] = $count;
        }

        // Выбираем категории из БД
        $criteria = new CriteriaCompo();
        $criteria->setSort('weight ASC, title');
        $criteria->setOrder('ASC');
        $ins_cat  = $instructioncatHandler->getall($criteria);
        unset($criteria);

        // Инициализируем
        $cattree = new InstructionTree($ins_cat, 'cid', 'pid');
        // Выводим списко категорий в шаблон
        $GLOBALS['xoopsTpl']->assign('insListCat', $cattree->makeCatsAdmin('--', $cidinstrids));

        // Создание новой категории
        $objInstructioncat = $instructioncatHandler->create();
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

        $objInstructioncat = $instructioncatHandler->get($cid);
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
            $objInstructioncat = $instructioncatHandler->get($cid);
        } else {
            $objInstructioncat = $instructioncatHandler->create();
            // Указываем дату создания
            $objInstructioncat->setVar('datecreated', $time);
        }

        $err         = false;
        $message_err = '';

        // Дата обновления
        $objInstructioncat->setVar('dateupdated', $time);
        $objInstructioncat->setVar('pid', $pid);
        $objInstructioncat->setVar('title', $_POST['title']);
        $objInstructioncat->setVar('description', $_POST['description']);
        $objInstructioncat->setVar('weight', $weight);
        $objInstructioncat->setVar('metakeywords', $_POST['metakeywords']);
        $objInstructioncat->setVar('metadescription', $_POST['metadescription']);

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
            if ($instructioncatHandler->insert($objInstructioncat)) {

                // ID категории. Если редактируем - то не изменяется. Если создаём новую - то получаем ID созданной записи.
                $new_cid = $cid ?: $objInstructioncat->get_new_enreg();

                // ===============
                // ==== Права ====
                // ===============

                $gpermHandler = xoops_getHandler('groupperm');

                // Если мы редактируем категорию, то старые права нужно удалить
                if ($cid) {
                    // Права на просмотр
                    $criteria = new CriteriaCompo();
                    $criteria->add(new Criteria('gperm_itemid', $new_cid, '='));
                    $criteria->add(new Criteria('gperm_modid', $GLOBALS['xoopsModule']->getVar('mid'), '='));
                    $criteria->add(new Criteria('gperm_name', 'instruction_view', '='));
                    $gpermHandler->deleteAll($criteria);
                    // Права на добавление
                    $criteria = new CriteriaCompo();
                    $criteria->add(new Criteria('gperm_itemid', $new_cid, '='));
                    $criteria->add(new Criteria('gperm_modid', $GLOBALS['xoopsModule']->getVar('mid'), '='));
                    $criteria->add(new Criteria('gperm_name', 'instruction_submit', '='));
                    $gpermHandler->deleteAll($criteria);
                    // Права на редактирование
                    $criteria = new CriteriaCompo();
                    $criteria->add(new Criteria('gperm_itemid', $new_cid, '='));
                    $criteria->add(new Criteria('gperm_modid', $GLOBALS['xoopsModule']->getVar('mid'), '='));
                    $criteria->add(new Criteria('gperm_name', 'instruction_edit', '='));
                    $gpermHandler->deleteAll($criteria);
                }

                // Добавляем права
                // Права на просмотр
                if (isset($_POST['groups_instr_view'])) {
                    foreach ($_POST['groups_instr_view'] as $onegroup_id) {
                        $gpermHandler->addRight('instruction_view', $new_cid, $onegroup_id, $GLOBALS['xoopsModule']->getVar('mid'));
                    }
                }
                // Права на добавление
                if (isset($_POST['groups_instr_submit'])) {
                    foreach ($_POST['groups_instr_submit'] as $onegroup_id) {
                        $gpermHandler->addRight('instruction_submit', $new_cid, $onegroup_id, $GLOBALS['xoopsModule']->getVar('mid'));
                    }
                }
                // Права на редактирование
                if (isset($_POST['groups_instr_edit'])) {
                    foreach ($_POST['groups_instr_edit'] as $onegroup_id) {
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
        $criteria = new CriteriaCompo();
        // Все инструкции в данной категории
        $criteria->add(new Criteria('cid', $cid, '='));
        $numrows = $insinstrHandler->getCount($criteria);
        //
        unset($criteria);
        // Если есть хоть одна инструкция
        if ($numrows) {
            redirect_header('cat.php', 3, _AM_INSTRUCTION_ERR_CATNOTEMPTY);
        }

        $objInscat = $instructioncatHandler->get($cid);
        // Если нет такой категории
        if (!is_object($objInscat)) {
            redirect_header('cat.php', 3, _AM_INSTRUCTION_ERR_CATNOTSELECT);
        }

        // Нельзя удалять пока есть доченрии категории
        // Подключаем трей
        include_once $GLOBALS['xoops']->path('class/tree.php');
        $inscat_arr   = $instructioncatHandler->getall();
        $mytree       = new XoopsObjectTree($inscat_arr, 'cid', 'pid');
        $ins_childcat = $mytree->getAllChild($cid);
        // Если есть дочернии категории
        if (count($ins_childcat)) {
            redirect_header('cat.php', 3, _AM_INSTRUCTION_ERR_CATCHILDREN);
        }

        // Нажали ли мы на кнопку OK
        $ok = isset($_POST['ok']) ? (int)$_POST['ok'] : 0;
        // Если мы нажали на кнопку
        if ($ok) {

            // Проверка
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('cat.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            // Пытаемся удалить категорию
            if ($instructioncatHandler->delete($objInscat)) {

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
