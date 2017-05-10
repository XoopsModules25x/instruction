<div style="font: bold italic 1.2em serif; text-align: left; vertical-align:middle; line-height: 1.4;"><{$insNav}></div>
<br />

<{* Список страниц *}>
<a name="instrmenu"></a>
<div>
<{if $insListPage}>
  <{$insListPage}>
<{/if}>
</div>

<br />
<a name="text"></a>
<div class="item">
  <div class="itemHead">
    <span class="itemTitle"><{$insInstr.title}></span>
  </div>
  <div class="itemBody">
    <p class="itemText"><{$insInstr.description}></p>
  </div>
  <{* Теги *}>
  <{if $tags}>
    <div><{include file="db:tag_bar.tpl"}></div>
  <{/if}>
  <div class="itemFoot">
    <span class="itemAdminLink"><{$insInstr.adminlink}>&nbsp;<a href="#instrmenu"><{$lang_menu}></a></span>
  </div>
</div>