<?php namespace Xoopsmodules\instruction;

// ааа

//if (!defined("XOOPS_ROOT_PATH")) {
//	die("XOOPS root path not defined");
//}

include_once $GLOBALS['xoops']->path('include/common.php');

include_once __DIR__ . '/../class/utility.php';

/**
 * Class CategoryHandler
 * @package Xoopsmodules\instruction
 */
class CategoryHandler extends \XoopsPersistableObjectHandler
{
    /**
     * @param null|mixed $db
     */
    public function __construct(\XoopsDatabase $db = null)
    {
        parent::__construct($db, 'instruction_cat', Category::class, 'cid', 'title');
    }

    // Обновление даты обновления категории

    /**
     * @param int  $cid
     * @param null|int $time
     * @return mixed
     */
    public function updateDateupdated($cid = 0, $time = null)
    {
        // Если не передали время
        $time = null === $time ? time() : (int)$time;
        //
        $sql = sprintf('UPDATE `%s` SET `dateupdated` = %u WHERE `cid` = %u', $this->table, $time, (int)$cid);
        //
        return $this->db->query($sql);
    }
}
