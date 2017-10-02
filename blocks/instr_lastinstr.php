<?php
// Блоки модуля инструкций
// Блок последних инструкций

// Последние инструкции
function b_instr_lastinstr_show($options = [])
{

    // Подключаем функции
    $moduleDirName = dirname(__DIR__);
    include_once $moduleDirName . '/include/functions.php';
    //
    $myts = MyTextSanitizer::getInstance();
    //
    $insinstr_Handler = xoops_getModuleHandler('instruction', 'instruction');

    // Добавляем стили
    //global $xoTheme;
    //$xoTheme->addStylesheet( XOOPS_URL . '/modules/instruction/css/blocks.css' );

    // Опции
    // Количество страниц
    $limit = $options[0];
    // Количество символов
    $numchars = $options[1];

    // Права на просмотр
    $cat_view = instr_MygetItemIds();
    // Массив выходных данных
    $block = [];

    // Если есть категории для прасмотра
    if (is_array($cat_view) && count($cat_view) > 0) {

        // Находим последние инструкции
        $sql = "SELECT `instrid`, `cid`, `title`, `pages`, `dateupdated` FROM {$insinstr_Handler->table} WHERE `cid` IN (" . implode(', ', $cat_view) . ') AND `status` > 0 ORDER BY `dateupdated` DESC';
        // Лимит запроса
        $result = $GLOBALS['xoopsDB']->query($sql, $limit);
        // Перебираем все значения
        $i = 0;
        while (list($instrid, $cid, $ititle, $pages, $dateupdated) = $GLOBALS['xoopsDB']->fetchRow($result)) {
            // ID инструкции
            $block[$i]['instrid'] = $instrid;
            // ID категории
            $block[$i]['cid'] = $cid;
            // Название инструкции
            $block[$i]['ititle'] = $myts->htmlSpecialChars($ititle);
            // Число страниц
            $block[$i]['pages'] = $pages;
            // Дата обновления инструкции
            $block[$i]['dateupdated'] = formatTimeStamp($dateupdated, 's');
            // Инкримент
            $i++;
        }
    }
    // Возвращаем массив
    return $block;
}

// Редактирование последних инструкций
function b_instr_lastinstr_edit($options = [])
{
    $form = '';
    $form .= _MB_INSTR_DISPLAYINSTRC . ' <input name="options[0]" size="5" maxlength="255" value="' . $options[0] . '" type="text" /><br>' . "\n";
    $form .= _MB_INSTR_NUMCHARSC . ' <input name="options[1]" size="5" maxlength="255" value="' . $options[1] . '" type="text" /><br>' . "\n";

    // Возвращаем форму
    return $form;
}
