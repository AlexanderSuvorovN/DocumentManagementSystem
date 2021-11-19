$(function()
{    
	let server = JSON.parse($("body").find("input[type='hidden'][name='json']").val().trim());
	console.log(server);
	let folders = server.folders;
	let button_view_list_type_table_node = $("section.actionbar").find("a.button.view.list-type-table");
	let button_view_list_type_list_node = $("section.actionbar").find("a.button.view.list-type-list");
	let view_list_type = server.view_list_type;
	let list_container_node = $("section.folders").find("div.list-container");
	function displayListAsTable()
	{
		console.log("folders.js > displayListAsTable");
		let table_node = $("<table></table>");
		let thead_node = $("<thead></thead>").appendTo(table_node);
		let tr_node = $("<tr></tr>");
		$("<th></th>").addClass("scan-id").text("Id").appendTo(tr_node);
		$("<th></th>").addClass("scan-filename").text("Filename").appendTo(tr_node);
		$("<th></th>").addClass("scan-file-exists").text("File Exists").appendTo(tr_node);
		$("<th></th>").addClass("document-name").text("Document Name").appendTo(tr_node);
		tr_node.appendTo(thead_node);
		let tbody_node = $("<tbody></tbody>").appendTo(table_node);
		folders.forEach(
			function(folder_item, index)
			{
				let tr_node = $("<tr></tr>");
				let a_node = $("<a></a>")
					.attr("href", "view?id=" + folder_item.id)
					.attr("title", "view / update / delete")
					.text(folder_item.scan_id);
				$("<td></td>").addClass("scan-id").append(a_node).appendTo(tr_node);
				$("<td></td>").addClass("scan-filename").text(folder_item.scan_filename).appendTo(tr_node);
				let img_node = $("<img>").attr("src", "/images/" + ((folder_item.scan_file_exists) ? "icon-check.svg" : "icon-x.svg"));
				$("<td></td>").addClass("scan-file-exists").append(img_node).appendTo(tr_node);
				$("<td></td>").addClass("document-name").text(folder_item.document_name).appendTo(tr_node);
				tr_node.appendTo(tbody_node);
			});
		$("<div></div>").addClass("records-count").text("Found " + folders.length + " " + ((folders.length === 1) ? "record" : "records") + ".").appendTo(list_container_node);
		button_view_list_type_list_node.removeClass("active");
		list_container_node.empty().append(table_node).show();
		button_view_list_type_table_node.addClass("active");
	}
	function displayListAsList()
	{
		console.log("folders.js > displayListAsList");
		let cards_collection = $();
		folders.forEach(
			function(folder_item, index)
			{	
				const FILE_FORMAT_SUPPORTED = 0;
				const FILE_FORMAT_UNSUPPORTED = 1;
				const FILE_DOES_NOT_EXIST = 2;
				let card_node = $("<div></div>").addClass("card");
				let img_node = $("<div></div>").addClass("image");
				let img_status = ["supported-file-format", "unsupported-file-format", "file-does-not-exist"];
				if(folder_item.label_image)
				{
					let ext = folder_item.label_image.match(/[^.]+$/);
					if(ext)
					{
						switch(ext[0])
						{
							case "jpg":
							case "jpeg":
								image_status = FILE_FORMAT_SUPPORTED;
								break;
							default:
								image_status = FILE_FORMAT_UNSUPPORTED
								break;
						}
					}
					else
					{
					}
				}
				else
				{
					image_status = IMAGE_FILE_DOES_NOT_EXIST;
				}
					img_node.css("background-image", "url('/dms/folders/images/" + folder_item.label_image + "')");
					img_node.css("background-image", "url('/images/icon-file-does-not-exist.svg')");
					img_node.css("background-image", "url('/images/icon-file.svg')");
				$("<a></a>")
					.addClass("preview")
					.attr("href", "view?id=" + folder_item.id)
					.attr("title", "view / update / delete")
					.append(img_node)
					.appendTo(card_node);
				let info_node = $("<div></div>").addClass("info").appendTo(card_node);
				$("<a></a>")
					.attr("href", "view?id=")
					.attr("title", "view / update / delete")
					.addClass("label-text")
					.text(folder_item.label_text)
					.appendTo(info_node);
				$("<div></div>").addClass("description").html(folder_item.description).appendTo(info_node);
				$("<div></div>").addClass("documents-count").text("Documents: " + folder_item.documents_count).appendTo(info_node);
				cards_collection = cards_collection.add(card_node);
			});
		$("<div></div>").addClass("records-count").text("Found " + folders.length + " " + ((folders.length === 1) ? "record" : "records") + ".").appendTo(list_container_node);
		button_view_list_type_table_node.removeClass("active");
		list_container_node.empty().append(cards_collection).show();
		button_view_list_type_list_node.addClass("active");
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
		let $msg = "Can't retrieve information about folders.";
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
	displayList();
});