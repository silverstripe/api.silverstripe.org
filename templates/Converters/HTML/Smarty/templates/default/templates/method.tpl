{section name=methods loop=$methods}
	{if $methods[methods].static}
		{include file="method_detail.tpl"}
	{/if}
{/section}

{section name=methods loop=$methods}
	{if !$methods[methods].static}
		{include file="method_detail.tpl"}
	{/if}
{/section}
