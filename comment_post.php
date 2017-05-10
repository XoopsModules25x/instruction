<?php

include __DIR__ . '/../../mainfile.php';
//--- verify that the user can post comments
global $xoopsModuleConfig, $xoopsUser;
if (!isset($xoopsModuleConfig)) {
    die();
}
if ($xoopsModuleConfig['com_rule'] == 0) {
    die();
}    // Comments deactivated
if ($xoopsModuleConfig['com_anonpost'] == 0 && !is_object($xoopsUser)) {
    die();
} // Anonymous users can't post

include XOOPS_ROOT_PATH . '/include/comment_post.php';