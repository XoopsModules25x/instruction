<?php
// Автор: andrey3761
// Вывод древообразного списка страниц в панели администрирования

//defined('XOOPS_ROOT_PATH') or die('Restricted access');
define('INST_DIRNAME', basename(dirname(__DIR__)));

include_once $GLOBALS['xoops']->path('/include/common.php');
// Подключаем трей
include_once $GLOBALS['xoops']->path('/class/tree.php');

// Наследник класса XoopsObjectTree
class InstructionTree extends XoopsObjectTree
{
    public function __constrcut()
    {
    }

    public function _makePagesAdminOptions($key, &$ret, $prefix_orig, $objInsinstr, $class = 'odd', $prefix_curr = '')
    {
        if ($key > 0) {

            //
            $class = ('even' == $class) ? 'odd' : 'even';
            // ID инструкции ( Можно сделать статической )
            $instrid = $objInsinstr->getVar('instrid');

            // ID страницы
            $pageid = $this->_tree[$key]['obj']->getVar('pageid');
            // Название страницы
            $pagetitle = $this->_tree[$key]['obj']->getVar('title');
            // Вес
            $pageweight = $this->_tree[$key]['obj']->getVar('weight');
            // Статус
            $pagestatus = $this->_tree[$key]['obj']->getVar('status');
            // Тип страницы
            $pagetype = $this->_tree[$key]['obj']->getVar('type');

            // Дочернии страницы
            $page_childs =& $this->getAllChild($pageid);
            // Число дочерних страниц
            $num_childs = count($page_childs);

            // Действие - удаление
            $act_del = ($num_childs > 0) ? '<img src="../assets/icons/no_delete_mini.png" alt="' . _AM_INSTR_NODELPAGE . '" title="' . _AM_INSTR_NODELPAGE . '" />' : '<a href="instr.php?op=delpage&pageid='
                                                                                                                                                                      . $pageid
                                                                                                                                                                      . '"><img src="../assets/icons/delete_mini.png" alt="'
                                                                                                                                                                      . _AM_INSTRUCTION_DEL
                                                                                                                                                                      . '" title="'
                                                                                                                                                                      . _AM_INSTRUCTION_DEL
                                                                                                                                                                      . '"></a>';
            //
            $page_link = '<a name="pageid_' . $pageid . '" ' . ($pagetype ? 'href="' . XOOPS_URL . '/modules/' . INST_DIRNAME . '/page.php?id=' . $pageid . '#pagetext"' : '') . '>' . $pagetitle . '</a>';

            $ret .= '<tr class="' . $class . '">
      <td>' . $prefix_curr . ' ' . $page_link . '</td>
      <td align="center" width="50">
        <input type="text" name="weights[]" size="2" value="' . $pageweight . '" />
        <input type="hidden" name="pageids[]" value="' . $pageid . '" />
      </td>
      <td align="center" width="180">';
            // Просмотре без кэша
            $ret .= ' <a href="' . XOOPS_URL . '/modules/' . INST_DIRNAME . '/page.php?id=' . $pageid . '&amp;nocache=1"><img src="../assets/icons/no_cache.png" alt="' . _AM_INSTR_DISPLAY_NOCACHE . '" title="' . _AM_INSTR_DISPLAY_NOCACHE . '" /></a> ';
            // Добавить подстраницу
            $ret .= ' <a href="instr.php?op=editpage&instrid=' . $instrid . '&pid=' . $pageid . '"><img src="../assets/icons/add_mini.png" alt="' . _AM_INSTRUCTION_ADDSUBPAGE . '" title="' . _AM_INSTRUCTION_ADDSUBPAGE . '" /></a> ';

            if ($pagestatus) {
                $ret .= ' <img src="../assets/icons/lock_mini.png" alt="' . _AM_INSTRUCTION_LOCK . '" title="' . _AM_INSTRUCTION_LOCK . '"> ';
            } else {
                $ret .= ' <img src="../assets/icons/unlock_mini.png" alt="' . _AM_INSTRUCTION_UNLOCK . '" title="' . _AM_INSTRUCTION_UNLOCK . '"> ';
            }

            $ret .= ' <a href="instr.php?op=editpage&pageid=' . $pageid . '"><img src="../assets/icons/edit_mini.png" alt="' . _AM_INSTRUCTION_EDIT . '" title="' . _AM_INSTRUCTION_EDIT . '"></a> ' . $act_del . '
      </td>
    </tr>';

            // Устанавливаем префикс
            $prefix_curr .= $prefix_orig;
        }

        if (isset($this->_tree[$key]['child']) && !empty($this->_tree[$key]['child'])) {
            foreach ($this->_tree[$key]['child'] as $childkey) {
                $this->_makePagesAdminOptions($childkey, $ret, $prefix_orig, $objInsinstr, $class, $prefix_curr);
            }
        }
    }

    public function makePagesAdmin(&$objInsinstr, $prefix = '-', $key = 0)
    {
        $ret = '<form name="inspages" action="instr.php" method="post">
  <table width="100%" cellspacing="1" class="outer">
    <tr>
      <th align="center" colspan="3">' . sprintf(_AM_INSTRUCTION_LISTPAGESININSTR, $objInsinstr->getVar('title')) . '</th>
    </tr>
    <tr>
      <td class="head" align="center">' . _AM_INSTRUCTION_TITLE . '</td>
      <td class="head" align="center" width="50">' . _AM_INSTRUCTION_WEIGHT . '</td>
      <td class="head" align="center" width="180">' . _AM_INSTRUCTION_ACTION . '</td>
    </tr>';

        // Выводим все страницы
        $this->_makePagesAdminOptions($key, $ret, $prefix, $objInsinstr);

        $ret .= '<tr class="foot">
      <td><a href="instr.php?op=editpage&instrid=' . $objInsinstr->getVar('instrid') . '"><img src="../assets/icons/add_mini.png" alt="' . _AM_INSTRUCTION_ADDPAGE . '" title="' . _AM_INSTRUCTION_ADDPAGE . '"></a></td>
      <td colspan="2">
        <input type="hidden" name="instrid" value="' . $objInsinstr->getVar('instrid') . '" />
        <input type="hidden" name="op" value="updpage" />
        <input type="submit" value="' . _SUBMIT . '" />
      </td>
    </tr>
  </table>
</form>';

        return $ret;
    }

    // ==================================
    // === Дерево категорий в админке ===
    // ==================================

    public function _makeCatsAdminOptions($key, &$ret, $prefix_orig, $cidinstrids = [], &$class = 'odd', $prefix_curr = '')
    {
        if ($key > 0) {

            //
            $class = ('even' == $class) ? 'odd' : 'even';

            // ID категории
            $catid = $this->_tree[$key]['obj']->getVar('cid');
            // Название категории
            $cattitle = $this->_tree[$key]['obj']->getVar('title');
            // Вес
            $catweight = $this->_tree[$key]['obj']->getVar('weight');
            // Статус
            $pagestatus = $this->_tree[$key]['obj']->getVar('status');

            // Дочернии категории
            $cat_childs =& $this->getAllChild($catid);
            // Число дочерних категорий
            $num_childs = count($cat_childs);
            // Число инструкций
            $num_instrs = isset($cidinstrids[$catid]) ? $cidinstrids[$catid] : 0;

            // Действие - удаление
            $act_del = (($num_instrs > 0) || ($num_childs > 0)) ? '<img src="../assets/icons/no_delete_mini.png" alt="' . _AM_INSTR_NODELCAT . '" title="' . _AM_INSTR_NODELCAT . '" />' : '<a href="cat.php?op=delcat&cid='
                                                                                                                                                                                           . $catid
                                                                                                                                                                                           . '"><img src="../assets/icons/delete_mini.png" alt="'
                                                                                                                                                                                           . _AM_INSTRUCTION_DEL
                                                                                                                                                                                           . '" title="'
                                                                                                                                                                                           . _AM_INSTRUCTION_DEL
                                                                                                                                                                                           . '" /></a>';
            // Действие - просмотр
            $act_view = ($num_instrs > 0) ? '<a href="instr.php?cid=' . $catid . '"><img src="../assets/icons/view_mini.png" alt="' . _AM_INSTR_VIEWINSTR . '" title="' . _AM_INSTR_VIEWINSTR . '" /></a>' : '<img src="../assets/icons/no_view_mini.png" alt="'
                                                                                                                                                                                                             . _AM_INSTR_NOVIEWINSTR
                                                                                                                                                                                                             . '" title="'
                                                                                                                                                                                                             . _AM_INSTR_NOVIEWINSTR
                                                                                                                                                                                                             . '" />';

            $ret .= '<tr class="' . $class . '">
      <td>' . $prefix_curr . ' <a href="' . XOOPS_URL . '/modules/' . INST_DIRNAME . '/index.php?cid=' . $catid . '">' . $cattitle . '</a></td>
      <td align="center" width="50">' . $catweight . '</td>
      <td align="center" width="100">' . $num_instrs . '</td>
      <td align="center" width="150">
        ' . $act_view . '
        <a href="cat.php?op=editcat&cid=' . $catid . '"><img src="../assets/icons/edit_mini.png" alt="' . _AM_INSTRUCTION_EDIT . '" title="' . _AM_INSTRUCTION_EDIT . '"></a>
        ' . $act_del . '
      </td>
    </tr>';

            // Устанавливаем префикс
            $prefix_curr .= $prefix_orig;
        }

        if (isset($this->_tree[$key]['child']) && !empty($this->_tree[$key]['child'])) {
            foreach ($this->_tree[$key]['child'] as $childkey) {
                $this->_makeCatsAdminOptions($childkey, $ret, $prefix_orig, $cidinstrids, $class, $prefix_curr);
            }
        }
    }

    public function makeCatsAdmin($prefix = '-', $cidinstrids = [], $key = 0)
    {
        $ret = '<table width="100%" cellspacing="1" class="outer">
    <tr>
      <th align="center" colspan="4">' . _AM_INSTR_LISTALLCATS . '</th>
    </tr>
    <tr>
      <td class="head">' . _AM_INSTRUCTION_TITLE . '</td>
      <td class="head" align="center" width="50">' . _AM_INSTRUCTION_WEIGHT . '</td>
	  <td class="head" align="center" width="100">' . _AM_INSTR_INSTRS . '</td>
      <td class="head" align="center" width="150">' . _AM_INSTRUCTION_ACTION . '</td>
    </tr>';

        // Выводим все страницы
        $this->_makeCatsAdminOptions($key, $ret, $prefix, $cidinstrids);

        $ret .= '</table>';

        return $ret;
    }

    // ======================================
    // Список страниц на стороне пользователя
    // ======================================

    public function _makePagesUserTree($key, &$ret, $currpageid = 0, &$lastpageids = [], $level = 0)
    {

        // Сохраняем значение предыдущей страницы
        //static $stat_prevpages;

        if ($key > 0) {

            // ID страницы
            $pageid = $this->_tree[$key]['obj']->getVar('pageid');
            // Название страницы
            $pagetitle = $this->_tree[$key]['obj']->getVar('title');
            // Тип страницы
            $pagetype = $this->_tree[$key]['obj']->getVar('type');

            // Дочернии категории
            $page_childs =& $this->getAllChild($pageid);
            // Число дочерних страниц
            $num_childs = count($page_childs);

            // Генерируем класс
            // InstrTreeNode InstrTreeIsRoot InstrTreeExpandClosed InstrTreeIsLast
            $class = [];
            // Данный класс должен быть у любого узла
            $class[] = 'InstrTreeNode';
            // Если узел нулевого уровня, добавляем InstrTreeIsRoot
            if (0 === $level) {
                $class[] = 'InstrTreeIsRoot';
            }
            // Тип узла InstrTreeExpandClosed|InstrTreeExpandLeaf
            // Если у узла нет потомков - InstrTreeExpandLeaf
            if (0 == $num_childs) {
                $class[] = 'InstrTreeExpandLeaf';
                // Если у искомого элемента есть потомки - открываем список
            } elseif ($currpageid == $pageid) {
                $class[] = 'InstrTreeExpandOpen';
                // Если искомый элемент есть в потомках текущего, то ставим класс InstrTreeExpandOpen
            } elseif (in_array($currpageid, array_keys($page_childs))) {
                $class[] = 'InstrTreeExpandOpen';
                //
            } else {
                $class[] = 'InstrTreeExpandClosed';
            }

            // Данный класс нужно добавлять последнему узлу в каждом уровне

            if (isset($lastpageids[$level]) && ($pageid == $lastpageids[$level])) {
                $class[] = 'InstrTreeIsLast';
            }

            //$class[] = 'InstrTreeIsLast';

            // Test
            //$ret .= '<div id="' . $pageid . '">';

            // Создаём запись
            $ret .= '<li class="' . implode(' ', $class) . '">';
            //
            $ret .= '<div class="InstrTreeExpand"></div>';
            //
            $ret .= '<div class="InstrTreeContent">';

            // Если это лист дерева
            if (0 == $pagetype) {
                $ret .= '<span class="InstrTreeEmptyPage">' . $pagetitle . '</span>';
                //
            } elseif ($currpageid == $pageid) {
                $ret .= $pagetitle;
                //
            } else {
                $ret .= '<a href="' . XOOPS_URL . '/modules/' . INST_DIRNAME . '/page.php?id=' . $pageid . '#pagetext">' . $pagetitle . '</a>';
            }

            $ret .= '</div>';

            // Если есть потомки
            if ($num_childs > 0) {
                $ret .= '<ul class="InstrTreeContainer">';
            }

            // Инкримент уровня
            $level++;
        }

        // Рекурсия
        if (isset($this->_tree[$key]['child']) && !empty($this->_tree[$key]['child'])) {
            foreach ($this->_tree[$key]['child'] as $childkey) {
                $this->_makePagesUserTree($childkey, $ret, $currpageid, $lastpageids, $level);
            }
        }

        // Test
        if ($key > 0) {
            // Если есть потомки
            if ($num_childs > 0) {
                $ret .= '</ul>';
            }
            // Конец текущей записи
            $ret .= '</li>';
        }
    }

    // Находим предыдущую и следующую страницы.
    // Находим последнии страницы на каждом уровне.
    public function _makePagesUserCalc($key, $currpageid = 0, &$prevpages = [], &$nextpages = [], &$lastpageids = [], $level = 0)
    {

        // Сохраняем значение предыдущей страницы
        static $stat_prevpages;

        if ($key > 0) {
            // ID страницы
            $pageid = $this->_tree[$key]['obj']->getVar('pageid');
            // Название страницы
            $pagetitle = $this->_tree[$key]['obj']->getVar('title');
            // Тип страницы
            $pagetype = $this->_tree[$key]['obj']->getVar('type');

            // Если мы передали ID текущей страницы, то находить предыдудую и следующую страницы
            // Не находить предыдущие и следующие для "Пустой страницы"
            if ($currpageid && $pagetype) {
                // Если элемент равен текущей странице
                if (isset($stat_prevpages) && ($currpageid == $pageid)) {
                    // Забиваем массив предыдущей страницы
                    $prevpages['pageid'] = $stat_prevpages['pageid'];
                    $prevpages['title']  = $stat_prevpages['title'];

                    // Если предыдущий равен текущей странице
                } elseif (isset($stat_prevpages) && ($currpageid == $stat_prevpages['pageid'])) {
                    // Забиваем массив следующей страницы
                    $nextpages['pageid'] = $pageid;
                    $nextpages['title']  = $pagetitle;
                }
                // Заносим текущие данные в массив предыдущей страницы
                $stat_prevpages['pageid'] = $pageid;
                $stat_prevpages['title']  = $pagetitle;
            }

            // Заносим текущую страницу в массив "последних страний"
            $lastpageids[$level] = $pageid;

            // Инкримент уровня
            $level++;
        }

        // Рекурсия
        if (isset($this->_tree[$key]['child']) && !empty($this->_tree[$key]['child'])) {
            foreach ($this->_tree[$key]['child'] as $childkey) {
                $this->_makePagesUserCalc($childkey, $currpageid, $prevpages, $nextpages, $lastpageids, $level);
            }
        }
    }

    //

    public function makePagesUser($currpageid = 0, &$prevpages = [], &$nextpages = [], $key = 0)
    {

        // Массив последней страницы на каждом уровне
        // level => pageid
        $lastpageids = [];

        // Расчёт
        $this->_makePagesUserCalc($key, $currpageid, $prevpages, $nextpages, $lastpageids);

        $ret = '<div onclick="instr_tree_toggle(arguments[0])">
<div>' . _MD_INSTRUCTION_LISTPAGES . '</div>
<div><ul class="InstrTreeContainer">';

        // Генерируем дерево
        $this->_makePagesUserTree($key, $ret, $currpageid, $lastpageids);

        $ret .= '</ul>
</div>';

        return $ret;
    }
}
