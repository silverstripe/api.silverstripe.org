<?xml version="1.0" encoding="UTF-8" ?>
<pages>
{foreach key=name item=class from=$classes}
	<page type="class" title="{$name}" link="{$class.url}" path="">
		<sdesc><![CDATA[{$class.sdesc}]]></sdesc>
		<desc><![CDATA[{$class.desc}]]></desc>
{foreach item=prop from=`$class.methods`}
		<page type="method" title="{$prop.function_name}"  link="{$prop.url}" path="" static="{$prop.static}">
			<sdesc><![CDATA[{$prop.sdesc}]]></sdesc>
			<desc><![CDATA[{$prop.desc}]]></desc>
		</page>
{/foreach}
{foreach item=prop from=`$class.vars`}
		<page type="property" title="{$prop.var_name}" link="{$prop.url}" path="" static="{$prop.static}">
			<sdesc><![CDATA[{$prop.sdesc}]]></sdesc>
			<desc><![CDATA[{$prop.desc}]]></desc>
		</page>
{/foreach}
	</page>
{/foreach}
</pages>	