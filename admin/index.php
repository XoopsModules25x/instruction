<?php

include __DIR__ . '/../../../include/cp_header.php';
include __DIR__ . '/admin_header.php';
// Admin GUI
xoops_cp_header();

$adminObject  = \Xmf\Module\Admin::getInstance();
$adminObject->displayNavigation(basename(__FILE__));
$adminObject->displayIndex();

include __DIR__ . '/admin_footer.php';
//
xoops_cp_footer();

?>