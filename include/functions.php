<?php

// Права
function instr_MygetItemIds( $permtype = 'instruction_view' )
{
	//global $xoopsUser;
	static $permissions = array();
	// Если есть в статике
	if( is_array( $permissions ) && array_key_exists( $permtype, $permissions ) ) {
		return $permissions[$permtype];
	}
	// Находим из базы
	$module_handler = xoops_gethandler('module');
	$instrModule = $module_handler->getByDirname('instruction');
	$groups = is_object( $GLOBALS['xoopsUser'] ) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
	$gperm_handler = xoops_gethandler('groupperm');
	$categories = $gperm_handler->getItemIds( $permtype, $groups, $instrModule->getVar('mid') );
	$permissions[$permtype] = $categories;
	return $categories;
}

// Редактор
function &instr_getWysiwygForm( $caption, $name, $value = '' )
{
	$editor = false;
	$editor_configs = array();
	$editor_configs['name'] = $name;
	$editor_configs['value'] = $value;
	$editor_configs['rows'] = 35;
	$editor_configs['cols'] = 60;
	$editor_configs['width'] = '100%';
	$editor_configs['height'] = '350px';
	$editor_configs['editor'] = strtolower( xoops_getModuleOption( 'form_options', 'instruction') );

	$editor = new XoopsFormEditor( $caption, $name, $editor_configs );
	return $editor;
}

// Получение значения переменной, переданной через GET или POST запрос
function instr_CleanVars(&$global, $key, $default = '', $type = 'int') {

    switch ($type) {
        case 'string':
            $ret = (isset($global[$key])) ? $global[$key] : $default;
            //$ret = ( isset( $global[$key] ) ) ? filter_var( $global[$key], FILTER_SANITIZE_MAGIC_QUOTES ) : $default;
            break;
        case 'int':
        default:
            $ret = (isset($global[$key])) ? intval($global[$key]) : intval($default);
            //$ret = ( isset( $global[$key] ) ) ? filter_var( $global[$key], FILTER_SANITIZE_NUMBER_INT ) : $default;
            break;
    }
    if ($ret === false) {
        return $default;
    }
    return $ret;
}
