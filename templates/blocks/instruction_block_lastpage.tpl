<div class="instr-block-lastpage">
    <ul>
        <{foreachq from=$block item=insBlockLastPage}>
        <li><a href="<{$xoops_url}>/modules/instruction/instr.php?id=<{$insBlockLastPage.instrid}>"><{$insBlockLastPage.ititle}></a>:&nbsp;<a href="<{$xoops_url}>/modules/instruction/page.php?id=<{$insBlockLastPage.pageid}>#pagetext"><{$insBlockLastPage.ptitle}></a></li>
        <{/foreach}>
    </ul>
</div>
