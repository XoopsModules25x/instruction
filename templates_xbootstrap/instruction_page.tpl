<div class="InstrNavIndexP breadcrumb"><{$insNav}></div>
<br>
<{* Список страниц *}>
<a name="instrmenu"></a>
<div class="breadcrumb">
    <{if $insListPage}>
        <{$insListPage}>
    <{/if}>
</div>

<br>
<a name="pagetext"></a>
<div class="item">
    <div class="itemHead table table-bordered table-responsive>
        <span class="itemTitle" style="padding:5px;"><{$insPage.title}></span>
    </div>
    <div class="itemBody table table-bordered table-responsive" style="padding:5px;">
        <div class="itemText"><{$insPage.hometext}></div>
    </div>

    <{* Сноска *}>
    <{if $insPage.footnotes}>
        <div id="instr-footnotes">
            <b><{$smarty.const._MD_INSTRUCTION_FOOTNOTESC}></b>
            <ul>
                <{foreach from=$insPage.footnotes item=insFootnote}>
                    <li><{$insFootnote}></li>
                <{/foreach}>
            </ul>
        </div>
    <{/if}>

    <div class="itemFoot">
    <span class="itemAdminLink table table-bordered table-responsive">
      <{$insPage.adminlink}>
        <{if $insPrevpages}><a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/page.php?id=<{$insPrevpages.pageid}>#pagetext" title="<{$insPrevpages.title}> <{$smarty.const._MD_INSTR_PREVPAGE}>"><{$smarty.const._MD_INSTR_PREVPAGE_HTML}></a><{/if}>
        <a href="#instrmenu"><{$lang_menu}></a>
        <{if $insNextpages}><a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/page.php?id=<{$insNextpages.pageid}>#pagetext" title="<{$smarty.const._MD_INSTR_NEXTPAGE}> <{$insNextpages.title}>"><{$smarty.const._MD_INSTR_NEXTPAGE_HTML}></a><{/if}>
    </span>
    </div>
</div>
<{* Комментарии *}>
<div style="text-align: center; padding: 3px; margin:3px;">
    <{$commentsnav}>
    <{$lang_notice}>
</div>
<div style="margin:3px; padding: 3px;">
    <{if $comment_mode == "flat"}>
        <{include file="db:system_comments_flat.tpl"}>
    <{elseif $comment_mode == "thread"}>
        <{include file="db:system_comments_thread.tpl"}>
    <{elseif $comment_mode == "nest"}>
        <{include file="db:system_comments_nest.tpl"}>
    <{/if}>
</div>
