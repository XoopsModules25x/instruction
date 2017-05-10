<?php

include __DIR__ . 'header.php';
$com_itemid = isset( $_GET['com_itemid'] ) ? intval( $_GET['com_itemid'] ) : 0;
if ( $com_itemid > 0 ) {
	// Находим заголовок
	$sql = "SELECT p.title, i.cid FROM " . $GLOBALS['xoopsDB']->prefix('instruction_page') . " p, " . $GLOBALS['xoopsDB']->prefix('instruction_instr') . " i WHERE i.instrid = p.instrid AND p.pageid = " . $com_itemid;
	$result = $GLOBALS['xoopsDB']->query($sql);
	if ( $result ) {
		list( $ptitle, $icid ) = $GLOBALS['xoopsDB']->fetchRow( $result );
		// Проверка прав на доступ к данной категории
		$categories = instr_MygetItemIds();
		if( ! in_array( $icid, $categories ) ) {
			redirect_header( XOOPS_URL, 3, _NOPERM );
			exit();
		}
		$com_replytitle = $ptitle;
		include XOOPS_ROOT_PATH . '/include/comment_new.php';
	}
}

