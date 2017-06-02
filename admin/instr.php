<?php
//
include __DIR__ . '/admin_header.php';
// Функции модуля
include '../include/functions.php';
// Пагинатор
include_once XOOPS_ROOT_PATH . '/class/pagenav.php';

// Admin Gui
$indexAdmin = new ModuleAdmin();

// Объявляем объекты
$insinstr_Handler = xoops_getModuleHandler( 'instruction', 'instruction' );
$inscat_Handler = xoops_getModuleHandler( 'category', 'instruction' );
$inspage_Handler = xoops_getModuleHandler( 'page', 'instruction' );

//
$uid = is_object( $GLOBALS['xoopsUser'] ) ? $GLOBALS['xoopsUser']->getVar('uid') : 0;
$time = time();

// ID инструкции
$instrid = instr_CleanVars( $_REQUEST, 'instrid', 0, 'int');
// ID страницы
$pageid = instr_CleanVars( $_REQUEST, 'pageid', 0, 'int');
// ID категории
$cid = instr_CleanVars( $_REQUEST, 'cid', 0, 'int');
// Вес
$weight = instr_CleanVars( $_POST, 'weight', 0, 'int');
//
$pid = instr_CleanVars( $_REQUEST, 'pid', 0, 'int');
//
$start = instr_CleanVars( $_GET, 'start', 0, 'int');
//
$limit = xoops_getModuleOption( 'perpageadmin', 'instruction');

$op = isset($_GET['op']) ? $_GET['op'] : 'main';
$op = isset($_POST['op']) ? $_POST['op'] : $op;
// Выбор
switch ( $op ) {

	case 'main':
		
		// Заголовок админки
		xoops_cp_header();
		// Меню
		//loadModuleAdminMenu( 2, _AM_INSTRUCTION_BC_LISTINSTR );
		$xoopsTpl->assign( 'insNavigation', $indexAdmin->addNavigation('instr.php') );
		// Кнопки
		$indexAdmin->addItemButton( _AM_INSTRUCTION_ADDINSTR, 'instr.php?op=editinstr', 'add' );
		$xoopsTpl->assign( 'insButton', $indexAdmin->renderButton() );
		
		
		//
		$criteria = new CriteriaCompo();
		
		// Если была передана категория
		if( $cid ) {
			// Добавляем в выборку ID категории
			$criteria->add( new Criteria( 'cid', $cid, '=' ) );
			// Получаем объект категории
			$objInscat =& $inscat_Handler->get( $cid );
			// Если нет такой категории
			if( ! is_object( $objInscat ) ) redirect_header( 'cat.php', 3, _AM_INSTRUCTION_ERR_CATNOTSELECT );
		}
		
		// Число инструкций, удовлетворяющих данному условию
		$numrows = $insinstr_Handler->getCount( $criteria );
		
		// Число выборки
		$criteria->setLimit( $limit );
		// Начинасть с данного элемента
		$criteria->setStart( $start );
		// Сортировать по
		$criteria->setSort( 'instrid' );
		// Порядок сортировки
		$criteria->setOrder( 'DESC' );
		// Находим все справки
		$instr_arr = $insinstr_Handler->getall( $criteria );
		// Если записей больше чем $limit, то выводим пагинатор
		if ( $numrows > $limit ) {
			$pagenav = new XoopsPageNav( $numrows, $limit, $start, 'start', 'op=' . $op . '&amp;cid=' . $cid );
 			$pagenav = $pagenav->renderNav(4);
 		} else {
 			$pagenav = '';
 		}
		// Выводим пагинатор в шаблон
		$GLOBALS['xoopsTpl']->assign( 'insPagenav', $pagenav );
		
		// Если есть записи
		if ( $numrows > 0 ) {
			
			$class = 'odd';
			foreach ( array_keys( $instr_arr ) as $i ) {
				
				//
				$class = ($class == 'even') ? 'odd' : 'even';
				// ID
				$insinstr_instrid = $instr_arr[$i]->getVar('instrid');
				// Название
				$insinstr_title = $instr_arr[$i]->getVar('title');
				// Статус
				$insinstr_status = $instr_arr[$i]->getVar('status');
				// Количество страниц
				$insinstr_pages = $instr_arr[$i]->getVar('pages');
				// Категория
				$insinstr_cat =& $inscat_Handler->get( $instr_arr[$i]->getVar('cid') );
				
				// Выводим в шаблон
				$GLOBALS['xoopsTpl']->append( 'insListInstr', array( 'instrid' => $insinstr_instrid, 'title' => $insinstr_title, 'status' => $insinstr_status, 'pages' => $insinstr_pages, 'ctitle' => $insinstr_cat->getVar( 'title' ), 'cid' => $insinstr_cat->getVar( 'cid' ), 'class' => $class ) );
				
			}
			
			//
			$inshead = isset( $objInscat ) && is_object( $objInscat ) ? sprintf( _AM_INSTR_LISTINSTRINCAT, $objInscat->getVar('title') ) : _AM_INSTR_LISTINSTRALL;
			$GLOBALS['xoopsTpl']->assign( 'insHead', $inshead );
			// Языковые константы
			$GLOBALS['xoopsTpl']->assign( 'lang_title', _AM_INSTRUCTION_TITLE );
			$GLOBALS['xoopsTpl']->assign( 'lang_cat', _AM_INSTRUCTION_CAT );
			$GLOBALS['xoopsTpl']->assign( 'lang_pages', _AM_INSTRUCTION_PAGES );
			$GLOBALS['xoopsTpl']->assign( 'lang_action', _AM_INSTRUCTION_ACTION );
			$GLOBALS['xoopsTpl']->assign( 'lang_display', _AM_INSTRUCTION_DISPLAY );
			$GLOBALS['xoopsTpl']->assign( 'lang_edit', _AM_INSTRUCTION_EDIT );
			$GLOBALS['xoopsTpl']->assign( 'lang_del', _AM_INSTRUCTION_DEL );
			$GLOBALS['xoopsTpl']->assign( 'lang_lock', _AM_INSTRUCTION_LOCK );
			$GLOBALS['xoopsTpl']->assign( 'lang_unlock', _AM_INSTRUCTION_UNLOCK );
			$GLOBALS['xoopsTpl']->assign( 'lang_addpage', _AM_INSTRUCTION_ADDPAGE );
			$GLOBALS['xoopsTpl']->assign( 'lang_addinstr', _AM_INSTRUCTION_ADDINSTR );
			
		}

		// Выводим шаблон
		$GLOBALS['xoopsTpl']->display("db:instruction_admin_instr.tpl");
		
		// Текст внизу админки
		include __DIR__ . '/admin_footer.php';
		// Подвал админки
		xoops_cp_footer();
		
		break;
	
	// Редактирование категории
	case 'editinstr':
		
		// Заголовок админки
		xoops_cp_header();
		// Меню
		//loadModuleAdminMenu( 2, _AM_INSTRUCTION_BC_EDITINSTR );
		$xoopsTpl->assign( 'insNavigation', $indexAdmin->addNavigation('instr.php') );
		
		// Если мы редактируем инструкцию
		if( $instrid ) {
			$objInsinstr =& $insinstr_Handler->get( $instrid );
		// Создание новой страницы
		} else {
			$objInsinstr =& $insinstr_Handler->create();
		}
		
    	$form = $objInsinstr->getForm( 'instr.php' );
    	// Форма
		$GLOBALS['xoopsTpl']->assign( 'insFormInstr', $form->render() );
		
		// Выводим шаблон
		$GLOBALS['xoopsTpl']->display("db:instruction_admin_editinstr.tpl");
		
		// Текст внизу админки
		include __DIR__ . '/admin_footer.php';
		// Подвал админки
		xoops_cp_footer();
		
		break;
	
	// Сохранение инструкций
	case 'saveinstr':
		
		// Проверка
		if ( ! $GLOBALS['xoopsSecurity']->check() ) {
			redirect_header( 'instr.php', 3, implode( ',', $GLOBALS['xoopsSecurity']->getErrors() ) );
		}
		// Если мы редактируем
		if ( $instrid ) {
			$objInsinstr =& $insinstr_Handler->get( $instrid );
		} else {
			$objInsinstr =& $insinstr_Handler->create();
			// Указываем дату создания
			$objInsinstr->setVar( 'datecreated', $time );
			// Указываем пользователя
			$objInsinstr->setVar( 'uid', $uid );
		}
		
		$err = false;
		$message_err = '';
		//
		$instr_title = instr_CleanVars( $_POST, 'title', '', 'string' );
		$instr_description = instr_CleanVars( $_POST, 'description', '', 'string' );
		
		// Дата обновления
		$objInsinstr->setVar( 'dateupdated', $time );
		//
		$objInsinstr->setVar( 'cid', $cid );
		$objInsinstr->setVar( 'title', $instr_title );
		$objInsinstr->setVar( 'status', $_POST['status'] );
		$objInsinstr->setVar( 'description', $instr_description );
		$objInsinstr->setVar( 'metakeywords', $_POST['metakeywords'] );
		$objInsinstr->setVar( 'metadescription', $_POST['metadescription'] );
		
		// Проверка категорий
		if ( ! $cid ) {
			$err = true;
			$message_err .= _AM_INSTRUCTION_ERR_CAT . '<br />';
		}
		// Проверка названия
		if ( !$instr_title ) {
			$err = true;
			$message_err .= _AM_INSTR_ERR_TITLE . '<br />';
		}
		// Проверка основного текста
		if ( !$instr_description ) {
			$err = true;
			$message_err .= _AM_INSTR_ERR_DESCRIPTION . '<br />';
		}
		
		// Если были ошибки
		if ( $err == true ) {
			xoops_cp_header();
			// Меню страницы
			//loadModuleAdminMenu( 2, _AM_INSTRUCTION_BC_EDITINSTR );
			$xoopsTpl->assign( 'insNavigation', $indexAdmin->addNavigation('instr.php') );
			
			$message_err = '<div class="errorMsg" style="text-align: left;">' . $message_err . '</div>';
			// Выводим ошибки в шаблон
			$GLOBALS['xoopsTpl']->assign( 'insErrorMsg', $message_err );
		// Если небыло ошибок
		} else {
			// Вставляем данные в БД
			if ( $insinstr_Handler->insert( $objInsinstr ) ) {
				// Получаем ID созданной записи
				$instrid_new = $instrid ? $instrid : $objInsinstr->get_new_enreg();
				// Обновление даты в категории
				$inscat_Handler->updateDateupdated( $cid, $time );
				// Тэги
				if ( xoops_getModuleOption( 'usetag', 'instruction') ) {
					$tag_handler = xoops_getmodulehandler( 'tag', 'tag' );
					$tag_handler->updateByItem( $_POST['tag'], $instrid_new, $GLOBALS['xoopsModule']->getVar('dirname'), 0 );
                }
				
				// Если мы редактируем
				if ( $instrid ) {
					redirect_header( 'instr.php', 3, _AM_INSTRUCTION_INSTRMODIFY );
				} else {
					redirect_header( 'instr.php', 3, _AM_INSTRUCTION_INSTRADDED );
				}
			}
			xoops_cp_header();
			// Меню страницы
			//loadModuleAdminMenu( 2, _AM_INSTRUCTION_BC_EDITINSTR );
			$xoopsTpl->assign( 'insNavigation', $indexAdmin->addNavigation('instr.php') );
			
			// Выводим ошибки в шаблон
			$GLOBALS['xoopsTpl']->assign( 'insErrorMsg', $objInstructioncat->getHtmlErrors() );
		}
		// Выводим форму
		$form =& $objInsinstr->getForm();
		// Форма
		$GLOBALS['xoopsTpl']->assign( 'insFormInstr', $form->render() );
		
		// Выводим шаблон
		$GLOBALS['xoopsTpl']->display("db:instruction_admin_saveinstr.tpl");
		
		// Текст внизу админки
		include __DIR__ . '/admin_footer.php';
		// Подвал админки
		xoops_cp_footer();
		
		break;
		
	// Просмотр категории
	case 'viewinstr':
		
		// Подключаем трей
		include_once XOOPS_ROOT_PATH . '/modules/instruction/class/tree.php';
		
		// Заголовок админки
		xoops_cp_header();
		// Меню
		//loadModuleAdminMenu( 2, _AM_INSTRUCTION_BC_LISTPAGE );
		$xoopsTpl->assign( 'insNavigation', $indexAdmin->addNavigation('instr.php') );
		// Кнопки
		$indexAdmin->addItemButton( _AM_INSTRUCTION_ADDPAGE, 'instr.php?op=editpage&instrid=' . $instrid, 'add' );
		$xoopsTpl->assign( 'insButton', $indexAdmin->renderButton() );
		
		//
		$objInsinstr =& $insinstr_Handler->get( $instrid );
		
		// Находим все страницы в данной инструкции
		$criteria = new CriteriaCompo();
		$criteria->add( new Criteria( 'instrid', $instrid, '=' ) );
		$criteria->setSort( 'weight' );
		$criteria->setOrder( 'ASC' );
		$ins_page = $inspage_Handler->getall( $criteria );
		//
		unset( $criteria );
		
		// Инициализируем
		$instree = new InstructionTree( $ins_page, 'pageid', 'pid' );
		// Выводим список страниц в шаблон
		$GLOBALS['xoopsTpl']->assign( 'insListPage', $instree->makePagesAdmin( $objInsinstr, '--' ) );
		
		// Выводим шаблон
		$GLOBALS['xoopsTpl']->display("db:instruction_admin_viewinstr.tpl");
		
		// Текст внизу админки
		include __DIR__ . '/admin_footer.php';
		// Подвал админки
		xoops_cp_footer();
		
		break;
	
	// Удаление категории
	case 'delinstr':
		
		// Проверка на instrid
		// ==================
		// Объект инструкций
		$objInsinstr =& $insinstr_Handler->get( $instrid );
		
		// Нажали ли мы на кнопку OK
		$ok = isset( $_POST['ok'] ) ? intval( $_POST['ok'] ) : 0;
		//
		if( $ok ) {
			
			// Проверка
			if ( ! $GLOBALS['xoopsSecurity']->check() ) {
				redirect_header( 'instr.php', 3, implode( ',', $GLOBALS['xoopsSecurity']->getErrors() ) );
			}
			// Находим все страницы, пренадлежащие этой инструкции
			$criteria = new CriteriaCompo();
			$criteria->add( new Criteria( 'instrid', $instrid ) );
			$ins_page = $inspage_Handler->getall( $criteria );
			//
			unset( $criteria );
			// Перебираем все страницы в данной инструкции
			foreach ( array_keys( $ins_page ) as $i ) {
				// Декримент комментов
				// Делает дикримент одного коммента, а не всех в цикле...
				//$inspage_Handler->updateposts( $ins_page[$i]->getVar( 'uid' ), $ins_page[$i]->getVar( 'status' ), 'delete' );
				// Удаляем комментарии
				xoops_comment_delete( $GLOBALS['xoopsModule']->getVar('mid'), $ins_page[$i]->getVar( 'pageid' ) );
				// Декримент страниц (Опционально)
				// ==============================
				
				// Удаляем страницу
				// Сделать проверку на удалённость страницы
				// ========================================
				$inspage_Handler->delete( $ins_page[$i] );
				
			}
			// Пытаемся удалить инструкцию
			if( $insinstr_Handler->delete( $objInsinstr ) ){
				// Редирект
				redirect_header( 'instr.php', 3, _AM_INSTRUCTION_INSTRDELETED );
				
			} else {
				// Редирект
				redirect_header( 'instr.php', 3, _AM_INSTRUCTION_ERR_DELINSTR );
			}
			
			
		} else {
			
			xoops_cp_header();
			//loadModuleAdminMenu( 2, _AM_INSTRUCTION_BC_DELINSTR );
			$xoopsTpl->assign( 'insNavigation', $indexAdmin->addNavigation('instr.php') );
			// Форма
			xoops_confirm( array( 'ok' => 1, 'instrid' => $instrid, 'op' => 'delinstr' ), 'instr.php', sprintf( _AM_INSTRUCTION_FORMDELINSTR, $objInsinstr->getVar('title') ) );
			// Текст внизу админки
			include __DIR__ . '/admin_footer.php';
			// Подвал админки
			xoops_cp_footer();
			
		}
		
		break;
	
	// Добавление страницы
	case 'editpage':
		
		// Заголовок админки
		xoops_cp_header();
		// Скрипты
		$xoTheme->addScript( XOOPS_URL . '/modules/instruction/assets/js/admin.js' );
		// Меню
		//loadModuleAdminMenu( 2, _AM_INSTRUCTION_BC_EDITPAGE );
		$xoopsTpl->assign( 'insNavigation', $indexAdmin->addNavigation('instr.php') );
		
		// Если мы редактируем страницу
		if( $pageid ) {
			// Получаем объект страницы
			$objInspage =& $inspage_Handler->get( $pageid );
			// ID инструкции
			$instrid = $objInspage->getVar( 'instrid' );
		// Создание новой страницы
		} elseif( $instrid ) {
			// Создаём объект страницы
			$objInspage =& $inspage_Handler->create();
			// Устанавливаем родительскую страницу
			$objInspage->setVar( 'pid', $pid );
		} else {
			redirect_header( 'instr.php', 3, _AM_INSTRUCTION_BADREQUEST );
		}
		// Форма
		$form =& $objInspage->getForm( 'instr.php', $instrid );
		// Форма
		$GLOBALS['xoopsTpl']->assign( 'insFormPage', $form->render() );
		
		// Выводим шаблон
		$GLOBALS['xoopsTpl']->display("db:instruction_admin_editpage.tpl");
		
		// Текст внизу админки
		include __DIR__ . '/admin_footer.php';
		// Подвал админки
		xoops_cp_footer();
		
		break;
	
	// Сохранение страницы
	case 'savepage':
		// Ошибки
		$err = false;
		$message_err = '';
		
		// Проверка сессии
		if ( ! $GLOBALS['xoopsSecurity']->check() ) {
			$err = true;
			$err_txt = implode( ', ', $GLOBALS['xoopsSecurity']->getErrors() );
			$message_err .= $err_txt . '<br />';
		}
		
		// Если мы редактируем
		if ( $pageid ) {
			$objInspage =& $inspage_Handler->get( $pageid );
		} elseif( $instrid ) {
			$objInspage =& $inspage_Handler->create();
			// Если мы создаём страницу необходимо указать к какой инструкции
			$objInspage->setVar( 'instrid', $instrid );
			// Указываем дату создания
			$objInspage->setVar( 'datecreated', $time );
			// Указываем пользователя
			$objInspage->setVar( 'uid', $uid );
		} else {
			redirect_header( 'instr.php', 3, _AM_INSTRUCTION_BADREQUEST );
		}
		
		//
		$page_title = instr_CleanVars( $_POST, 'title', '', 'string' );
		$page_hometext = instr_CleanVars( $_POST, 'hometext', '', 'string' );
		
		// Родительская страница
		$objInspage->setVar( 'pid', $pid );
		// Дата обновления
		$objInspage->setVar( 'dateupdated', $time );
		// Название страницы
		$objInspage->setVar( 'title', $page_title );
		// Вес страницы
		$objInspage->setVar( 'weight', $weight );
		// Основной текст
		$objInspage->setVar( 'hometext', $page_hometext );
		// Сноска
		$objInspage->setVar( 'footnote', instr_CleanVars( $_POST, 'footnote', '', 'string' ) );
		// Статус
		$objInspage->setVar( 'status', instr_CleanVars( $_POST, 'status', 0, 'int' ) );
		// Тип
		$objInspage->setVar( 'type', instr_CleanVars( $_POST, 'type', 0, 'int' ) );
		// Мета-теги описания
		$objInspage->setVar( 'keywords', instr_CleanVars( $_POST, 'keywords', '', 'string' ) );
		// Мета-теги ключевых слов
		$objInspage->setVar( 'description', instr_CleanVars( $_POST, 'description', '', 'string' ) );
		//
		$dosmiley = ( isset( $_POST['dosmiley'] ) && intval( $_POST['dosmiley'] ) > 0 ) ? 1 : 0;
		$doxcode = ( isset( $_POST['doxcode'] ) && intval( $_POST['doxcode'] ) > 0 ) ? 1 : 0;
		$dobr = ( isset( $_POST['dobr'] ) && intval( $_POST['dobr'] ) > 0 ) ? 1 : 0;
		$dohtml = ( isset( $_POST['dohtml'] ) && intval( $_POST['dohtml'] ) > 0 ) ? 1 : 0;
		//$doimage = ( isset( $_POST['doimage'] ) && intval( $_POST['doimage'] ) > 0 ) ? 1 : 0;
		$objInspage->setVar( 'dohtml', $dohtml );
		$objInspage->setVar( 'dosmiley', $dosmiley );
		$objInspage->setVar( 'doxcode', $doxcode );
		//$objInspage->setVar( 'doimage', $doimage );
		$objInspage->setVar( 'dobr', $dobr );
		
		//
		if ( ! $pageid && ! $instrid ) {
			$err = true;
			$message_err .= _AM_INSTRUCTION_ERR_INSTR . '<br />';
		}
		// Проверка веса
		if ( $weight == 0 ){
			$err = true;
			$message_err .= _AM_INSTRUCTION_ERR_WEIGHT . '<br />';
		}
		// Проверка родительской страницы
		if ( $pageid && ( $pageid == $pid ) ) {
			$err = true;
			$message_err .= _AM_INSTRUCTION_ERR_PPAGE . '<br />';
		}
		// Проверка названия
		if ( !$page_title ) {
			$err = true;
			$message_err .= _AM_INSTR_ERR_TITLE . '<br />';
		}
		// Проверка основного текста
		if ( !$page_hometext ) {
			$err = true;
			$message_err .= _AM_INSTR_ERR_HOMETEXT . '<br />';
		}
		
		// Если были ошибки
		if ( $err == true ) {
			xoops_cp_header();
			// Меню страницы
			//loadModuleAdminMenu( 2, _AM_INSTRUCTION_BC_EDITPAGE );
			$xoopsTpl->assign( 'insNavigation', $indexAdmin->addNavigation('instr.php') );
			
			$message_err = '<div class="errorMsg" style="text-align: left;">' . $message_err . '</div>';
			// Выводим ошибки в шаблон
			$GLOBALS['xoopsTpl']->assign( 'insErrorMsg', $message_err );
		// Если небыло ошибок
		} else {
			// Вставляем данные в БД
			if ( $inspage_Handler->insert( $objInspage ) ) {
				// Ссылка для редиректа
				$redirect_url = 'instr.php?op=viewinstr&amp;instrid=' . $instrid . '#pageid_' . $pid;
				// Получаем ID инструкции
				$instrid = $objInspage->getInstrid();
				// Обновляем в инструкции число страниц и дату
				$insinstr_Handler->updatePages( $instrid );
				// Если мы редактируем
				if ( $pageid ) {
					// Редирект
					redirect_header( $redirect_url, 3, _AM_INSTRUCTION_PAGEMODIFY );
				// Если мы добавляем
				} else {
					// Инкримент комментов
					$inspage_Handler->updateposts( $uid, $_POST['status'], 'add' );
					// Редирект
					redirect_header( $redirect_url, 3, _AM_INSTRUCTION_PAGEADDED );
				}
			}
			xoops_cp_header();
			// Меню страницы
			//loadModuleAdminMenu( 2, _AM_INSTRUCTION_BC_EDITPAGE );
			$xoopsTpl->assign( 'insNavigation', $indexAdmin->addNavigation('instr.php') );
			
			// Выводим ошибки в шаблон
			$GLOBALS['xoopsTpl']->assign( 'insErrorMsg', $objInspage->getHtmlErrors() );
		}
		// Скрипты
		$xoTheme->addScript( XOOPS_URL . '/modules/instruction/assets/js/admin.js' );
		// Выводим форму
		$form =& $objInspage->getForm( 'instr.php', $instrid );
		// Форма
		$GLOBALS['xoopsTpl']->assign( 'insFormPage', $form->render() );
		
		// Выводим шаблон
		$GLOBALS['xoopsTpl']->display("db:instruction_admin_savepage.tpl");
		
		// Текст внизу админки
		include __DIR__ . '/admin_footer.php';
		// Подвал админки
		xoops_cp_footer();
		
		break;
	
	// Удаление страницы
	case 'delpage':
		
		// Проверка на pageid
		// ==================
		
		$objInspage =& $inspage_Handler->get( $pageid );
		// Нажали ли мы на кнопку OK
		$ok = isset( $_POST['ok'] ) ? intval( $_POST['ok'] ) : 0;
		// Если мы нажали на кнопку
		if( $ok ) {
			
			// Проверка
			if ( ! $GLOBALS['xoopsSecurity']->check() ) {
				redirect_header( 'instr.php', 3, implode( ',', $GLOBALS['xoopsSecurity']->getErrors() ) );
			}
			// ID инструкции
			$page_instrid = $objInspage->getVar('instrid');
			// Декримент комментов
			$inspage_Handler->updateposts( $objInspage->getVar( 'uid' ), $objInspage->getVar( 'status' ), 'delete' );
			// Пытаемся удалить страницу
			if( $inspage_Handler->delete( $objInspage ) ) {
				// Обновляем в инструкции число страниц и дату
				$insinstr_Handler->updatePages( $page_instrid );
				// Удаляем комментарии
				xoops_comment_delete( $GLOBALS['xoopsModule']->getVar('mid'), $pageid );
				//
				redirect_header( 'instr.php?op=viewinstr&amp;instrid=' . $page_instrid, 3, _AM_INSTRUCTION_PAGEDELETED );
			// Если не смогли удалить страницу
			} else {
				
				redirect_header( 'instr.php?op=viewinstr&amp;instrid=' . $page_instrid, 3, _AM_INSTRUCTION_ERR_DELPAGE );
				
			}
			
		} else {
			
			// Заголовок админки
			xoops_cp_header();
			// Меню
			//loadModuleAdminMenu( 2, _AM_INSTRUCTION_BC_DELPAGE );
			$xoopsTpl->assign( 'insNavigation', $indexAdmin->addNavigation('instr.php') );
			// Форма
			xoops_confirm( array( 'ok' => 1, 'pageid' => $pageid, 'op' => 'delpage' ), 'instr.php', sprintf( _AM_INSTRUCTION_FORMDELPAGE, $objInspage->getVar('title') ) );
			// Текст внизу админки
			include __DIR__ . '/admin_footer.php';
			// Подвал админки
			xoops_cp_footer();
			
		}
		
		break;
	
	// Удаление страницы
	case 'updpage':
		
		// Принимаем данные
		$pageids = $_POST['pageids'];
		$weights = $_POST['weights'];
		// Перебираем все значения
		foreach( $pageids as $key => $pageid ){
			
			//echo $pageid . ' -> ' . $weights[$key] . '<br />';
			// Объявляем объект
			$objInspage =& $inspage_Handler->get( $pageid );
			// Устанавливаем вес
			$objInspage->setVar( 'weight', $weights[$key] );
			// Вставляем данные в БД
			$inspage_Handler->insert( $objInspage );
			// Удаляем объект
			unset( $objInspage );
			
		}
		// Редирект
		redirect_header( 'instr.php?op=viewinstr&instrid=' . $instrid, 3, _AM_INSTRUCTION_PAGESUPDATE );
		
		break;

}
