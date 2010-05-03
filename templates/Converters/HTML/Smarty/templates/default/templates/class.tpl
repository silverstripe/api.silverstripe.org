{include file="header.tpl" eltype="class" hasel=true contents=$classcontents}

<div class="section">
	<dl>
		<dt>Inheritance:</dt>
		<dd>{section name=tree loop=$class_tree.classes}{$class_tree.classes[tree]} <span class="inheritance-arrow">&lt;</span> {/section}</dd>
		
		<dt>Summary:</dt>
		<dd>{$sdesc|default:''}</dd>
		
		{if $tutorial}
		<dt>{if $is_interface}Interface{else}Class{/if} Tutorial:</dt>
		<dd>{$tutorial}</dd>
		{/if}
		
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

<div class="section" id="variables-overview">
	<h3><a href="#class_vars">Variables</a></h3>
	{if count($vars)}
	<table>
		<tbody>
			{section name=vars loop=$vars}
			{if !$vars[vars].static}
			<tr>
				<td><a href="#{$vars[vars].var_dest}">var {$vars[vars].var_name}</a></td>
				<td>{$vars[vars].sdesc}</td>
			</tr>
			{/if}
			{/section}
			{section name=vars loop=$vars}
			{if $vars[vars].static}
			<tr>
				<td><a href="#{$vars[vars].var_dest}">static var {$vars[vars].var_name}</a></td>
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

<p class="toggle"><a href="#inherited-variables-overview">Show inherited methods</a></p>
<div class="section" id="inherited-variables-overview">
	<h3>Inherited Variables</h3>
	{if count($ivars)}
	<table>
		<tbody>
			{section name=ivars loop=$ivars}
			{section name=ivars2 loop=$ivars[ivars].ivars}
			<tr>
				<td>{$ivars[ivars].ivars[ivars2].link}</td>
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

<div class="section" id="methods-overview">
	<h3><a href="#class_methods">Methods</a></h3>
	{if count($methods)}
	<table>
		<tbody>
			{section name=methods loop=$methods}
			{if $methods[methods].static}
			<tr>
				<td><a href="#{$methods[methods].method_dest}">static {$methods[methods].function_call}</a></td>
				<td>{$methods[methods].sdesc}</td>
			</tr>
			{/if}
			{/section}
			{section name=methods loop=$methods}
			{if $methods[methods].static}
			<tr>
				<td><a href="#{$methods[methods].method_dest}">{$methods[methods].function_call}</a></td>
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

<p class="toggle"><a href="#inherited-methods-overview">Show inherited methods</a></p>
<div class="section" id="inherited-methods-overview">
	<h3>Inherited Methods</h3>
	{if count($imethods)}
	<table>
		<tbody>
			{section name=imethods loop=$imethods}
			{section name=imethods2 loop=$imethods[imethods].imethods}
			<tr>
				<td>{$imethods[imethods].imethods[imethods2].link}</td>
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

<div class="section" id="constants-overview">
	<h3><a href="#class_consts">Constants</a></h3>
	{if count($consts)}
	<table>
		<tbody>
			{section name=consts loop=$consts}
			<tr>
				<td><a href="#{$consts[consts].const_dest}">var {$consts[consts].const_name}</a></td>
				<td>{$consts[consts].sdesc}</td>
			</tr>
			{/section}
		</tbody>
	</table>
	{else}
	<small>(none)</small>
	{/if}
</div>

<p class="toggle"><a href="#inherited-constants-overview">Show inherited methods</a></p>
<div class="section" id="inherited-constants-overview">
	<h3>Inherited Constants</h3>
	{if count($iconsts)}
	<table>
		<tbody>
			{section name=iconsts loop=$iconsts}
			{section name=iconsts2 loop=$iconsts[iconsts].iconsts}
			<tr>
				<td>{$iconsts[iconsts].iconsts[iconsts2].link}</td>
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

<div id="content">
<hr>
	<div class="contents">
{if $children}
	<h2>Child classes:</h2>
	{section name=kids loop=$children}
	<dl>
	<dt>{$children[kids].link}</dt>
		<dd>{$children[kids].sdesc}</dd>
	</dl>
	{/section}</p>
{/if}
	</div>

	<div class="leftCol">
    {if $implements}
    <h2>Implements interfaces</h2>
    <ul>
        {foreach item="int" from=$implements}<li>{$int}</li>{/foreach}
    </ul>
    {/if}

	<hr>

	<a name="class_details"></a>
	<h2>Class Details</h2>
	{include file="docblock.tpl" type="class" sdesc=$sdesc desc=$desc}
	<p class="top">[ <a href="#top">Top</a> ]</p>

	<hr>
	<a name="class_vars"></a>
	<h2>Class Variables</h2>
	{include file="var.tpl"}

	<hr>
	<a name="class_methods"></a>
	<h2>Class Methods</h2>
	{include file="method.tpl"}

	<hr>
	<a name="class_consts"></a>
	<h2>Class Constants</h2>
	{include file="const.tpl"}
</div>
{include file="footer.tpl"}
