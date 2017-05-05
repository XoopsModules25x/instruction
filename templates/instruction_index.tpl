<{* Форма выбора категории *}>
<div style="font: bold italic 1.6em serif; text-align: center; vertical-align:middle; line-height: 1.4;"><img src="<{$xoops_url}>/modules/soapbox/assets/images/icon/open.png" style="width:36px; height:24px;" alt="open" />&nbsp;Инструкции&nbsp;<img src="<{$xoops_url}>/modules/soapbox/assets/images/icon/close.png" style="width:36px; height:24px;" alt="close" /></div>
<!--<div style="text-align: center;">
  <form name="insformselcat" action="<{$xoops_url}>/modules/instruction/index.php" method="get">
    <{$smarty.const._MD_INSTRUCTION_SELCAT}>
    <{$insFormSelCat}>
    <input type="submit" value="<{$smarty.const._MD_INSTRUCTION_GO}>" class="formButton" />
  </form>
</div>-->
<br />

<{* Описание категории *}>

<{* Список инструкций *}>
<{if $insListInstr}>
  <table class="outer" style="border-spacing:1px;width:100%;">
    <tr>
      <th style="text-align:center;"><{$smarty.const._MD_INSTRUCTION_TITLE}></th>
      <th style="text-align:center;"><{$smarty.const._MD_INSTRUCTION_CAT}></th>
      <th style="text-align:center; width:100px;"><{$smarty.const._MD_INSTRUCTION_PAGES}></th>
      <th style="text-align:center; width:100px;"><{$smarty.const._MD_INSTRUCTION_ACTION}></th>
    </tr>
  <{foreach from=$insListInstr item=insInstr}>
    <tr class="<{$insInstr.class}>">
      <td><a href="<{$xoops_url}>/modules/instruction/instr.php?id=<{$insInstr.instrid}>"><{$insInstr.title}></a></td>
      <td><a href="<{$xoops_url}>/modules/instruction/index.php?cid=<{$insInstr.cid}>"><{$insInstr.ctitle}></a></td>
      <td style="text-align:center; width:100px;"><{$insInstr.pages}></td>
      <td style="text-align:center; width:100px;">
        <{if $insInstr.permsubmit}><a href="<{$xoops_url}>/modules/instruction/submit.php?op=editpage&amp;instrid=<{$insInstr.instrid}>"><img src="./images/icons/add_mini.png" alt="<{$smarty.const._MD_INSTRUCTION_ADDPAGE}>" title="<{$smarty.const._MD_INSTRUCTION_ADDPAGE}>" /></a><{/if}> 
        <{if $insInstr.permedit}><img src="./images/icons/edit_mini.png" alt="<{$smarty.const._MD_INSTRUCTION_EDIT}>" title="<{$smarty.const._MD_INSTRUCTION_EDIT}>" /><{/if}> 
      </td>
    </tr>
  <{/foreach}>
    <tr class="foot">
      <td colspan="2"></td>
      <td><{if $insPagenav}><{$insPagenav}><{/if}></td>
    </tr>
  </table>
<{/if}>