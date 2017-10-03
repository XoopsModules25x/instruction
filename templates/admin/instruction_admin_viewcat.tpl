<{$insNavigation}>

<table class="outer" style="border-spacing:1px;width:100%;">
    <tr>
        <th colspan="2" style="text-align:center;"><{$insCat.title}></th>
    </tr>
    <tr class="even">
        <td style="width:30%;"><{$lang_dsc}></td>
        <td><{$insCat.dsc}></td>
    </tr>
    <tr class="odd">
        <td style="width:30%;"><{$lang_weight}></td>
        <td><{$insCat.weight}></td>
    </tr>
    <tr class="even">
        <td style="width:30%;"><{$lang_action}></td>
        <td><a href="cat.php?op=editcat&cid=<{$insCat.cid}>"><img src="../assets/icons/edit_mini.png" alt="<{$lang_edit}>" title="<{$lang_edit}>"></a>&nbsp;<a href="cat.php?op=delcat&cid=<{$insCat.cid}>"><img src="../assets/icons/delete_mini.png" alt="<{$lang_del}>" title="<{$lang_del}>"></a></td>
    </tr>
</table>
