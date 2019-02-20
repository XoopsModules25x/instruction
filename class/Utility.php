<?php 

namespace XoopsModules\Instruction;

include dirname(__DIR__) . '/class/Common/VersionChecks.php';
include dirname(__DIR__) . '/class/Common/ServerStats.php';
include dirname(__DIR__) . '/class/Common/FilesManagement.php';

//require_once __DIR__ . '/../include/common.php';
//include dirname(__DIR__) . '/preloads/autoloader.php';
use Xmf\Request;
use XoopsModules\Instruction;
use XoopsModules\Instruction\Common;

/**
 * Class Utility
 */
class Utility extends \XoopsObject
{
    use Common\VersionChecks; //checkVerXoops, checkVerPhp Traits

    use Common\ServerStats; // getServerStats Trait

    use Common\FilesManagement; // Files Management Trait

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
}
