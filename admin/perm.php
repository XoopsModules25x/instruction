<?php
//
include __DIR__ . '/admin_header.php';
// Функции модуля
//include __DIR__ . '/../class/utility.php';

// Подключаем форму прав
include_once $GLOBALS['xoops']->path('class/xoopsform/grouppermform.php');

$adminObject = \Xmf\Module\Admin::getInstance();
// Меню
xoops_cp_header();
$adminObject->displayNavigation(basename(__FILE__));

$permission                = InstructionUtility::cleanVars($_REQUEST, 'permission', 1, 'int');
$selected                  = ['', '', ''];
$selected[$permission - 1] = ' selected';

echo "
<form method='get' name='fselperm' action='perm.php'>
    <table border=0>
        <tr>
            <td>
                <select name='permission' onChange='document.fselperm.submit()'>
                    <option value='1'" . $selected[0] . '>' . _AM_INSTRUCTION_PERM_VIEW . "</option>
                    <option value='2'" . $selected[1] . '>' . _AM_INSTRUCTION_PERM_SUBMIT . "</option>
                    <option value='3'" . $selected[2] . '>' . _AM_INSTRUCTION_PERM_EDIT . '</option>
                </select>
            </td>
        </tr>
       <tr>
    </tr>
    </table>
</form>';

$moduleId = $GLOBALS['xoopsModule']->getVar('mid');

switch ($permission) {
    // Права на просмотр
    case 1:
        $formTitle             = _AM_INSTRUCTION_PERM_VIEW;
        $permissionName        = 'instruction_view';
        $permissionDescription = _AM_INSTRUCTION_PERM_VIEW_DSC;
        break;
    // Права на добавление
    case 2:
        $formTitle             = _AM_INSTRUCTION_PERM_SUBMIT;
        $permissionName        = 'instruction_submit';
        $permissionDescription = _AM_INSTRUCTION_PERM_SUBMIT_DSC;
        break;
    // Права на редактирование
    case 3:
        $formTitle             = _AM_INSTRUCTION_PERM_EDIT;
        $permissionName        = 'instruction_edit';
        $permissionDescription = _AM_INSTRUCTION_PERM_EDIT_DSC;
        break;
}

// Права
$permissionsForm = new XoopsGroupPermForm($formTitle, $moduleId, $permissionName, $permissionDescription, 'admin/perm.php?permission=' . $permission);

$sql    = 'SELECT cid, pid, title FROM ' . $xoopsDB->prefix('instruction_cat') . ' ORDER BY title';
$result = $xoopsDB->query($sql);
if ($result) {
    while ($row = $xoopsDB->fetchArray($result)) {
        $permissionsForm->addItem($row['cid'], $row['title'], $row['pid']);
    }
}

echo $permissionsForm->render();
unset($permissionsForm);

// Текст внизу админки
include __DIR__ . '/admin_footer.php';
