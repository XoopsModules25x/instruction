<?php

include __DIR__ . '/header.php';
// Подключаем трей
include_once XOOPS_ROOT_PATH . '/modules/instruction/class/tree.php';
include_once XOOPS_ROOT_PATH . '/class/tree.php';

// Объявляем объекты
$insinstr_Handler = xoops_getModuleHandler( 'instruction', 'instruction' );
$inscat_Handler = xoops_getModuleHandler( 'category', 'instruction' );
$inspage_Handler = xoops_getModuleHandler( 'page', 'instruction' );

$instrid = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;

// Существует ли такая инструкция
$criteria = new CriteriaCompo();
$criteria->add( new Criteria( 'instrid', $instrid ) );
$criteria->add( new Criteria( 'status ', '0', '>' ) );
if ( $insinstr_Handler->getCount( $criteria ) == 0 ) {
    redirect_header( 'index.php', 3, _MD_INSTRUCTION_INSTRNOTEXIST );
	exit();
}
//
unset( $criteria );

// Находим данные об инструкции
$objInsinstr = $insinstr_Handler->get( $instrid );

// Задание тайтла
$xoopsOption['xoops_pagetitle'] = $GLOBALS['xoopsModule']->name() . ' - ' . $objInsinstr->getVar( 'title' );
// Шаблон
$GLOBALS['xoopsOption']['template_main'] = 'instruction_instr.tpl';
// Заголовок
include XOOPS_ROOT_PATH . '/header.php';
// Стили
$xoTheme->addStylesheet( XOOPS_URL . '/modules/instruction/assets/css/style.css' );
// Скрипты
$xoTheme->addScript( XOOPS_URL . '/modules/instruction/assets/js/tree.js' );

// Права на просмотр инструкции
$categories = instr_MygetItemIds();
if( ! in_array( $objInsinstr->getVar('cid'), $categories ) ) {
	redirect_header( XOOPS_URL . '/modules/instruction/', 3, _NOPERM );
	exit();
}


// Массив данных об инструкции
$instrs = array();
// ID инструкции
$instrs['instrid'] = $objInsinstr->getVar( 'instrid' );
// Название страницы
$instrs['title'] = $objInsinstr->getVar( 'title' );
// Описание
$instrs['description'] = $objInsinstr->getVar( 'description' );
// Если админ, рисуем админлинк
if ( is_object( $GLOBALS['xoopsUser'] ) && $GLOBALS['xoopsUser']->isAdmin( $GLOBALS['xoopsModule']->mid() ) ) {
	$instrs['adminlink'] = '[&nbsp;<a href="' . XOOPS_URL . '/modules/instruction/admin/instr.php?op=editinstr&instrid=' . $instrid . '">' . _EDIT . '</a>&nbsp;|&nbsp;<a href="' . XOOPS_URL . '/modules/instruction/admin/instr.php?op=delinstr&instrid=' . $instrid . '">' . _DELETE . '</a>&nbsp;]';
} else {
	$instrs['adminlink'] = '';
}

// Выводим в шаблон
$GLOBALS['xoopsTpl']->assign( 'insInstr', $instrs );

// Мета теги
$xoTheme->addMeta( 'meta', 'keywords', $objInsinstr->getVar( 'metakeywords' ) );
$xoTheme->addMeta( 'meta', 'description', $objInsinstr->getVar( 'metadescription' ) );

// Находим данные об категории
$objInscat = $inscat_Handler->get( $objInsinstr->getVar( 'cid' ) );

// Навигация
$criteria = new CriteriaCompo();
$criteria->setSort('weight ASC, title');
$criteria->setOrder('ASC');
$inscat_arr = $inscat_Handler->getall( $criteria );
$mytree = new XoopsObjectTree( $inscat_arr, 'cid', 'pid' );
$nav_parent_id = $mytree->getAllParent( $objInsinstr->getVar( 'cid' ) );
$titre_page = $nav_parent_id;
$nav_parent_id = array_reverse( $nav_parent_id );
$navigation = '<a href="' . XOOPS_URL . '/modules/instruction/">' . $GLOBALS['xoopsModule']->name() . '</a>&nbsp;:&nbsp;';
foreach ( array_keys( $nav_parent_id ) as $i ) {
	$navigation .= '<a href="' . XOOPS_URL . '/modules/instruction/index.php?cid=' . $nav_parent_id[$i]->getVar( 'cid' ) . '">' . $nav_parent_id[$i]->getVar( 'title' ) . '</a>&nbsp;:&nbsp;';
}
$navigation .= '<a href="' . XOOPS_URL . '/modules/instruction/index.php?cid=' . $objInscat->getVar('cid') . '">' . $objInscat->getVar('title') . '</a>&nbsp;:&nbsp;';
$navigation .= $objInsinstr->getVar('title');
$xoopsTpl->assign( 'insNav', $navigation );
//
unset( $criteria );

// Список страниц в данной инструкции
$criteria = new CriteriaCompo();
$criteria->add( new Criteria( 'instrid', $instrid, '=' ) );
$criteria->add( new Criteria( 'status ', '0', '>' ) );
$criteria->setSort( 'weight' );
$criteria->setOrder( 'ASC' );
$ins_page = $inspage_Handler->getall( $criteria );
unset( $criteria );
// Инициализируем
$instree = new InstructionTree( $ins_page, 'pageid', 'pid' );
// Выводим список страниц в шаблон
$GLOBALS['xoopsTpl']->assign( 'insListPage', $instree->makePagesUser() );

// Языковые константы
$xoopsTpl->assign( 'lang_listpages', _MD_INSTRUCTION_LISTPAGES );
$xoopsTpl->assign( 'lang_menu', _MD_INSTRUCTION_MENU );

// Теги
if ( xoops_getModuleOption( 'usetag', 'instruction') ){
	require_once XOOPS_ROOT_PATH . '/modules/tag/include/tagbar.php';
	$xoopsTpl->assign( 'tags', true );
	$xoopsTpl->assign( 'tagbar', tagBar( $instrid, 0 ) );
} else {
	$xoopsTpl->assign( 'tags', false );
}

// Рейтинг
if ( xoops_getModuleOption( 'userat', 'instruction' ) ){
	$xoopsTpl->assign( 'insUserat', true );
} else {
	$xoopsTpl->assign( 'insUserat', false );
}


// Подвал
include XOOPS_ROOT_PATH . '/footer.php';

?>