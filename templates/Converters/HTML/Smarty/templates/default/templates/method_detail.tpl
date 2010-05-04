<div class="section member-detail member-detail-{cycle values = 'odd,even'} method-detail">
{if $show == 'summary'}
	<p>{if $methods[methods].static}static {/if}method {$methods[methods].function_call}, {$methods[methods].sdesc}</p>
{else}
	<a name="{$methods[methods].method_dest}"></a>
	<h3>
		{include file="access.tpl" access=$methods[methods].access}
		{if $methods[methods].static}static {/if}{$methods[methods].function_name}
		<a class="anchor" href="#{$methods[methods].method_dest}" title="Link to this section">¶</a>
	</h3>
	<div class="indent">
		<p>
		<code>{if $methods[methods].static}static {/if}{$methods[methods].function_return} {if $methods[methods].ifunction_call.returnsref}&amp;{/if}{$methods[methods].function_name}(
{if count($methods[methods].ifunction_call.params)}
{section name=params loop=$methods[methods].ifunction_call.params}
{if $smarty.section.params.iteration != 1}, {/if}
{if $methods[methods].ifunction_call.params[params].hasdefault}[{/if}{$methods[methods].ifunction_call.params[params].type}
{$methods[methods].ifunction_call.params[params].name}{if $methods[methods].ifunction_call.params[params].hasdefault} = {$methods[methods].ifunction_call.params[params].default}]{/if}
{/section}
{/if})</code>
		</p>

		<p class="linenumber">[line {if $methods[methods].slink}{$methods[methods].slink}{else}{$methods[methods].line_number}{/if}]</p>
		{include file="docblock.tpl" sdesc=$methods[methods].sdesc desc=$methods[methods].desc tags=$methods[methods].tags}
	
{if $methods[methods].descmethod}
	<p>Overridden in child classes as:<br />
	{section name=dm loop=$methods[methods].descmethod}
	<dl>
	<dt>{$methods[methods].descmethod[dm].link}</dt>
		<dd>{$methods[methods].descmethod[dm].sdesc}</dd>
	</dl>
	{/section}</p>
{/if}

{if $methods[methods].method_overrides.link}
	<p>
		Overrides {$methods[methods].method_overrides.link} 	
		({$methods[methods].method_overrides.sdesc|default:"parent method not documented"})
	</p>
{/if}

{if $methods[methods].method_implements}
	<hr class="separator" />
	<div class="notes">Implementation of:</div>
{section name=imp loop=$methods[methods].method_implements}
	<dl>
		<dt>{$methods[methods].method_implements[imp].link}</dt>
		{if $methods[methods].method_implements[imp].sdesc}
		<dd>{$methods[methods].method_implements[imp].sdesc}</dd>
		{/if}
	</dl>
{/section}
{/if}

{if count($methods[methods].params)}
	<h4>Parameters:</h4>
	<ul class="parameters">
	{section name=params loop=$methods[methods].params}
		<li>
			<span class="type">{$methods[methods].params[params].datatype}</span>
			<code>{$methods[methods].params[params].var}</code> 
			- {$methods[methods].params[params].data}
		</li>
	{/section}
	</ul>
{/if}
	
	</div>
	<p class="top">[ <a href="#top">Top</a> ]</p>
{/if}
</div>