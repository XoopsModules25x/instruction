<?php

include_once __DIR__ . '/admin_header.php';
//
$adminObject = \Xmf\Module\Admin::getInstance();
xoops_cp_header();
$adminObject->displayNavigation(basename(__FILE__));
\Xmf\Module\Admin::setPaypal('aerograf@shmel.org');
$adminObject->displayAbout(false);

include_once __DIR__ . '/admin_footer.php';
