<?php

define('_AM_INSTRUCTION_FORMADDCAT', 'Category submission form');
define('_AM_INSTRUCTION_FORMEDITCAT', 'Category edit form');
define('_AM_INSTRUCTION_FORMADDINSTR', 'Instruction submission form');
define('_AM_INSTRUCTION_FORMEDITINSTR', 'Instruction edit form');
define('_AM_INSTRUCTION_FORMADDPAGE', 'Page submission form');
define('_AM_INSTRUCTION_FORMEDITPAGE', 'Page edit form');
define('_AM_INSTRUCTION_FORMDELPAGE', 'Do you really want to delete the page: "%s" ?');
define('_AM_INSTRUCTION_FORMDELCAT', 'Do you really want to delete the category: "%s" ?');
define('_AM_INSTRUCTION_FORMDELINSTR', 'Do you really want to delete the instruction "%s" , and all its pages  ?');

// Кнопки формы
define('_AM_INSTR_SAVEFORM', 'Save');
//
//Try again
define('_AM_INSTR_TRY_AGAIN', 'Please, try again');

// Элементы форм
define('_AM_INSTRUCTION_TITLEC', 'Title:');
define('_AM_INSTRUCTION_DSCC', 'Description:');
define('_AM_INSTRUCTION_PCATC', 'Parent category:');
define('_AM_INSTRUCTION_PPAGEC', 'Parent page:');
define('_AM_INSTRUCTION_WEIGHTC', 'Weight:');
define('_AM_INSTRUCTION_CATC', 'Category:');
define('_AM_INSTRUCTION_ACTIVEC', 'Active:');
define('_AM_INSTRUCTION_HOMETEXTC', 'Main text:');
define('_AM_INSTRUCTION_FOOTNOTEC', 'Reference:');
define('_AM_INSTRUCTION_DESCRIPTIONC', 'Description:');
define('_AM_INSTRUCTION_METAKEYWORDSC', 'Meta keywords:');
define('_AM_INSTRUCTION_METADESCRIPTIONC', 'Meta description:');
define('_AM_INSTR_DOSMILEY', 'Allow smileys');
define('_AM_INSTR_DOHTML', 'Allow HTML tags');
define('_AM_INSTR_DOAUTOWRAP', 'Move lines automatically');
define('_AM_INSTR_DOXCODE', 'Allow BB-codes');
define('_AM_INSTR_PAGETYPEC', 'Page type:');
define('_AM_INSTR_PAGETYPEC_DESC', 'Tree leaf - a Page is displayed like a leaf of a instruction tree  with no link to it. <br>This is a general page.');

// Описание элементов форм
define('_AM_INSTRUCTION_FOOTNOTE_DSC', 'Reference is in the bottom of a page. <br>Should be divided with "|".');

define('_AM_INSTRUCTION_TITLE', 'Title');
define('_AM_INSTRUCTION_WEIGHT', 'Weight');
define('_AM_INSTR_INSTRS', 'Instructions');
define('_AM_INSTR_LISTALLCATS', 'List of all instructions');
define('_AM_INSTRUCTION_ACTION', 'Action');
define('_AM_INSTRUCTION_DEL', 'Delete');
define('_AM_INSTR_NODELCAT', 'Failed to delete (the category is not empty or child categories exist)');
define('_AM_INSTR_NODELPAGE', 'Failed to delete (child pages exist)');
define('_AM_INSTR_VIEWINSTR', 'Show instructions');
define('_AM_INSTR_NOVIEWINSTR', 'No instructions');
define('_AM_INSTRUCTION_DSC', 'Description');
define('_AM_INSTRUCTION_CAT', 'Category');
define('_AM_INSTRUCTION_PAGES', 'Pages');
define('_AM_INSTRUCTION_PPAGES', 'Pages');

//
define('_AM_INSTRUCTION_LISTPAGESININSTR', 'List of pages in the instruction "%s"');
define('_AM_INSTR_LISTINSTRINCAT', 'List of instructions in the category "%s"');
define('_AM_INSTR_LISTINSTRALL', 'List of all instructions');

//
define('_AM_INSTRUCTION_DISPLAY', 'Show');
define('_AM_INSTRUCTION_LOCK', 'Unblock');
define('_AM_INSTRUCTION_UNLOCK', 'Unblock');
define('_AM_INSTRUCTION_ADDPAGE', 'Submit a page');
define('_AM_INSTRUCTION_ADDSUBPAGE', 'Submit a subpage');
define('_AM_INSTRUCTION_ADDINSTR', 'Submit an instruction');
define('_AM_INSTRUCTION_EDIT', 'Edit');
define('_AM_INSTR_DISPLAY_NOCACHE', 'Do not cache to view');

// Breadcrumd
define('_AM_INSTRUCTION_BC_LISTINSTR', 'List of instructions');
define('_AM_INSTRUCTION_BC_EDITINSTR', 'Edit instruction');
define('_AM_INSTRUCTION_BC_DELINSTR', 'Delete instruction');
define('_AM_INSTRUCTION_BC_LISTPAGE', 'List of pages');
define('_AM_INSTRUCTION_BC_EDITPAGE', 'Page edit');
define('_AM_INSTRUCTION_BC_DELPAGE', 'Page removal');
define('_AM_INSTRUCTION_BC_PERM', 'Permissions');

// Права
define('_AM_INSTRUCTION_PERM_VIEW', 'Permissions to view');
define('_AM_INSTRUCTION_PERM_VIEW_DSC', 'Groups allowed to read instructions from categories.');
define('_AM_INSTRUCTION_PERM_SUBMIT', 'Permissions to submit');
define('_AM_INSTRUCTION_PERM_SUBMIT_DSC', 'Groups allowed to submit instructions in categories.');
define('_AM_INSTRUCTION_PERM_EDIT', 'Permissions to edit');
define('_AM_INSTRUCTION_PERM_EDIT_DSC', 'Groups allowed to edit instructions from categories.');

// Типы страницы
define('_AM_INSTR_PT_0', 'Tree leaf');
define('_AM_INSTR_PT_1', 'Page');

// Редиректы
define('_AM_INSTRUCTION_NEWCATADDED', 'New category is saved successfully.');
define('_AM_INSTRUCTION_INSTRADDED', 'New instruction is submitted successfully.');
define('_AM_INSTRUCTION_INSTRMODIFY', 'The instruction is edited successfully.');
define('_AM_INSTRUCTION_INSTRDELETED', 'The instruction is deleted successfully.');
define('_AM_INSTRUCTION_PAGEADDED', 'New page is submitted successfully.');
define('_AM_INSTRUCTION_PAGEDELETED', 'The page is deleted successfully.');
define('_AM_INSTRUCTION_PAGEMODIFY', 'The page is edited successfully.');
define('_AM_INSTRUCTION_PAGESUPDATE', 'Pages updated.');
define('_AM_INSTRUCTION_CATDELETED', 'The category is deleted successfully.');

// Ошибки
define('_AM_INSTR_ERR_TITLE', 'Error: Title is empty!');
define('_AM_INSTR_ERR_HOMETEXT', 'Error: Main text is empty!');
define('_AM_INSTR_ERR_DESCRIPTION', 'Error: Description is empty!');

define('_AM_INSTRUCTION_ERR_WEIGHT', 'Error: Weight not specified!');
define('_AM_INSTRUCTION_ERR_CAT', 'Error: Category not specified!');
define('_AM_INSTRUCTION_ERR_PCAT', 'Error: Parent category is specified wrong!');
define('_AM_INSTRUCTION_ERR_INSTR', 'Error: You have not chosen an instruction to submit to');
define('_AM_INSTRUCTION_ERR_DELPAGE', 'Error: Failed to delete the page!');
define('_AM_INSTRUCTION_ERR_DELINSTR', 'Error: Failed to delete the instruction!');
define('_AM_INSTRUCTION_ERR_PPAGE', 'Error: Parent page is specified wrong!');
define('_AM_INSTRUCTION_ERR_DELCAT', 'Error: Failed to delete the category!');
define('_AM_INSTRUCTION_ERR_CATNOTEMPTY', 'Error: There are instructions in the category!');
define('_AM_INSTRUCTION_ERR_CATNOTSELECT', 'Error: Category not chosen!');
define('_AM_INSTRUCTION_ERR_CATCHILDREN', 'Error: The category has child categories!');

define('_AM_INSTRUCTION_BADREQUEST', 'Wrong query...');
//define('_AM_MODULEADMIN_ABOUT_BY', 'by ');
//define('_AM_MODULEADMIN_ABOUT_AMOUNT', 'Amount');
//define('_AM_MODULEADMIN_ABOUT_AMOUNT_TTL', 'Please enter USD amount e.g. $25.00');
//define('_AM_MODULEADMIN_ABOUT_AMOUNT_CURRENCY', 'USD');
//define('_AM_MODULEADMIN_ABOUT_AMOUNT_SUGGESTED', '25.00');
//define('_AM_MODULEADMIN_ABOUT_AMOUNT_PATTERN', '\\$?[0-9]+(,[0-9]{3})*(\\.[0-9]{0,2})?$');
//define('_AM_MODULEADMIN_ABOUT_DONATE_IMG_ALT', 'Donate using PayPal or a major credit card online!');

define('_AM_INSTRUCTION_TOTAL', 'Total in the module');
define('_AM_INSTRUCTION_TOTAL_CAT', 'Total Categories');
define('_AM_INSTRUCTION_TOTAL_INSTR', 'Total Instructions');
define('_AM_INSTRUCTION_TOTAL_PAGE', 'Total pages');

define('_AM_INSTRUCTION_UPGRADEFAILED0', "Update failed - couldn't rename field '%s'");
define('_AM_INSTRUCTION_UPGRADEFAILED1', "Update failed - couldn't add new fields");
define('_AM_INSTRUCTION_UPGRADEFAILED2', "Update failed - couldn't rename table '%s'");
define('_AM_INSTRUCTION_ERROR_COLUMN', 'Could not create column in database : %s');
define('_AM_INSTRUCTION_ERROR_BAD_XOOPS', 'This module requires XOOPS %s+ (%s installed)');
define('_AM_INSTRUCTION_ERROR_BAD_PHP', 'This module requires PHP version %s+ (%s installed)');
define('_AM_INSTRUCTION_ERROR_TAG_REMOVAL', 'Could not remove tags from Tag Module');

define('_AM_INSTRUCTION_FOLDERS_DELETED_OK', 'Upload Folders have been deleted');

// Error Msgs
define('_AM_INSTRUCTION_ERROR_BAD_DEL_PATH', 'Could not delete %s directory');
define('_AM_INSTRUCTION_ERROR_BAD_REMOVE', 'Could not delete %s');
define('_AM_INSTRUCTION_ERROR_NO_PLUGIN', 'Could not load plugin');
