$(function()
{    
	let scans_section_node = $("section.scans");
	let scans = JSON.parse(scans_section_node.find("input[type='hidden'][name='json']").val().trim());	
	let button_view_list_type_table_node = $("section.actionbar").find("a.button.view.list-type-table");
	let button_view_list_type_list_node = $("section.actionbar").find("a.button.view.list-type-list");
	let button_reload_scans_node = $("section.actionbar").find("a.button.reload-scans");
	let view_list_type = scans_section_node.find("input[type='hidden'][name='view_list_type']").val().trim();
	let list_container_node = scans_section_node.find("div.list-container");
	let records_count_node = scans_section_node.find("div.records-count");
	function displayListAsTable()
	{
		console.log("scans.js > displayListAsTable");
		let table_node = $("<table></table>");
		let thead_node = $("<thead></thead>").appendTo(table_node);
		let tr_node = $("<tr></tr>");
		$("<th></th>").addClass("scan-id").text("Id").appendTo(tr_node);
		$("<th></th>").addClass("scan-filename").text("Filename").appendTo(tr_node);
		$("<th></th>").addClass("scan-file-exists").text("File Exists").appendTo(tr_node);
		$("<th></th>").addClass("document-name").text("Document Name").appendTo(tr_node);
		tr_node.appendTo(thead_node);
		let tbody_node = $("<tbody></tbody>").appendTo(table_node);
		scans.forEach(
			function(scan_item, index)
			{
				let tr_node = $("<tr></tr>");
				let a_node = $("<a></a>")
					.attr("href", "view?id=" + scan_item.id)
					.attr("title", "view / update / delete")
					.text(scan_item.scan_id);
				$("<td></td>").addClass("scan-id").append(a_node).appendTo(tr_node);
				$("<td></td>").addClass("scan-filename").text(scan_item.scan_filename).appendTo(tr_node);
				let img_node = $("<img>").attr("src", "/images/" + ((scan_item.scan_file_exists) ? "icon-check.svg" : "icon-x.svg"));
				$("<td></td>").addClass("scan-file-exists").append(img_node).appendTo(tr_node);
				$("<td></td>").addClass("document-name").text(scan_item.document_name).appendTo(tr_node);
				tr_node.appendTo(tbody_node);
			});
		button_view_list_type_list_node.removeClass("active");
		list_container_node.empty().append(table_node).show();
		button_view_list_type_table_node.addClass("active");
		records_count_node.text("Found " + scans.length + " " + ((scans.length === 1) ? "record" : "records") + ".");
	}
	function displayListAsList()
	{
		console.log("scans.js > displayListAsList");
		let cards_collection = $();
		scans.forEach(
			function(scan_item, index)
			{		
				let card_node = $("<div></div>").addClass("card");
				let ext = scan_item.scan_filename.match(/[^.]+$/);
				let img_node = $("<div></div>").addClass("image");
				if(ext)
				{
					switch(ext[0])
					{
						case "jpg":
						case "jpeg":
							img_node.css("background-image", "url('/dms/scans/files/" + scan_item.scan_filename + "')");
							break;
						case "pdf":
							img_node.css("background-size", "contain").css("background-position", "top").css("background-image", "url('/images/icon-adobe-pdf.svg')");
							break;
						default:
							img_node.css("background-image", "url('/images/icon-file.svg')");
							break;
					}
				}
				else
				{
					img_node.css("background-image", "url('/images/icon-file-does-not-exist.svg')");
				}
				$("<a></a>")
					.addClass("preview")
					.attr("href", "view?id=" + scan_item.id)
					.attr("title", "view / update / delete")
					.append(img_node)
					.appendTo(card_node);
				let info_node = $("<div></div>").addClass("info").appendTo(card_node);
				$("<a></a>")
					.attr("href", "view?id=")
					.attr("title", "view / update / delete")
					.addClass("scan-filename")
					.text(scan_item.scan_filename)
					.appendTo(info_node);
				let document_name_node = $("<div></div>").addClass("document-name").appendTo(info_node);
				if(scan_item.document_name)
				{
					document_name_node.text("Document: ");
					$("<a></a>")
						.attr("href", "view?id=")
						.attr("title", "View document")
						.text(scan_item.document_name)
						.appendTo(document_name_node);
				}
				else
				{
					document_name_node.text("No document associated with this scan");
				}
				cards_collection = cards_collection.add(card_node);
			});
		button_view_list_type_table_node.removeClass("active");
		list_container_node.empty().append(cards_collection).show();
		button_view_list_type_list_node.addClass("active");
		records_count_node.text("Found " + scans.length + " " + ((scans.length === 1) ? "record" : "records") + ".");
	}
	function displayList(list_type = view_list_type)
	{
		switch(list_type)
		{
			case "list":
				displayListAsList();
				break;
			case "table":
			default:
				displayListAsTable();
				break;
		}
	}
	function displayError()
	{
		let $msg = "Can't retrieve information about scans.";
		list_container_node.hide();
		$("<div></div>").addClass("error").appendTo(list_container_node);
	}
	$().add(button_view_list_type_table_node).add(button_view_list_type_list_node).on(
		"click",
		function(e)
		{
			let match = $(this).attr("class").match(/\s?list-type-(\w+)\s?/i);
			if(match)
			{
				view_list_type = match[1].toLowerCase();
				displayList();
				$.post("/dms/ajax-view-settings", {pathname: window.location.pathname, view_list_type: view_list_type}, null, "text")
					.done(
						function(data)
						{
							console.log(data);
						});
				}
			e.preventDefault();
		});
	$(button_reload_scans_node).on(
		"click",
		function(e)
		{
			console.log("scan.js > reload scans");
			$.get("/dms/scans-reload-ajax", null, null, "json")
				.done(
					function(data)
					{
						console.log("scan.js > scans have been reloaded");
						console.log(data);
						if(data.return_code === "ok")
						{
							scans = data.data;
							displayList();
						}
						else
						{
							alert(data.messages[data.messages.length - 1]);
							displayError();
						}
					});
		});
	displayList();
});