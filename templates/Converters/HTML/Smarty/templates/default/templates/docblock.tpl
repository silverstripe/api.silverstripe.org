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
	<li>
		<strong class="tagblock">{$tags[tag].keyword}</strong>
		{$tags[tag].data}
	</li>
	{/if}
{/section}
</ul>
{/if}
