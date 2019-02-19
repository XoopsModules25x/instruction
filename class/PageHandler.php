<?php namespace XoopsModules\Instruction;

//if (!defined("XOOPS_ROOT_PATH")) {
//	die("XOOPS root path not defined");
//}

include_once $GLOBALS['xoops']->path('include/common.php');

/**
 * Class PageHandler
 * @package Xoopsmodules\instruction
 */
class PageHandler extends \XoopsPersistableObjectHandler
{
    /**
     * @param null|mixed $db
     */
    public function __construct(\XoopsDatabase $db = null)
    {
        parent::__construct($db, 'instruction_page', Page::class, 'pageid', 'title');
    }

    /**
     * Generate function for update user post
     *
     * @ Update user post count after send approve content
     * @ Update user post count after change status content
     * @ Update user post count after delete content
     * @param $uid
     * @param $status
     * @param $action
     */
    public function updatePosts($uid, $status, $action)
    {
        //
        switch ($action) {
            // Добавление страницы
            case 'add':
                if ($uid && $status) {
                    $user          = new \XoopsUser($uid);
                    $memberHandler = xoops_getHandler('member');
                    // Добавялем +1 к комментам
                    $memberHandler->updateUserByField($user, 'posts', $user->getVar('posts') + 1);
                }
                break;
            // Удаление страницы
            case 'delete':
                if ($uid && $status) {
                    $user          = new \XoopsUser($uid);
                    $memberHandler = xoops_getHandler('member');
                    // Декримент комментов
                    //$user->setVar( 'posts', $user->getVar( 'posts' ) - 1 );
                    // Сохраняем
                    $memberHandler->updateUserByField($user, 'posts', $user->getVar('posts') - 1);
                }
                break;

            case 'status':
                if ($uid) {
                    $user          = new \XoopsUser($uid);
                    $memberHandler = xoops_getHandler('member');
                    if ($status) {
                        $memberHandler->updateUserByField($user, 'posts', $user->getVar('posts') - 1);
                    } else {
                        $memberHandler->updateUserByField($user, 'posts', $user->getVar('posts') + 1);
                    }
                }
                break;
        }
    }
}
