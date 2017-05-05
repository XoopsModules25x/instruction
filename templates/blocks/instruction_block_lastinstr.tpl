<div class="instr-block-lastinstr">
  <ul>
  <{foreachq from=$block item=insBlockLastInstr}>
    <li><a href="<{$xoops_url}>/modules/instruction/instr.php?id=<{$insBlockLastInstr.instrid}>"><{$insBlockLastInstr.ititle}></a>&nbsp;(&nbsp;<span title="<{$smarty.const._MB_INSTR_DATEUPDATE}>"><{$insBlockLastInstr.dateupdated}></span>,&nbsp;<span title="<{$smarty.const._MB_INSTR_NUMPAGES}>"><{$insBlockLastInstr.pages}></span>&nbsp;)</li>
  <{/foreach}>
  </ul>
</div>