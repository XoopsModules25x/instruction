<?php

include __DIR__ . '/../../../include/cp_header.php';
include __DIR__ . '/admin_header.php';
// Admin GUI
xoops_cp_header();

$adminObject  = \Xmf\Module\Admin::getInstance();

$insinstr_Handler = xoops_getModuleHandler( 'instruction', 'instruction' );
$inscat_Handler = xoops_getModuleHandler( 'category', 'instruction' );
$inspage_Handler = xoops_getModuleHandler( 'page', 'instruction' );
$numrows_instr = $insinstr_Handler->getCount( $criteria );
$numrows_cat = $inscat_Handler->getCount( $criteria );
$numrows_page = $inspage_Handler->getCount( $criteria );
$adminObject->addInfoBox(_AM_INSTRUCTION_TOTAL);
$adminObject->addInfoBoxLine('<infolabel>' . '<a href="cat.php">' . _AM_INSTRUCTION_TOTAL_CAT . '</a>' . '&nbsp;&mdash;&nbsp;<span class="green">' . $numrows_cat . '</span></infolabel>','','');
$adminObject->addInfoBoxLine('<infolabel>' . '<a href="instr.php">' . _AM_INSTRUCTION_TOTAL_INSTR . '</a>' . '&nbsp;&mdash;&nbsp;<span class="green">' . $numrows_instr . '</span></infolabel>','','');
$adminObject->addInfoBoxLine('<infolabel>' . '<a href="instr.php">' . _AM_INSTRUCTION_TOTAL_PAGE . '</a>' . '&nbsp;&mdash;&nbsp;<span class="green">' . $numrows_page . '</span></infolabel>','','');

$adminObject->displayNavigation(basename(__FILE__));
$adminObject->displayIndex();

include __DIR__ . '/admin_footer.php';
//
xoops_cp_footer();
