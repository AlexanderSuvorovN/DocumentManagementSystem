<?php
	$date = (new DateTime())->format("Y");
?>
	<footer>
		&copy;&nbsp;<?= $date ?>&nbsp;Suvorov Business Consulting
	</footer>
	<script>
		$(function()
		{
			let breadcrumb_node = $("div.breadcrumb");
			if(breadcrumb_node.length)
			{
				let return_url = breadcrumb_node.find("a").last().attr("href").trim();
				$("<a></a>").addClass("return").attr("href", return_url).appendTo(breadcrumb_node);
			}
		});
	</script>