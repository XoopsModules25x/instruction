<{$insNavigation}>
<{$insButton}>

  <table class="outer" style="border-spacing:1px;width:100%;">
    <tr>
      <th colspan="4"><{$insHead}></th>
    </tr>
    <tr>
      <td class="head" style="text-align:center;"><{$lang_title}></td>
      <td class="head" style="text-align:center;"><{$lang_cat}></td>
      <td class="head" style="text-align:center; width:50px;"><{$lang_pages}></td>
      <td class="head" style="text-align:center; width:180px;"><{$lang_action}></td>
    </tr>
  <{if $insListInstr}>
  <{foreach from=$insListInstr item=insInstr}>
    <tr class="<{$insInstr.class}>">
      <td><a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/instr.php?id=<{$insInstr.instrid}>"><{$insInstr.title}></a></td>
      <td><a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?cid=<{$insInstr.cid}>"><{$insInstr.ctitle}></a></td>
      <td style="text-align:center; width:50px;"><{$insInstr.pages}></td>
      <td style="text-align:center; width:180px;">
        <a href="instr.php?op=viewinstr&instrid=<{$insInstr.instrid}>"><img src="../assets/icons/view_mini.png" alt="<{$lang_display}>" title="<{$lang_display}>"></a>&nbsp;<a href="instr.php?op=editpage&instrid=<{$insInstr.instrid}>"><img src="../assets/icons/add_mini.png" alt="<{$lang_addpage}>" title="<{$lang_addpage}>"></a>&nbsp;<{if $insInstr.status}><img src="../assets/icons/lock_mini.png" alt="<{$lang_lock}>" title="<{$lang_lock}>"><{else}><img src="../assets/icons/unlock_mini.png" alt="<{$lang_unlock}>" title="<{$lang_unlock}>"><{/if}>&nbsp;<a href="instr.php?op=editinstr&instrid=<{$insInstr.instrid}>"><img src="../assets/icons/edit_mini.png" alt="<{$lang_edit}>" title="<{$lang_edit}>"></a>&nbsp;<a href="instr.php?op=delinstr&instrid=<{$insInstr.instrid}>"><img src="../assets/icons/delete_mini.png" alt="<{$lang_del}>" title="<{$lang_del}>"></a>
      </td>
    </tr>
  <{/foreach}>
  <{/if}>
    <tr class="foot">
      <td><a href="instr.php?op=editinstr"><img src="../assets/icons/edit_mini.png" alt="<{$lang_addinstr}>" title="<{$lang_addinstr}>"></a></td>
      <td></td>
      <td colspan="2"><{if $insPagenav}><{$insPagenav}><{/if}></td>
    </tr>
  </table>