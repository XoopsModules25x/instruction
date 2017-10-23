<?php
use Xmf\Request;

include_once dirname(dirname(__DIR__)) . '/mainfile.php';
require_once __DIR__ . '/include/common.php';

$com_itemid = Request::getInt('com_itemid', 0, 'GET');
if ( $com_itemid > 0 ) {
	// Находим заголовок
	$sql = "SELECT p.title, i.cid FROM "
          . $GLOBALS['xoopsDB']->prefix('instruction_page')
          . " p, " . $GLOBALS['xoopsDB']->prefix('instruction_instr')
          . " i WHERE i.instrid = p.instrid AND p.pageid = "
          . $com_itemid;
	$result = $GLOBALS['xoopsDB']->query($sql);
	if ( $result ) {
		list( $ptitle, $icid ) = $GLOBALS['xoopsDB']->fetchRow( $result );
		// Проверка прав на доступ к данной категории
		$categories = Xoopsmodules\instruction\Utility::getItemIds();
		if( ! in_array( $icid, $categories ) ) {
			redirect_header('javascript:history.go(-1)', 3, _NOPERM );
			exit();
		}
		$com_replytitle = $ptitle;
		include $GLOBALS['xoops']->path('include/comment_new.php');
	}
}
