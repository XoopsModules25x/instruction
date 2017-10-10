<?php namespace Xoopsmodules\instruction;

use Xmf\Request;
use Xoopsmodules\instruction\common;

require_once __DIR__ . '/common/VersionChecks.php';
require_once __DIR__ . '/common/ServerStats.php';
require_once __DIR__ . '/common/FilesManagement.php';

require_once __DIR__ . '/../include/common.php';

/**
 * Class Utility
 */
class Utility
{
    use common\VersionChecks; //checkVerXoops, checkVerPhp Traits

    use common\ServerStats; // getServerStats Trait

    use common\FilesManagement; // Files Management Trait

    // Права
    /**
     * @param string $permtype
     * @return mixed
     */
    public static function getItemIds($permtype = 'instruction_view')
    {
        //global $xoopsUser;
        static $permissions = [];
        // Если есть в статике
        if (is_array($permissions) && array_key_exists($permtype, $permissions)) {
            return $permissions[$permtype];
        }
        // Находим из базы
        $moduleHandler          = xoops_getHandler('module');
        $instrModule            = $moduleHandler->getByDirname('instruction');
        $groups                 = ($GLOBALS['xoopsUser'] instanceof \XoopsUser) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
        $gpermHandler           = xoops_getHandler('groupperm');
        $categories             = $gpermHandler->getItemIds($permtype, $groups, $instrModule->getVar('mid'));
        $permissions[$permtype] = $categories;
        return $categories;
    }

    // Редактор

    /**
     * @param        $caption
     * @param        $name
     * @param string $value
     * @return bool|\XoopsFormEditor
     */
    public static function getWysiwygForm($caption, $name, $value = '')
    {
        $editor                   = false;
        $editor_configs           = [];
        $editor_configs['name']   = $name;
        $editor_configs['value']  = $value;
        $editor_configs['rows']   = 35;
        $editor_configs['cols']   = 60;
        $editor_configs['width']  = '100%';
        $editor_configs['height'] = '350px';
        $editor_configs['editor'] = strtolower(xoops_getModuleOption('form_options', 'instruction'));

        $editor = new \XoopsFormEditor($caption, $name, $editor_configs);
        return $editor;
    }

    // Получение значения переменной, переданной через GET или POST запрос

    /**
     * @param        $global
     * @param        $key
     * @param string $default
     * @param string $type
     * @return int|string
     */
    public static function cleanVars(&$global, $key, $default = '', $type = 'int')
    {
        switch ($type) {
            case 'string':
                $ret = isset($global[$key]) ? $global[$key] : $default;
                break;
            case 'int':
            default:
                $ret = isset($global[$key]) ? (int)$global[$key] : (int)$default;
                break;
        }
        if (false === $ret) {
            return $default;
        }
        return $ret;
    }
}
