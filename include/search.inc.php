<?php
include_once dirname(__DIR__) . '/include/common.php';
require_once __DIR__ . '../../header.php';

function instruction_search($queryarray, $andor, $limit, $offset, $userid)
{
    // Подключаем функции
    include_once $GLOBALS['xoops']->path('/modules/' . $moduleDirName . '/class/utility.php');

    $sql = 'SELECT p.pageid, p.title, p.uid, p.datecreated, i.title FROM ' . $GLOBALS['xoopsDB']->prefix('instruction_page') . ' p, ' . $GLOBALS['xoopsDB']->prefix('instruction_instr') . ' i WHERE i.instrid = p.instrid AND i.status > 0 AND p.status > 0 AND p.type > 0';
    if (0 != $userid) {
        $sql .= ' AND p.uid = ' . (int)$userid . ' ';
        //return NULL;
    }

    // Права на просмотр
    $categories = InstructionUtility::getItemIds();
    if (is_array($categories) && count($categories) > 0) {
        $sql .= ' AND i.cid IN ( ' . implode(', ', $categories) . ' ) ';
        // Если пользователь не имеет прав просмотра ни одной категории
    } else {
        return null;
    }

    // Добавляем в условие ключевые слова поиска
    if (is_array($queryarray) && $count = count($queryarray)) {
        $sql .= " AND ( ( p.title LIKE '%$queryarray[0]%' OR p.hometext LIKE '%$queryarray[0]%' )";
        for ($i = 1; $i < $count; $i++) {
            $sql .= " $andor ";
            $sql .= "( p.title LIKE '%$queryarray[$i]%' OR p.hometext LIKE '%$queryarray[$i]%' )";
        }
        $sql .= ' ) ';
    }
    //$sql .= "ORDER BY date DESC";
    $result = $GLOBALS['xoopsDB']->query($sql, $limit, $offset);
    $ret    = [];
    $i      = 0;
    // Перебираем все результаты
    while (list($pageid, $ptitle, $puid, $pdatecreated, $ititle) = $GLOBALS['xoopsDB']->fetchRow($result)) {
        $ret[$i]['image'] = 'assets/images/size2.gif';
        $ret[$i]['link']  = 'page.php?id=' . $pageid;
        $ret[$i]['title'] = $ititle . ': ' . $ptitle;
        $ret[$i]['time']  = $pdatecreated;
        $ret[$i]['uid']   = $puid;
        $i++;
    }
    return $ret;
}
