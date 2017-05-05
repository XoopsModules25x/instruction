<?php
//
include 'admin_header.php';
// Функции модуля
include '../include/functions.php';
// Отключаем дебугер
$GLOBALS['xoopsLogger']->activated = false;

// Объявляем объекты
$insinstr_Handler =& xoops_getModuleHandler( 'instruction', 'instruction' );
$inscat_Handler =& xoops_getModuleHandler( 'category', 'instruction' );
$inspage_Handler =& xoops_getModuleHandler( 'page', 'instruction' );

$uid = is_object( $GLOBALS['xoopsUser'] ) ? $GLOBALS['xoopsUser']->getVar('uid') : 0;
$time = time();

// Опция
$op = instr_CleanVars( $_POST, 'op', 'main', 'string' );

// Выбор
switch ( $op ) {
	// Сохранение страницы
	case 'savepage':
		// Выходной массив
		$ret = array();
		
		// Ошибки
		$err = false;
		$message_err = '';
		
		//
		$title = instr_CleanVars( $_POST, 'title', '', 'string' );
		$pid = instr_CleanVars( $_POST, 'pid', 0, 'int' );
		$weight = instr_CleanVars( $_POST, 'weight', 0, 'int');
		$hometext = instr_CleanVars( $_POST, 'hometext', '', 'string' );
		$footnote = instr_CleanVars( $_POST, 'footnote', '', 'string' );
		$status = instr_CleanVars( $_POST, 'status', 0, 'int' );
		$type = instr_CleanVars( $_POST, 'type', 0, 'int' );
		$keywords = instr_CleanVars( $_POST, 'keywords', '', 'string' );
		$description = instr_CleanVars( $_POST, 'description', '', 'string' );
		$dosmiley = ( isset( $_POST['dosmiley'] ) && intval( $_POST['dosmiley'] ) > 0 ) ? 1 : 0;
		$doxcode = ( isset( $_POST['doxcode'] ) && intval( $_POST['doxcode'] ) > 0 ) ? 1 : 0;
		$dobr = ( isset( $_POST['dobr'] ) && intval( $_POST['dobr'] ) > 0 ) ? 1 : 0;
		$dohtml = ( isset( $_POST['dohtml'] ) && intval( $_POST['dohtml'] ) > 0 ) ? 1 : 0;
		/*
		$dohtml = instr_CleanVars( $_POST, 'dohtml', 0, 'int' );
		$dosmiley = instr_CleanVars( $_POST, 'dosmiley', 0, 'int' );
		$doxcode = instr_CleanVars( $_POST, 'doxcode', 0, 'int' );
		$dobr = instr_CleanVars( $_POST, 'dobr', 0, 'int' );
		*/
		$pageid = instr_CleanVars( $_POST, 'pageid', 0, 'int' );
		$instrid = instr_CleanVars( $_POST, 'instrid', 0, 'int' );
		
		// Проверка
		if ( ! $GLOBALS['xoopsSecurity']->check() ) {
			$err = true;
			$err_txt = implode( ', ', $GLOBALS['xoopsSecurity']->getErrors() );
			$message_err .= $err_txt . '<br />' . _AM_INSTR_TRY_AGAIN . '<br />';
		}
		// =========================
		// Устанавливаем новый token
		$token_name = 'XOOPS_TOKEN';
		$token_timeout = 0;
		// $token_name . '_REQUEST' - название элемента формы
		$ret['toket'] = $GLOBALS['xoopsSecurity']->createToken( $token_timeout, $token_name );
		// =========================
		
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
			// Устанавливаем сообщение
			$ret['message'] = _AM_INSTRUCTION_BADREQUEST;
			// Возвращаем ответ скрипту через JSON
			echo json_encode( $ret );
			// Прерываем выполнение
			exit();
		}
		
		// Родительская страница
		$objInspage->setVar( 'pid', $pid );
		// Дата обновления
		$objInspage->setVar( 'dateupdated', $time );
		// Название
		$objInspage->setVar( 'title', $title );
		// Вес
		$objInspage->setVar( 'weight', $weight );
		// Текст
		$objInspage->setVar( 'hometext', $hometext );
		// Сноска
		$objInspage->setVar( 'footnote', $footnote );
		// Статус
		$objInspage->setVar( 'status', $status );
		// Тип
		$objInspage->setVar( 'type', $type );
		// Мета-теги ключевых слов
		$objInspage->setVar( 'keywords', $keywords );
		// Мета-теги описания
		$objInspage->setVar( 'description', $description );
		//
		$objInspage->setVar( 'dohtml', $dohtml );
		$objInspage->setVar( 'dosmiley', $dosmiley );
		$objInspage->setVar( 'doxcode', $doxcode );
		$objInspage->setVar( 'dobr', $dobr );
		
		// Проверка категорий
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
		if ( !$title ) {
			$err = true;
			$message_err .= _AM_INSTR_ERR_TITLE . '<br />';
		}
		// Проверка основного текста
		if ( !$hometext ) {
			$err = true;
			$message_err .= _AM_INSTR_ERR_HOMETEXT . '<br />';
		}
		
		// Если были ошибки
		if ( $err == true ) {
			//
			$message_err = '<div class="errorMsg" style="text-align: left;">' . $message_err . '</div>';
			// Устанавливаем сообщение
			$ret['message'] = $message_err;
			// Возвращаем ответ скрипту через JSON
			echo json_encode( $ret );
			// Прерываем выполнение
			exit();
		// Если небыло ошибок
		} else {
			// Вставляем данные в БД
			if ( $inspage_Handler->insert( $objInspage ) ) {
				// Находим ID созданной записи
				$pageid_new = $pageid ? $pageid : $objInspage->get_new_enreg();
				//
				$ret['pageid'] = $pageid_new;
				// Получаем ID инструкции
				$instrid = $objInspage->getInstrid();
				// Обновляем в инструкции число страниц и дату
				$insinstr_Handler->updatePages( $instrid );
				// Если мы редактируем
				if ( $pageid ) {
					// Устанавливаем сообщение
					$ret['message'] = '<div class="successMsg" style="text-align: left;">' . _AM_INSTRUCTION_PAGEMODIFY . '</div>';
					// Возвращаем ответ скрипту через JSON
					echo json_encode( $ret );
					// Прерываем выполнение
					exit();
					
				// Если мы добавляем
				} else {
					// Инкримент комментов
					$inspage_Handler->updateposts( $uid, $status, 'add' );
					
					// Устанавливаем сообщение
					$ret['message'] = '<div class="successMsg" style="text-align: left;">' . _AM_INSTRUCTION_PAGEADDED . '</div>';
					// Возвращаем ответ скрипту через JSON
					echo json_encode( $ret );
					// Прерываем выполнение
					exit();
					
				}
			// Если не получилось вставить данные
			} else {
				
				//
				$message_err = '<div class="errorMsg" style="text-align: left;">' . $objInspage->getHtmlErrors() . '</div>';
				// Устанавливаем сообщение
				$ret['message'] = $message_err;
				// Возвращаем ответ скрипту через JSON
				echo json_encode( $ret );
				// Прерываем выполнение
				exit();
				
			}
		}
		
		break;
}

?>