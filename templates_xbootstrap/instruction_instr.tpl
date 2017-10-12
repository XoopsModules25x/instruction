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
<a name="text"></a>
<div class="item">
    <div class="itemHead table table-bordered table-responsive">
        <span class="itemTitle" style="padding:5px;"><{$insInstr.title}></span>
    </div>
    <div class="itemBody table table-bordered table-responsive" style="padding:5px;">
        <p class="itemText"><{$insInstr.description}></p>
    </div>
    <{* Теги *}>
    <{if $tags}>
        <div class="table table-bordered table-responsive"><{include file="db:tag_bar.tpl"}></div>
    <{/if}>
    <div class="itemFoot table table-bordered table-responsive">
        <span class="itemAdminLink"><{$insInstr.adminlink}>&nbsp;<a href="#instrmenu"><{$lang_menu}></a></span>
    </div>
</div>
