{if $sdesc != ''}
	<p class="summary">{$sdesc|default:''}</p>
{/if}

{if $desc != ''}
	<div class="desc">{$desc|default:''}</div>
{/if}

{if count($tags)}
<h4>Tags:</h4>
<ul>
{section name=tag loop=$tags}
	<li><b>{$tags[tag].keyword}</b> - {$tags[tag].data}</li>
{/section}
</ul>
{/if}
