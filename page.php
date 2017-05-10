<?php

include __DIR__ . '/header.php';
// Подключаем трей
include_once __DIR__ . '/class/tree.php';

// Права на просмотр
//$cat_view = instr_MygetItemIds();
// Права на добавление
//$cat_submit = instr_MygetItemIds( 'instruction_submit' );
// Права на редактирование
//$cat_edit = instr_MygetItemIds( 'instruction_edit' );

$groups = is_object( $GLOBALS['xoopsUser'] ) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
$gperm_handler = xoops_gethandler('groupperm');

// Права на просмотр страницы
// ==========================

// Объявляем объекты
$insinstr_Handler = xoops_getModuleHandler( 'instruction', 'instruction' );
$inscat_Handler = xoops_getModuleHandler( 'category', 'instruction' );
$inspage_Handler = xoops_getModuleHandler( 'page', 'instruction' );

// Получаем данные
// ID страницы
$pageid = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;
// Без кэша
$nocache = instr_CleanVars( $_GET, 'nocache', 0, 'int' );

// Существует ли такая страница
$criteria = new CriteriaCompo();
$criteria->add( new Criteria( 'pageid ', $pageid ) );
$criteria->add( new Criteria( 'status ', '0', '>' ) );
if ( $inspage_Handler->getCount( $criteria ) == 0 ) {
    redirect_header( 'index.php', 3, _MD_INSTRUCTION_PAGENOTEXIST );
	exit();
}
//
unset( $criteria );

// Находим данные о страницы
$objInspage = $inspage_Handler->get( $pageid );
// Находим данные об инструкции
$objInsinstr = $insinstr_Handler->get( $objInspage->getVar( 'instrid' ) );

// Если админ и ссылка на отключение кэша
if( is_object( $GLOBALS['xoopsUser'] ) && $GLOBALS['xoopsUser']->isAdmin() && $nocache ) {
	// Отключаем кэш
	$GLOBALS['xoopsConfig']['module_cache'][$GLOBALS['xoopsModule']->getVar('mid')] = 0;
}

// Задание тайтла
$xoopsOption['xoops_pagetitle'] = $GLOBALS['xoopsModule']->name() . ' - ' . $objInsinstr->getVar('title') . ' - ' . $objInspage->getVar( 'title' );
// Шаблон
$GLOBALS['xoopsOption']['template_main'] = 'instruction_page.tpl';
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

// Массив данных о странице
$pages = array();
// Название страницы
$pages['title'] = $objInspage->getVar( 'title' );
// ID страницы
$pages['pageid'] = $objInspage->getVar( 'pageid' );
// ID инструкции
$pages['instrid'] = $objInspage->getVar( 'instrid' );
// Основной текст
$pages['hometext'] = $objInspage->getVar( 'hometext' );
// Сноска - массив строк
$footnote = $objInspage->getVar( 'footnote' );
// Если есть сноски
if( $footnote ) {
	$pages['footnotes'] = explode( '|', $objInspage->getVar( 'footnote' ) );
} else {
	$pages['footnotes'] = false;
}
// Мета-теги ключевых слов
$pages['keywords'] = $objInspage->getVar( 'keywords' );
// Мета-теги описания
$pages['description'] = $objInspage->getVar( 'description' );
//
// Если админ, рисуем админлинк
if ( is_object( $GLOBALS['xoopsUser'] ) && $GLOBALS['xoopsUser']->isAdmin( $GLOBALS['xoopsModule']->mid() ) ) {
	$pages['adminlink'] = '[&nbsp;<a href="' . XOOPS_URL . '/modules/instruction/admin/instr.php?op=editpage&pageid=' . $pages['pageid'] . '">' . _EDIT . '</a>&nbsp;|&nbsp;<a href="' . XOOPS_URL . '/modules/instruction/admin/instr.php?op=delpage&pageid=' . $pages['pageid'] . '">' . _DELETE . '</a>&nbsp;]';
} else {
	$pages['adminlink'] = '[&nbsp;';
	// Если можно редактировать
	if( $gperm_handler->checkRight( 'instruction_edit', $objInsinstr->getVar('cid'), $groups, $GLOBALS['xoopsModule']->getVar('mid') ) ) $pages['adminlink'] .= '<a href="' . XOOPS_URL . '/modules/instruction/submit.php?op=editpage&pageid=' . $pages['pageid'] . '">' . _EDIT . '</a>';
	
	$pages['adminlink'] .= '&nbsp;]';
	// Если нет админлика
	if( $pages['adminlink'] == '[&nbsp;&nbsp;]' ) $pages['adminlink'] = '';
}
// Выводим в шаблон
$GLOBALS['xoopsTpl']->assign( 'insPage', $pages );

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
$navigation .= '<a href="' . XOOPS_URL . '/modules/instruction/instr.php?id=' . $pages['instrid'] . '">' . $objInsinstr->getVar('title') . '</a>';
$xoopsTpl->assign( 'insNav', $navigation );

unset( $criteria );

// Список страниц в данной справке
$criteria = new CriteriaCompo();
$criteria->add( new Criteria( 'instrid', $pages['instrid'], '=' ) );
$criteria->add( new Criteria( 'status ', '0', '>' ) );
$criteria->setSort( 'weight' );
$criteria->setOrder( 'ASC' );
$ins_page = $inspage_Handler->getall( $criteria );
unset( $criteria );
// Предыдущая и следующая страницы
$prevpages = array();
$nextpages = array();
// Инициализируем
$instree = new InstructionTree( $ins_page, 'pageid', 'pid' );
// Выводим список страниц в шаблон
$GLOBALS['xoopsTpl']->assign( 'insListPage', $instree->makePagesUser( $pageid, $prevpages, $nextpages ) );
// Выводим в шаблон
$xoopsTpl->assign( 'insPrevpages', $prevpages );
$xoopsTpl->assign( 'insNextpages', $nextpages );
// Языковые константы
//$xoopsTpl->assign( 'lang_listpages', _MD_INSTRUCTION_LISTPAGES );
$xoopsTpl->assign( 'lang_menu', _MD_INSTRUCTION_MENU );

// Рейтинг
if ( xoops_getModuleOption( 'userat', 'instruction' ) ){
	$xoopsTpl->assign( 'insUserat', true );
} else {
	$xoopsTpl->assign( 'insUserat', false );
}

// Мета теги
$xoTheme->addMeta( 'meta', 'keywords', $objInspage->getVar( 'keywords' ) );
$xoTheme->addMeta( 'meta', 'description', $objInspage->getVar( 'description' ) );

// Комментарии
include XOOPS_ROOT_PATH . '/include/comment_view.php';
// Подвал
include XOOPS_ROOT_PATH . '/footer.php';
