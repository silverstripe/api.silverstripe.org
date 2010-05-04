{section name=vars loop=$vars}
	{if $vars[vars].static}
		{include file="var_detail.tpl"}
	{/if}
{/section}

{section name=vars loop=$vars}
	{if !$vars[vars].static}
		{include file="var_detail.tpl"}
	{/if}
{/section}
