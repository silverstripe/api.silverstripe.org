{include file="header.tpl" eltype="class" hasel=true contents=$classcontents}

<div class="section class-overview">
	<dl>
		<dt>Inheritance:</dt>
		<dd>{section name=tree loop=$class_tree.classes}{$class_tree.classes[tree]} <span class="inheritance-arrow">&lt;</span> {/section}</dd>
		
		<dt>Summary:</dt>
		<dd>{$sdesc|default:''}</dd>
		
		{if $tutorial}
		<dt>{if $is_interface}Interface{else}Class{/if} Tutorial:</dt>
		<dd>{$tutorial}</dd>
		{/if}
		
		{section name=tag loop=$tags}
			{if $tags[tag].keyword eq "author"}{assign var="hasauthors" value=true}{/if}
		{/section}
		{if $hasauthors}
		<dt>Author(s):</dt>
		<dd>
			<ul>
				{section name=tag loop=$tags}
					{if $tags[tag].keyword eq "author"}
					<li>{$tags[tag].data}</li>
					{/if}
				{/section}
			</ul>
		</dd>
		{/if}
		
	</dl>

	<!-- 
	<h4>Version:</h4>
	<ul>
		{section name=tag loop=$tags}
			{if $tags[tag].keyword eq "version"}
			<li>{$tags[tag].data}</li>
			{/if}
		{/section}
	</ul>
	<h4>Copyright:</h4>
	<ul>
		{section name=tag loop=$tags}
			{if $tags[tag].keyword eq "copyright"}
			<li>{$tags[tag].data}</li>
			{/if}
		{/section}
	</li>
	</div>
	-->
</div>

<div class="section members-overview" id="variables-overview">
	<h3><a href="#class_vars">Variables</a></h3>
	{if count($vars)}
	<table>
		<tbody>
			{section name=vars loop=$vars}
			{if !$vars[vars].static}
			<tr class="{cycle values = 'odd,even'}">
				<td><code><a href="#{$vars[vars].var_dest}">{$vars[vars].var_name}</a></code></td>
				<td>{$vars[vars].sdesc}</td>
			</tr>
			{/if}
			{/section}
			{section name=vars loop=$vars}
			{if $vars[vars].static}
			<tr class="{cycle values = 'odd,even'}">
				<td><code><a href="#{$vars[vars].var_dest}">static {$vars[vars].var_name}</a></code></td>
				<td>{$vars[vars].sdesc}</td>
			</tr>
			{/if}
			{/section}
		</tbody>
	</table>
	{else}
	<small>(none)</small>
	{/if}
</div>

{if count($ivars)}
<p class="toggle">
	<small><a href="#inherited-variables-overview">Show/hide inherited methods</a></small>
</p>
<div class="section members-overview" id="inherited-variables-overview">
	<h3>Inherited Variables</h3>
	{if count($ivars)}
	<table>
		<tbody>
			{section name=ivars loop=$ivars}
			{section name=ivars2 loop=$ivars[ivars].ivars}
			<tr class="{cycle values = 'odd,even'}">
				<td><code>{$ivars[ivars].ivars[ivars2].link}</code></td>
				<td>{$ivars[ivars].ivars[ivars2].ivars_sdesc} </td>
			</tr>
			{/section}
			{/section}
		</tbody>
	</table>
	{else}
	<small>(none)</small>
	{/if}
</div>
{/if}

<div class="section members-overview" id="methods-overview">
	<h3><a href="#class_methods">Methods</a></h3>
	{if count($methods)}
	<table>
		<tbody>
			{section name=methods loop=$methods}
			{if $methods[methods].static}
			<tr class="{cycle values = 'odd,even'}">
				<td><code><a href="#{$methods[methods].method_dest}">static {$methods[methods].function_call}</a></code></td>
				<td>{$methods[methods].sdesc}</td>
			</tr>
			{/if}
			{/section}
			{section name=methods loop=$methods}
			{if $methods[methods].static}
			<tr class="{cycle values = 'odd,even'}">
				<td><code><a href="#{$methods[methods].method_dest}">{$methods[methods].function_call}</a></code></td>
				<td>{$methods[methods].sdesc}</td>
			</tr>
			{/if}
			{/section}
		</tbody>
	</table>
	{else}
	<small>(none)</small>
	{/if}
</div>

{if count($imethods)}
<p class="toggle">
	<small><a href="#inherited-methods-overview">Show/hide inherited methods</a></small>
</p>
<div class="section members-overview" id="inherited-methods-overview">
	<h3>Inherited Methods</h3>
	{if count($imethods)}
	<table>
		<tbody>
			{section name=imethods loop=$imethods}
			{section name=imethods2 loop=$imethods[imethods].imethods}
			<tr class="{cycle values = 'odd,even'}">
				<td><code>{$imethods[imethods].imethods[imethods2].link}</code></td>
				<td>{$imethods[imethods].imethods[imethods2].imethods_sdesc} </td>
			</tr>
			{/section}
			{/section}
		</tbody>
	</table>
	{else}
	<small>(none)</small>
	{/if}
</div>
{/if}

<div class="section members-overview" id="constants-overview">
	<h3><a href="#class_consts">Constants</a></h3>
	{if count($consts)}
	<table>
		<tbody>
			{section name=consts loop=$consts}
			<tr class="{cycle values = 'odd,even'}">
				<td><code><a href="#{$consts[consts].const_dest}">var {$consts[consts].const_name}</a></code></td>
				<td>{$consts[consts].sdesc}</td>
			</tr>
			{/section}
		</tbody>
	</table>
	{else}
	<small>(none)</small>
	{/if}
</div>

{if count($iconsts)}
<p class="toggle">
	<small><a href="#inherited-constants-overview">Show/hide inherited methods</a></small>
</p>
<div class="section members-overview" id="inherited-constants-overview">
	<h3>Inherited Constants</h3>
	{if count($iconsts)}
	<table>
		<tbody>
			{section name=iconsts loop=$iconsts}
			{section name=iconsts2 loop=$iconsts[iconsts].iconsts}
			<tr class="{cycle values = 'odd,even'}">
				<td><code>{$iconsts[iconsts].iconsts[iconsts2].link}</code></td>
				<td>{$iconsts[iconsts].iconsts[iconsts2].iconsts_sdesc}</td>
			</tr>
			{/section}
			{/section}
		</tbody>
	</table>
	{else}
	<small>(none)</small>
	{/if}
</div>
{/if}

{if $children}
<div id="content">
	<h2>Child classes:</h2>
	{section name=kids loop=$children}
	<dl>
	<dt>{$children[kids].link}</dt>
		<dd>{$children[kids].sdesc}</dd>
	</dl>
	{/section}</p>
	<hr>
</div>
{/if}

{if $implements}
<div class="section">
   <h2>Implements interfaces</h2>
   <ul>
       {foreach item="int" from=$implements}<li>{$int}</li>{/foreach}
   </ul>
	<hr>
</div>
{/if}

	<a name="class_details"></a>
	<h2>Class Details</h2>
	{include file="docblock.tpl" type="class" sdesc=$sdesc desc=$desc}
	<p class="top">[ <a href="#top">Top</a> ]</p>

	{if count($methods)}
	<hr>
	<a name="class_methods"></a>
	<h2>Class Methods</h2>
	{include file="method.tpl"}
	{/if}
	
	{if count($vars)}
	<hr>
	<a name="class_vars"></a>
	<h2>Class Variables</h2>
	{include file="var.tpl"}
	{/if}

	{if count($consts)}
	<hr>
	<a name="class_consts"></a>
	<h2>Class Constants</h2>
	{include file="const.tpl"}
	{/if}
</div>
{include file="footer.tpl"}
