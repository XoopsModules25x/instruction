<?php namespace Xoopsmodules\instruction;

//use Xoopsmodules\instruction;

//if (!defined("XOOPS_ROOT_PATH")) {
//	die("XOOPS root path not defined");
//}

//include_once $GLOBALS['xoops']->path('include/common.php');

/**
 * Class InstructionHandler
 * @package Xoopsmodules\instruction
 */
class InstructionHandler extends \XoopsPersistableObjectHandler
{
    /**
     * @param null|mixed $db
     */
    public function __construct(\XoopsDatabase $db = null)
    {
        parent::__construct($db, 'instruction_instr', Instruction::class, 'instrid', 'title');
    }

    // Обновление даты обновления инструкций

    /**
     * @param int  $instrid
     * @param bool|int $time
     * @return mixed
     */
    public function updateDateupdated($instrid = 0, $time = null)
    {
        // Если не передали время
        $time = null === $time ? time() : (int)$time;
        //
        $sql = sprintf('UPDATE `%s` SET `dateupdated` = %u WHERE `instrid` = %u', $this->table, $time, (int)$instrid);
        //
        return $this->db->query($sql);
    }

    // Обновление числа страниц

    /**
     * @param int $instrid
     * @return mixed
     */
    public function updatePages($instrid = 0)
    {
        //        $pageHandler = xoops_getModuleHandler('page', 'instruction');
        // Находим число активных страниц
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('instrid', $instrid, '='));
        $criteria->add(new \Criteria('status ', '0', '>'));
        // Число страниц
        $pages = $pageHandler->getCount($criteria);
        unset($criteria);

        // Сохраняем это число
        $sql = sprintf('UPDATE `%s` SET `pages` = %u, `dateupdated` = %u WHERE `instrid` = %u', $this->table, $pages, time(), $instrid);
        //
        return $this->db->query($sql);
    }
}
