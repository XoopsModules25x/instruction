function instrSavePage(){
	
	// Сохраняем редактор
	tinyMCE.triggerSave();
	
	// Элементы форм
	var page_title, page_pid, page_weight, page_hometext, page_footnote, page_status, page_type, page_keywords, page_description, page_dohtml;
	// Скрытые элементы форм
	var page_token, page_pageid, page_instrid, page_op;
	
	// Ссылки на элементы
	var var_token = $("#instr_form_page #XOOPS_TOKEN_REQUEST");
	var var_pageid = $("#instr_form_page #pageid");
	var var_instrid = $("#instr_form_page #instrid");
	var var_op = $("#instr_form_page #op");
	
	// Название страницы
	page_title = $("#instr_form_page #title").val();
	// Родительская страница
	page_pid = $("#instr_form_page #pid").val();
	page_pid = page_pid ? page_pid : 0;
	// Вес
	page_weight = $("#instr_form_page #weight").val();
	// Основной текст
	page_hometext = $("#instr_form_page #hometext").val();
	// Сноска
	page_footnote = $("#instr_form_page #footnote").val();
	// Статус
	page_status = $('#instr_form_page input:radio[name=status]:checked').val();
	page_status = page_status ? page_status : 0;
	// Тип
	page_type = $("#instr_form_page #type").val();
	page_type = page_type ? page_type : 0;
	// Мета теги ключевых слов
	page_keywords = $("#instr_form_page #keywords").val();
	// Мета теги описания
	page_description = $("#instr_form_page #description").val();
	//
	page_dohtml = $('input:checkbox[name=dohtml]:checked').val();
	page_dohtml = page_dohtml ? page_dohtml : 0;
	//
	page_dosmiley = $('input:checkbox[name=dosmiley]:checked').val();
	page_dosmiley = page_dosmiley ? page_dosmiley : 0;
	//
	page_doxcode = $('input:checkbox[name=doxcode]:checked').val();
	page_doxcode = page_doxcode ? page_doxcode : 0;
	//
	page_dobr = $('input:checkbox[name=dobr]:checked').val();
	page_dobr = page_dobr ? page_dobr : 0;
	
	// Код безопасности
	page_token = var_token.val();
	// ID страницы
	page_pageid = var_pageid.val();
	// ID инструкции
	page_instrid = var_instrid.val();
	// Опция
	page_op = var_op.val();
	
	// Выполняем AJAX запрос
	$.ajax({
		url: 'ajax.php',
		type: 'post',
		// Передаём данные
		data: { title: page_title, pid: page_pid, weight: page_weight, hometext: page_hometext, footnote: page_footnote, status: page_status, type: page_type, keywords: page_keywords, description: page_description, dohtml: page_dohtml, dosmiley: page_dosmiley, doxcode: page_doxcode, dobr: page_dobr, XOOPS_TOKEN_REQUEST: page_token, pageid: page_pageid, instrid: page_instrid, op: page_op },
		dataType: 'json',
		// При успешном выполнении
		success: function(json) {
			
			// Если передали token
			if( json['toket'] ) {
				//
				var_token.val( json['toket'] );
			}
			// Если передали pageid
			if( json['pageid'] ) {
				//
				var_pageid.val( json['pageid'] );
			}
			
			// Очищаем поле сообщений
			$('#ins_message').html('');
			// Если есть сообщение
			if( json['message'] ) {
				// Устанавливаем текст сообщения
				//$('#ins_message').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
				// Устанавливаем сообщение
				$('#ins_message').html( json['message'] );
				// Показываем сообщение
				//$('.success').fadeIn('slow');
				// Прокручиваем окно вверх
				$('html, body').animate({ scrollTop: 0 }, 'slow');
			}
		}
	});
	//
	return false;
}