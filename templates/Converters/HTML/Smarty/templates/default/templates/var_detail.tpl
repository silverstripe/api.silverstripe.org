<div class="section member-detail member-detail-{cycle values = 'odd,even'} var-detail">
	{if $show == 'summary'}
		{if !$vars[vars].static}static {/if} {$vars[vars].var_name}, {$vars[vars].sdesc}<br>
	{else}
		<a name="{$vars[vars].var_dest}"></a>
		<h3>
			{if !$vars[vars].static}static {/if}{$vars[vars].var_name}{if $vars[vars].has_default} = <span class="value">{$vars[vars].var_default|replace:"\n":"<br>\n"|replace:" ":"&nbsp;"|replace:"\t":"&nbsp;&nbsp;&nbsp;"}</span>{/if}
			<a class="anchor" href="#{$vars[vars].var_dest}" title="Link to this section">¶</a>
		</h3>
		<div class="indent">
			<p class="linenumber">[line {if $vars[vars].slink}{$vars[vars].slink}{else}{$vars[vars].line_number}{/if}]</p>
			{include file="docblock.tpl" sdesc=$vars[vars].sdesc desc=$vars[vars].desc tags=$vars[vars].tags}
			<p><b>Type:</b> {$vars[vars].var_type}</p>
			<p><b>Overrides:</b> {$vars[vars].var_overrides}</p>
		</div>
		<p class="top">[ <a href="#top">Top</a> ]</p>
	{/if}
</div>