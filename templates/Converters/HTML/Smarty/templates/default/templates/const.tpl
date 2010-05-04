{section name=consts loop=$consts}
	<div class="section member-detail member-detail-{cycle values = 'odd,even'} const-detail">
	{if $show == 'summary'}
		var {$consts[consts].const_name}, {$consts[consts].sdesc}<br>
	{else}
		<a name="{$consts[consts].const_dest}"></a>
		<p></p>
		<h3>{$consts[consts].const_name} = <span class="value">{$consts[consts].const_value|replace:"\n":"<br>\n"|replace:" ":"&nbsp;"|replace:"\t":"&nbsp;&nbsp;&nbsp;"}</span></h3>
		<div class="indent">
			<p class="linenumber">[line {if $consts[consts].slink}{$consts[consts].slink}{else}{$consts[consts].line_number}{/if}]</p>
			{include file="docblock.tpl" sdesc=$consts[consts].sdesc desc=$consts[consts].desc tags=$consts[consts].tags}
		</div>
		<p class="top">[ <a href="#top">Top</a> ]</p>
	{/if}
	</div>
{/section}
