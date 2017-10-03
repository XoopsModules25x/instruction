<?php

use Xmf\Request;

include_once dirname(dirname(__DIR__)) . '/mainfile.php';
require_once __DIR__ . '/include/common.php';

$com_itemid = Request::getInt('com_itemid', 0, 'GET');
if ($com_itemid > 0) {
    $itemObj       = $publisher->getHandler('item')->get($com_itemid);
    $com_replytext = _POSTEDBY . '&nbsp;<strong>' . $itemObj->getLinkedPosterName() . '</strong>&nbsp;' . _DATE . '&nbsp;<strong>' . $itemObj->dateSub() . '</strong><br><br>' . $itemObj->summary();
    $bodytext      = $itemObj->body();
    if ('' != $bodytext) {
        $com_replytext .= '<br><br>' . $bodytext . '';
    }
    $com_replytitle = $itemObj->getTitle();
    include_once $GLOBALS['xoops']->path('include/comment_new.php');
}
