{if $sdesc != ''}
	<p class="summary">{$sdesc|default:''}</p>
{/if}

{if $desc != ''}
	<div class="desc">{$desc|default:''}</div>
{/if}

{if count($tags)}
<ul class="tags">
{section name=tag loop=$tags}
	{if $tags[tag].keyword != 'access'}
	{if $tags[tag].keyword eq "deprecated"}
		{assign var="tagclass" value="warning"}
	{else}
		{assign var="tagclass" value=""}
	{/if}
	<li class="{$tagclass}">
		<strong class="tagblock">@{$tags[tag].keyword}</strong>
		{$tags[tag].data}
	</li>
	{/if}
{/section}
</ul>
{/if}
