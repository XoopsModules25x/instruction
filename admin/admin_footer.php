<?php
// Автор: andrey3761
echo "<div style='text-align:center;'><a href='http://xoops.ws' target='_blank'><img src=" . XOOPS_URL . '/modules/instruction/assets/images/xoops.ws.gif' . ' alt="XOOPS.WebSite" title="XOOPS.WebSite"></a></div>';
echo "<div class='center smallsmall italic pad5'><strong>" . $xoopsModule->getVar('name') . "</strong> is maintained by the <a class='tooltip' rel='external' href='https://xoops.org/' title='Visit XOOPS Community'>XOOPS Community</a></div>";
xoops_cp_footer();
