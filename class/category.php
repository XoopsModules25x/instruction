<?php
// ааа

if (!defined("XOOPS_ROOT_PATH")) {
	die("XOOPS root path not defined");
}

class InstructionCategory extends XoopsObject
{
	// constructor
	function __construct()
	{
//		$this->XoopsObject();
		$this->initVar( "cid", XOBJ_DTYPE_INT, NULL, false, 5 );
		$this->initVar( "pid", XOBJ_DTYPE_INT, 0, false, 5 );
		$this->initVar( "title", XOBJ_DTYPE_TXTBOX, '', false );
		$this->initVar( "imgurl", XOBJ_DTYPE_TXTBOX, '', false );
		$this->initVar( "description", XOBJ_DTYPE_TXTAREA, NULL, false );
		$this->initVar( "weight", XOBJ_DTYPE_INT, 0, false, 11 );
		$this->initVar( "datecreated", XOBJ_DTYPE_INT, 0, false, 10 );
		$this->initVar( "dateupdated", XOBJ_DTYPE_INT, 0, false, 10 );
		$this->initVar( "metakeywords", XOBJ_DTYPE_TXTBOX, '', false );
		$this->initVar( "metadescription", XOBJ_DTYPE_TXTBOX, '', false );
	}
	
	function InstructionCategory()
    {
		$this->__construct();
	}
	
	function get_new_enreg()
	{
		$new_enreg = $GLOBALS['xoopsDB']->getInsertId();
		return $new_enreg;
    }
	
	// Получаем форму
	function getForm( $action = false )
	{
		//global $xoopsDB, $xoopsModule, $xoopsModuleConfig;
		// Если нет $action
		if ($action === false) {
			$action = xoops_getenv('REQUEST_URI');
		}
		// Подключаем формы
		include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
		
		// Название формы       
		$title = $this->isNew() ? sprintf( _AM_INSTRUCTION_FORMADDCAT ) : sprintf( _AM_INSTRUCTION_FORMEDITCAT );
		
		// Форма
        $form = new XoopsThemeForm( $title, 'formcat', $action, 'post', true );
		//$form->setExtra('enctype="multipart/form-data"');
		// Название категории
		$form->addElement( new XoopsFormText( _AM_INSTRUCTION_TITLEC, 'title', 50, 255, $this->getVar('title') ), true );
		// Редактор
		$form->addElement( new XoopsFormTextArea( _AM_INSTRUCTION_DSCC, 'description', $this->getVar('description', 'e') ), true );
        //image
        /*
		$downloadscat_img = $this->getVar('imgurl') ? $this->getVar('imgurl') : 'blank.gif';
		$uploadirectory='/uploads/tdmdownloads/images/cats';
		$imgtray = new XoopsFormElementTray(_AM_TDMDOWNLOADS_FORMIMG,'<br />');
		$imgpath=sprintf(_AM_TDMDOWNLOADS_FORMPATH, $uploadirectory );
		$imageselect= new XoopsFormSelect($imgpath, 'downloadscat_img',$downloadscat_img);        
   		$topics_array = XoopsLists :: getImgListAsArray( XOOPS_ROOT_PATH . $uploadirectory );
    	foreach( $topics_array as $image ) {
   	    	$imageselect->addOption("$image", $image);
    	}
		$imageselect->setExtra( "onchange='showImgSelected(\"image3\", \"downloadscat_img\", \"" . $uploadirectory . "\", \"\", \"" . XOOPS_URL . "\")'" );
    	$imgtray->addElement($imageselect,false);
    	$imgtray -> addElement( new XoopsFormLabel( '', "<br /><img src='" . XOOPS_URL . "/" . $uploadirectory . "/" . $downloadscat_img . "' name='image3' id='image3' alt='' />" ) );
    	$fileseltray= new XoopsFormElementTray('','<br />');
    	$fileseltray->addElement(new XoopsFormFile(_AM_TDMDOWNLOADS_FORMUPLOAD , 'attachedfile', $xoopsModuleConfig['maxuploadsize']), false);
    	$fileseltray->addElement(new XoopsFormLabel('' ), false);
    	$imgtray->addElement($fileseltray);
    	$form->addElement($imgtray);
		*/
        // Родительская категория
		$instructioncat_Handler =& xoops_getModuleHandler('category', 'instruction');
		$criteria = new CriteriaCompo();
		// Если мы редактируем, то убрать текущую категорию из списка выбора родительской
		if( ! $this->isNew() ) $criteria->add( new Criteria( 'cid', $this->getVar('cid'), '<>' ) );
		$criteria->setSort( 'weight ASC, title' );
		$criteria->setOrder( 'ASC' );
		$instructioncat_arr = $instructioncat_Handler->getall( $criteria );
		unset( $criteria );
		// Подключаем трей
		include_once XOOPS_ROOT_PATH . '/class/tree.php';
		$mytree = new XoopsObjectTree( $instructioncat_arr, 'cid', 'pid' );
		$form->addElement( new XoopsFormLabel( _AM_INSTRUCTION_PCATC, $mytree->makeSelBox( 'pid', 'title', '--', $this->getVar('pid'), true ) ) );
		// Вес
		$form->addElement( new XoopsFormText( _AM_INSTRUCTION_WEIGHTC, 'weight', 5, 5, $this->getVar( 'weight' ) ), true );
		// Мета-теги ключевых слов
		$form->addElement( new XoopsFormText( _AM_INSTRUCTION_METAKEYWORDSC, 'metakeywords', 50, 255, $this->getVar('metakeywords') ), false );
		// Мета-теги описания
		$form->addElement( new XoopsFormText( _AM_INSTRUCTION_METADESCRIPTIONC, 'metadescription', 50, 255, $this->getVar('metadescription') ), false );
		
		
		// ==========================================================
		// ==========================================================
		
		// Права
		$member_handler = & xoops_gethandler('member');
		$group_list = &$member_handler->getGroupList();
		$gperm_handler = &xoops_gethandler('groupperm');
		$full_list = array_keys( $group_list );
		
		// Права на просмотр
		$groups_ids = array();
		// Если мы редактируем
		if( ! $this->isNew() ) {
			$groups_ids = $gperm_handler->getGroupIds( 'instruction_view', $this->getVar('cid'), $GLOBALS['xoopsModule']->getVar('mid') );
			$groups_ids = array_values( $groups_ids );
			$groups_instr_view = new XoopsFormCheckBox( _AM_INSTRUCTION_PERM_VIEW, 'groups_instr_view', $groups_ids );
		} else {
			$groups_instr_view = new XoopsFormCheckBox( _AM_INSTRUCTION_PERM_VIEW, 'groups_instr_view', $full_list );
		}
		$groups_instr_view->addOptionArray( $group_list );
		$form->addElement( $groups_instr_view );
		
		// Права на отправку
		$groups_ids = array();
		if( ! $this->isNew() ) {
			$groups_ids = $gperm_handler->getGroupIds( 'instruction_submit', $this->getVar('cid'), $GLOBALS['xoopsModule']->getVar('mid') );
			$groups_ids = array_values( $groups_ids );
			$groups_instr_submit = new XoopsFormCheckBox( _AM_INSTRUCTION_PERM_SUBMIT, 'groups_instr_submit', $groups_ids );
		} else {
			$groups_instr_submit = new XoopsFormCheckBox( _AM_INSTRUCTION_PERM_SUBMIT, 'groups_instr_submit', $full_list );
		}
		$groups_instr_submit->addOptionArray( $group_list );
		$form->addElement( $groups_instr_submit );
		
		// Права на редактирование
		$groups_ids = array();
		if( ! $this->isNew() ) {
			$groups_ids = $gperm_handler->getGroupIds( 'instruction_edit', $this->getVar('cid'), $GLOBALS['xoopsModule']->getVar('mid') );
			$groups_ids = array_values( $groups_ids );
			$groups_instr_edit = new XoopsFormCheckBox( _AM_INSTRUCTION_PERM_EDIT, 'groups_instr_edit', $groups_ids );
		} else {
			$groups_instr_edit = new XoopsFormCheckBox( _AM_INSTRUCTION_PERM_EDIT, 'groups_instr_edit', $full_list );
		}
		$groups_instr_edit->addOptionArray( $group_list );
		$form->addElement( $groups_instr_edit );
		
		// ==========================================================
		// ==========================================================
		
		
		// Если мы редактируем категорию
		if ( ! $this->isNew() ) {
			$form->addElement( new XoopsFormHidden( 'cid', $this->getVar('cid') ) );
			//$form->addElement( new XoopsFormHidden( 'catmodify', true));
		}        
        //
		$form->addElement( new XoopsFormHidden( 'op', 'savecat' ) );
		// Кнопка
		$button_tray = new XoopsFormElementTray( '', '' );
		$submit_btn = new XoopsFormButton( '', 'submit', _SUBMIT, 'submit' );
		$button_tray->addElement( $submit_btn );
		$cancel_btn = new XoopsFormButton( '', 'cancel', _CANCEL, 'cancel' );
		$cancel_btn->setExtra( 'onclick="javascript:history.go(-1);"' );
		$button_tray->addElement( $cancel_btn );
		$form->addElement( $button_tray );
		
		return $form;
	}
}

class InstructionCategoryHandler extends XoopsPersistableObjectHandler 
{
	function __construct(&$db) 
	{
		parent::__construct( $db, 'instruction_cat', 'InstructionCategory', 'cid', 'title' );
	}
	
	// Обновление даты обновления категории
	function updateDateupdated( $cid = 0, $time = NULL ) {
		// Если не передали время
		$time = $time === NULL ? time() : intval( $time );
		//
		$sql = sprintf( "UPDATE `%s` SET `dateupdated` = %u WHERE `cid` = %u", $this->table, $time, intval( $cid ) );
		//
		return $this->db->query( $sql );
	}
	
}
