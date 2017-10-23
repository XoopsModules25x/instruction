<?php

use Xoopsmodules\instruction;

// Блоки модуля инструкций

// Последние страницы
/**
 * @param array $options
 * @return array
 */
function b_instr_lastpage_show($options = [])
{

    // Подключаем функции
    $moduleDirName = basename(dirname(__DIR__));
    include_once $GLOBALS['xoops']->path('modules/' . $moduleDirName . '/include/common.php');
    //
    $myts = MyTextSanitizer::getInstance();
    //
    //mb    $instructionHandler = xoops_getModuleHandler('instruction', 'instruction');
    //mb    $pageHandler  = xoops_getModuleHandler('page', 'instruction');

    $db                 = \XoopsDatabaseFactory::getDatabase();
    $instructionHandler = new \Xoopsmodules\instruction\InstructionHandler($db);
    $pageHandler        = new \Xoopsmodules\instruction\PageHandler($db);

    // Добавляем стили
    //global $xoTheme;
    //$xoTheme->addStylesheet( XOOPS_URL . '/modules/instruction/css/blocks.css' );

    // Опции
    // Количество страниц
    $limit = $options[0];
    // Количество символов
    $numchars = $options[1];

    // Права на просмотр
    $cat_view = Xoopsmodules\instruction\Utility::getItemIds();
    // Массив выходных данных
    $block = [];

    // Если есть категории для прасмотра
    if (is_array($cat_view) && count($cat_view) > 0) {

        // Находим последние страницы
        $sql = "SELECT p.pageid, p.instrid, p.title, p.dateupdated, i.title, i.cid FROM {$pageHandler->table} p, {$instructionHandler->table} i WHERE p.instrid = i.instrid AND i.cid IN (" . implode(', ', $cat_view) . ') AND p.status > 0 AND i.status > 0 ORDER BY p.dateupdated DESC';
        // Лимит запроса
        $result = $GLOBALS['xoopsDB']->query($sql, $limit);
        // Перебираем все значения
        $i = 0;
        while (list($pageid, $instrid, $ptitle, $dateupdated, $ititle, $cid) = $GLOBALS['xoopsDB']->fetchRow($result)) {
            // ID страницы
            $block[$i]['pageid'] = $pageid;
            // ID инструкции
            $block[$i]['instrid'] = $instrid;
            // Название страницы
            $block[$i]['ptitle'] = $myts->htmlSpecialChars($ptitle);
            // Название инструкции
            $block[$i]['ititle'] = $myts->htmlSpecialChars($ititle);
            // Дата обновления страницы
            $block[$i]['dateupdated'] = formatTimeStamp($dateupdated, 's');
            // Категория инстркции
            $block[$i]['cid'] = $cid;
            // Инкримент
            $i++;
        }
    }

    // Возвращаем массив
    return $block;
}

// Редактирование последних страниц
/**
 * @param array $options
 * @return string
 */
function b_instr_lastpage_edit($options = [])
{
    $form = '';
    $form .= _MB_INSTR_DISPLAYPAGESC . ' <input name="options[0]" size="5" maxlength="255" value="' . $options[0] . '" type="text" ><br>' . "\n";
    $form .= _MB_INSTR_NUMCHARSC . ' <input name="options[1]" size="5" maxlength="255" value="' . $options[1] . '" type="text" ><br>' . "\n";

    // Возвращаем форму
    return $form;
}
