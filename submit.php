<?php

include __DIR__ . '/header.php';

// Объявляем объекты
$insinstr_Handler = xoops_getModuleHandler( 'instruction', 'instruction' );
//$inscat_Handler =& xoops_getModuleHandler( 'category', 'instruction' );
$inspage_Handler = xoops_getModuleHandler( 'page', 'instruction' );

//
$uid = is_object( $GLOBALS['xoopsUser'] ) ? $GLOBALS['xoopsUser']->getVar('uid') : 0;
$time = time();

// ID инструкции
$instrid = isset( $_GET['instrid'] ) ? intval( $_GET['instrid'] ) : 0;
$instrid = isset( $_POST['instrid'] ) ? intval( $_POST['instrid'] ) : $instrid;
// ID страницы
$pageid = isset( $_GET['pageid'] ) ? intval( $_GET['pageid'] ) : 0;
$pageid = isset( $_POST['pageid'] ) ? intval( $_POST['pageid'] ) : $pageid;
// ID категории
$cid = isset( $_POST['cid'] ) ? intval( $_POST['cid'] ) : 0;
// Вес
$weight = isset( $_POST['weight'] ) ? intval( $_POST['weight'] ) : 0;
//
$pid = isset( $_POST['pid'] ) ? intval( $_POST['pid'] ) : 0;

// Права на добавление
$cat_submit = instr_MygetItemIds( 'instruction_submit' );
// Права на редактирование
$cat_edit = instr_MygetItemIds( 'instruction_edit' );

$op = isset($_GET['op']) ? $_GET['op'] : '';
$op = isset($_POST['op']) ? $_POST['op'] : $op;

switch ( $op ) {
	
	case 'editpage':
		
		// Задание тайтла
		$xoopsOption['xoops_pagetitle'] = '';
		// Шаблон
		$GLOBALS['xoopsOption']['template_main'] = 'instruction_editpage.tpl';
		// Заголовок
		include XOOPS_ROOT_PATH . '/header.php';
		
		// Если мы редактируем страницу
		if( $pageid ) {
			// Получаем объект страницы
			$objInspage =& $inspage_Handler->get( $pageid );
			// ID инструкции
			$instrid = $objInspage->getVar( 'instrid' );
			// Объект инструкции
			$objInsinstr = $insinstr_Handler->get( $instrid );
			// Можно ли редактировать инструкцию в данной категории
			if( ! in_array( $objInsinstr->getVar('cid'), $cat_edit ) ) redirect_header( 'index.php', 3, _MD_INSTRUCTION_NOPERM_EDITPAGE );
		// Создание новой страницы
		} elseif( $instrid ) {
			
			// Если нельзя добавлять не в одну категорию
			//if( ! count( $cat_submit ) ) redirect_header( 'index.php', 3, _MD_INSTRUCTION_NOPERM_SUBMIT_PAGE );
			// Создаём объект страницы
			$objInspage =& $inspage_Handler->create();
			// Объект инструкции
			$objInsinstr = $insinstr_Handler->get( $instrid );
			// Можно ли добавлять инструкции в данной категории
			if( ! in_array( $objInsinstr->getVar('cid'), $cat_submit ) ) redirect_header( 'index.php', 3, _MD_INSTRUCTION_NOPERM_SUBMITPAGE );
		} else {
			redirect_header( 'index.php', 3, _MD_INSTRUCTION_BADREQUEST );
		}
		
		// Информация об инструкции
		
		// Массив данных об инструкции
		$instrs = array();
		// ID инструкции
		$instrs['instrid'] = $objInsinstr->getVar( 'instrid' );
		// Название страницы
		$instrs['title'] = $objInsinstr->getVar( 'title' );
		// Описание
		$instrs['description'] = $objInsinstr->getVar( 'description' );
		
		// Выводим в шаблон
		$GLOBALS['xoopsTpl']->assign( 'insInstr', $instrs );
		
		//
		
		$form =& $objInspage->getForm( 'submit.php', $instrid );
		// Форма
		$GLOBALS['xoopsTpl']->assign( 'insFormPage', $form->render() );
		
		
		// Подвал
		include XOOPS_ROOT_PATH . '/footer.php';
		
		break;
	// Сохранение страницы
	case 'savepage':
		
		// Проверка
		if ( ! $GLOBALS['xoopsSecurity']->check() ) {
			redirect_header( 'index.php', 3, implode( ',', $GLOBALS['xoopsSecurity']->getErrors() ) );
		}
		
		$err = false;
		$message_err = '';
		
		// Если мы редактируем
		if ( $pageid ) {
			$objInspage =& $inspage_Handler->get( $pageid );
			// Объект инструкции
			$objInsinstr = $insinstr_Handler->get( $objInspage->getVar( 'instrid' ) );
			// Можно ли редактировать инструкцию в данной категории
			if( ! in_array( $objInsinstr->getVar('cid'), $cat_edit ) ) redirect_header( 'index.php', 3, _MD_INSTRUCTION_NOPERM_EDITPAGE );
		} elseif( $instrid ) {
			$objInspage =& $inspage_Handler->create();
			// Объект инструкции
			$objInsinstr = $insinstr_Handler->get( $instrid );
			// Можно ли добавлять инструкции в данной категории
			if( ! in_array( $objInsinstr->getVar('cid'), $cat_submit ) ) redirect_header( 'index.php', 3, _MD_INSTRUCTION_NOPERM_SUBMITPAGE );
			
			// Если мы создаём страницу необходимо указать к какой инструкции
			$objInspage->setVar( 'instrid', $instrid );
			// Указываем дату создания
			$objInspage->setVar( 'datecreated', $time );
			// Указываем пользователя
			$objInspage->setVar( 'uid', $uid );
		} else {
			redirect_header( 'index.php', 3, _MD_INSTRUCTION_BADREQUEST );
		}
		
		// Родительская страница
		$objInspage->setVar( 'pid', $pid );
		// Дата обновления
		$objInspage->setVar( 'dateupdated', $time );
		//
		$objInspage->setVar( 'title', $_POST['title'] );
		$objInspage->setVar( 'weight', $weight );
		$objInspage->setVar( 'hometext', $_POST['hometext'] );
		// Сноска
		$objInspage->setVar( 'footnote', $_POST['footnote'] );
		$objInspage->setVar( 'status', $_POST['status'] );
		$objInspage->setVar( 'keywords', $_POST['keywords'] );
		$objInspage->setVar( 'description', $_POST['description'] );
		
		// Проверка категорий
		if ( ! $pageid && ! $instrid ) {
			$err = true;
			$message_err .= _MD_INSTRUCTION_ERR_INSTR . '<br />';
		}
		// Проверка веса
		if ( $weight == 0 ){
			$err = true;
			$message_err .= _MD_INSTRUCTION_ERR_WEIGHT . '<br />';
		}
		// Проверка родительской страницы
		if ( $pageid && ( $pageid == $pid ) ) {
			$err = true;
			$message_err .= _MD_INSTRUCTION_ERR_PPAGE . '<br />';
		}
		// Если были ошибки
		if ( $err == true ) {
			// Задание тайтла
			$xoopsOption['xoops_pagetitle'] = '';
			// Шаблон
			$GLOBALS['xoopsOption']['template_main'] = 'instruction_savepage.tpl';
			// Заголовок
			include XOOPS_ROOT_PATH . '/header.php';
			// Сообщение об ошибке
			$message_err = '<div class="errorMsg" style="text-align: left;">' . $message_err . '</div>';
			// Выводим ошибки в шаблон
			$GLOBALS['xoopsTpl']->assign( 'insErrorMsg', $message_err );
		// Если небыло ошибок
		} else {
			// Вставляем данные в БД
			if ( $inspage_Handler->insert( $objInspage ) ) {
				// Если мы редактируем
				if ( $pageid ) {
					// Обновление даты
					$sql = sprintf( "UPDATE %s SET `dateupdated` = %u WHERE `instrid` = %u", $GLOBALS['xoopsDB']->prefix("instruction_instr"), $time, $instrid );
					$GLOBALS['xoopsDB']->query($sql);
					// Запись в лог
					xoops_loadLanguage( 'main', 'userslog' );
					userslog_insert( $objInsinstr->getVar('title') . ': ' . $objInspage->getVar('title'), _MD_USERSLOG_MODIFY_PAGE );
					//
					redirect_header( 'index.php', 3, _MD_INSTRUCTION_PAGEMODIFY );
				// Если мы добавляем
				} else {
					// Инкримент комментов
					$inspage_Handler->updateposts( $uid, $_POST['status'], 'add' );
					// Инкремент страниц и обновление даты
					$sql = sprintf( "UPDATE %s SET `pages` = `pages` + 1, `dateupdated` = %u WHERE `instrid` = %u", $GLOBALS['xoopsDB']->prefix("instruction_instr"), $time, $instrid );
					$GLOBALS['xoopsDB']->query($sql);
					// Запись в лог
					xoops_loadLanguage( 'main', 'userslog' );
					userslog_insert( $objInsinstr->getVar('title') . ': ' . $objInspage->getVar('title'), _MD_USERSLOG_SUBMIT_PAGE );
					//
					redirect_header( 'index.php', 3, _MD_INSTRUCTION_PAGEADDED );
				}
			}
			
			// Задание тайтла
			$xoopsOption['xoops_pagetitle'] = '';
			// Шаблон
			$GLOBALS['xoopsOption']['template_main'] = 'instruction_savepage.tpl';
			// Заголовок
			include XOOPS_ROOT_PATH . '/header.php';
			
			// Выводим ошибки в шаблон
			$GLOBALS['xoopsTpl']->assign( 'insErrorMsg', $objInspage->getHtmlErrors() );
		}
		// Получаем форму
		$form =& $objInspage->getForm( 'submit.php', $instrid );
		
		// Форма
		$GLOBALS['xoopsTpl']->assign( 'insFormPage', $form->render() );
		
		// Подвал
		include XOOPS_ROOT_PATH . '/footer.php';
		
		break;
	
}
