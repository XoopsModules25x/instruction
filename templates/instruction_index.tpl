<{* Форма выбора категории *}>
<div class="InstrNavIndex">
  <img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/assets/images/open.png" style="width:36px;" alt="open" />
  &nbsp;<{$smarty.const._MD_INSTRUCTION_MODULE_NAME}>&nbsp;
  <img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/assets/images/close.png" style="width:36px;" alt="close" />
</div>
<div style="text-align: center;">
  <form name="insformselcat" action="<{$xoops_url}>/modules/<{$xoops_dirname}>/" method="get">
    <{$smarty.const._MD_INSTRUCTION_SELCAT}>
    <{$insFormSelCat}>
    <input type="submit" value="<{$smarty.const._MD_INSTRUCTION_GO}>" class="formButton" />
  </form>
</div>
<br>

<{* Описание категории *}>

<{* Список инструкций *}>
<{if $insListInstr}>
  <table class="outer" style="width:100%;">
    <tr>
      <th style="text-align:center;"><{$smarty.const._MD_INSTRUCTION_TITLE}></th>
      <!--<th style="text-align:center;"><{$smarty.const._MD_INSTRUCTION_CAT}></th>-->
      <th style="text-align:center; width:100px;"><{$smarty.const._MD_INSTRUCTION_PAGES}></th>
      <th style="text-align:center; width:100px;"><{$smarty.const._MD_INSTRUCTION_ACTION}></th>
    </tr>
  <{foreach from=$insListInstr item=insInstr}>
    <tr class="<{$insInstr.class}>">
      <td><a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/instr.php?id=<{$insInstr.instrid}>"><{$insInstr.title}></a></td>
      <!--<td><a href="<{$xoops_url}>/modules/instruction/index.php?cid=<{$insInstr.cid}>"><{$insInstr.ctitle}></a></td>-->
      <td style="text-align:center; width:100px;"><{$insInstr.pages}></td>
      <td style="text-align:center; width:100px;">
      <{if $xoops_isuser}>
        <{if $insInstr.permsubmit}><a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/submit.php?op=editpage&amp;instrid=<{$insInstr.instrid}>"><img src="./assets/icons/add_mini.png" alt="<{$smarty.const._MD_INSTRUCTION_ADDPAGE}>" title="<{$smarty.const._MD_INSTRUCTION_ADDPAGE}>" /></a><{/if}> 
        <{if $insInstr.permedit}><a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/admin/instr.php?op=editinstr&instrid=<{$insInstr.instrid}>"><img src="./assets/icons/edit_mini.png" alt="<{$smarty.const._MD_INSTRUCTION_EDIT}>" title="<{$smarty.const._MD_INSTRUCTION_EDIT}>" /></a><{/if}> 
      <{/if}>
      <a href="mailto:?subject=<{$smarty.const._MD_INSTRUCTION_MAIL_INTART}>&body=<{$smarty.const._MD_INSTRUCTION_MAIL_INTARTFOUND}><{$xoops_sitename}>&nbsp;--&nbsp;<{$xoops_url}>/modules/<{$xoops_dirname}>/instr.php?id=<{$insInstr.instrid}>">
        <img style="width:24px;" src="./assets/icons/mail_foward.png" alt="<{$smarty.const._MD_INSTRUCTION_MAIL}>" title="<{$smarty.const._MD_INSTRUCTION_MAIL}>" /></a>
      </td>
    </tr>
  <{/foreach}>
    <tr class="foot">
      <td style="text-align:center;" colspan="3"><{if $insPagenav}><{$insPagenav}><{/if}></td>
    </tr>
  </table>
<{/if}>
