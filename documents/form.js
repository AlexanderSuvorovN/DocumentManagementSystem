$(function()
{
    const _debug = true;
    let clock;
    if($("input[name='form_error']").length)
    {
        return;
    }
    $(document).on(
        "click",
        "section.actionbar > a.button.submit",
        function(e)
        {
            let submit_type = "ajax";
            let url = $(this).attr("href").trim();
            let form_submit = true;
            _debug && console.log("submit_type: " + submit_type);
            _debug && console.log("form url: " + url);
            if(form.operation === "delete")
            {
                if(!confirm("Delete this record?"))
                {
                    form_submit = false;
                    return false;
                }
            }
            form.mode === "view" && (document_item.id = $("section.document-item input[name='id']").val().trim());
            if(document_item.name.length === 0)
            {
                alert("Document name must be specified.");
                form_submit = false;
            }
            document_item.description = ckes['description'].getData();
            if(form_submit && document_item.scans.length === 0)
            {
                form_submit = confirm("Document has no scans defined. Submit?");
            }
            else
            {
                let duplicates = {};
                document_item.scans.forEach(
                    function(item, index)
                    {
                        for(let i = 0; i < document_item.scans.length; i++)
                        {
                            if(index !== i && item === document_item.scans[i])
                            {
                                if(duplicates[item] === undefined)
                                {
                                    duplicates[item] = [index];
                                }
                                if(duplicates[item].indexOf(i) === -1)
                                {
                                    duplicates[item].push(i);
                                }
                            }
                        }
                    });
                console.log("duplicates: ");
                console.log(duplicates);
                if(Object.keys(duplicates).length)
                {
                    form_submit = false;
                    let message = "";
                    for(const key of Object.keys(duplicates))
                    {
                        let dups = duplicates[key];
                        let indexes_str = "";
                        for(const index of dups)
                        {
                            (indexes_str.length) && (indexes_str += ", ");
                            indexes_str += "#" + (index + 1);
                        }
                        (message.length) && (message += " \n");
                        message += "Scans " + indexes_str + " use the same filename '" + key + "'.";
                    }
                    alert(message);
                }
            }
            if(form_submit)
            {
                json = JSON.stringify(document_item);
                form.json.val(json);
                _debug && console.log(json);
                if(submit_type === "ajax")
                {
                    return_url = $("div.breadcrumb").find("a").last().attr("href");
                    _debug && console.log("return_url: " + return_url);
                    $.post(url, {"json" : json}, null, "json").done(
                        function(data)
                        {
                            console.log(data);
                            if(data.return_code === "ok")
                            {
                                true && (window.location.href = return_url)
                            }
                            else
                            {
                                alert(data.messages[data.messages.length-1]);
                            }
                        });
                }
                else if(submit_type === "form")
                {
                    form.node.attr("method", "post").attr("action", url).submit();
                }
            }
            e.preventDefault();
        });
    $(document).on(
        "change",
        "section.actionbar > div.operation-container > select[name='operation']",
        function(e)
        {
            form.operation = $(this).val().trim();
            form.submit_button.attr("href", form.operations[form.operation]);
            if(form.operation !== "delete")
            {
                form.submit_button.text("Submit");
            }
            else
            {
                form.submit_button.text(form.operation);
            }
            // console.log("form.operation: " + form.operation + ", url: " + form.operations[form.operation]);
        });
    let form = {};
    form.node = $("#document-item-form");
    form.mode = $("section.document-item > input[name='form_mode']").val().trim();
    form.operation = $("section.document-item > input[name='form_operation']").val().trim();
    form.select = $("section.actionbar > div.operation-container > select[name='operation']");
    form.submit_button = $("section.actionbar > a.button.submit");    
    form.operations = {};
    $("section.document-item > input[type='hidden'][name='form_operations[]']")
        .each(
            function(ix)
            {
                let op = $(this).data("operation").trim();
                let url = $(this).data("url").trim();
                let opText = op.replace(/\b\w/g, l => l.toUpperCase());
                $("<option></option>").attr("value", op).text(opText).appendTo(form.select);
                form.operations[op] = url;
                // console.log("form operation: " + $(this).val());
            });
    form.select.val(form.operation).change();
    if(Object.keys(form.operations).length <= 1)
    {
        form.select.attr("disabled", "disabled");
    }
    form.json = $("section.document-item input[name='json']");
    $("input[name='document_name']").on(
        "input",
        function(e)
        {
            document_item.name = $(this).val().trim();
            _debug && console.log("document_item.name: " + document_item.name);
        });
    function CreateTag(tag_text)
    {
        let tag_node = $("section.dummy").find("div.tag").clone().find("span.text").text(tag_text).end();
        tag_node.find("div.button.remove").on("click", e => RemoveTag(tag_node));
        return tag_node;
    }
    function AddTag()
    {
        let combobox = this;
        let tag_text = combobox.value;
        if(tag_text && !combobox.list.node.is(":visible"))
        {
            let index = document_item.tags.indexOf(tag_text);
            (index !== -1) && RemoveTag($("div.tags-collection-container").find("div.tag").eq(index));
            CreateTag(tag_text).appendTo("div.tags-collection-container");
            document_item.tags.push(tag_text);
            _debug && console.log("document_item.tags: ");
            _debug && console.log(document_item.tags);
            combobox.clear_input();
            combobox.collapse();
        }
    }
    function RemoveTag(tag_node)
    {
        let index = tag_node.index(); //document_item.tags.indexOf(tag_node.find("span.text").text());
        _debug && console.log("tag index: " + index);
        document_item.tags.splice(index, 1);
        _debug && console.log("document_item.tags: ");
        _debug && console.log(document_item.tags);
        tag_node.remove();
    }
    function tags_on_input_enter()
    {
        console.log("tags_on_input_enter");
        AddTag();
        this.clear_input();
    }
    tags_combobox = new Combobox(
        $("div.combobox.tags"),
        {
            list_items_source: "html",
            ajax_url: "/dms/documents/form-ajax-tags.php",
            on_input_enter: AddTag,
            allow_new_items: false
        })
    function folder_select_callback()
    {
        document_item.folder_label_text = this.value;
        console.log("document_item.folder_label_text: " + document_item.folder_label_text);
    }
    function ScanContainer(scan_filename)
    {

    }
    ScanContainer.prototype.scan_set_preview = function()
    {

    }
    function scan_set_preview(scan_container_node)
    {
        let scan_filename = scan_container_node.find("div.combobox").data("value");
        console.log(scan_container_node.find("div.combobox").data("value"));
        let img_preview_node = scan_container_node.find("div.preview");
        let pdf_preview_node = scan_container_node.find("embed");
        _debug && console.log("scan_set_preview scan_filename: " + scan_filename);
        let no_preview = false;
        if(scan_filename !== "")
        {
            let ext = "";
            let match = scan_filename.match(/\.(\w+)$/);
            if(match !== null)
            {
                ext = match[1];
            }
            console.log("ext: " + ext);
            if(ext === "jpg" || ext === "jpeg")
            {
                pdf_preview_node.is(":visible") && pdf_preview_node.hide();
                img_preview_node.css("background-image", "url('/dms/scans/"+scan_filename+"')");
                $.get("/dms/documents/form-ajax-scan-data.php", {scan : scan_filename}, null, "json")
                    .done(
                        function(data)
                        {
                            _debug && console.log(data);
                            if(data.return_code === "ok")
                            {
                                !img_preview_node.is(":visible") && img_preview_node.css("height", 0).show();
                                let preview_width = img_preview_node.outerWidth();
                                let image_width = data.data[0];
                                let ratio = preview_width / image_width;
                                let image_height = data.data[1] * ratio;
                                _debug && console.log("preview_width: " + preview_width);
                                _debug && console.log("image_width: " + image_width);
                                _debug && console.log("ratio: " + ratio);
                                //img_preview_node.css("height", image_height);
                                img_preview_node.css("height", "75vh");
                            }
                            else
                            {
                                _debug && console.log("problem retrieving image data for '" + data.scan_filename + "': " + data.message);
                                no_preview = true;
                            }
                        });
            }
            else if(ext === "pdf")
            {
                img_preview_node.is(":visible") && img_preview_node.hide();
                pdf_preview_node.attr("src", "/dms/scans/" + scan_filename);
                !pdf_preview_node.is(":visible") && pdf_preview_node.show();
            }
            else
            {
                no_preview = true;
            }
        }
        else
        {
            no_preview = true;
        }
        if(no_preview)
        {
            img_preview_node.hide();
            pdf_preview_node.hide();
        }
    }
    function scan_select_callback()
    {
        let combobox = this;
        let scan_container_node = combobox.node.closest("div.scan-container");
        scan_set_preview(scan_container_node);
        let index = scan_container_node.index();
        _debug && console.log("scan index: " + index);
        document_item.scans[index] = combobox.value;
        _debug && console.log("document_item.scans: ");
        _debug && console.log(document_item.scans);
    }
    function create_scan_container(scan_filename)
    {
        let scan_container_node = $("section.dummy").find("div.scan-container").clone();
        new Combobox(
            scan_container_node.find("div.combobox"),
            {
                list_items_source: "html",
                select_callback: scan_select_callback,
                allow_new_items: false,
                value: scan_filename
            });
        scan_container_node.find("div.controls").find("div.remove").on("click", e => remove_scan(scan_container_node));
        let img_preview_node = $("<div></div>").addClass("preview").hide().appendTo(scan_container_node);
        let pdf_preview_node = $("<embed />").addClass("pdf-viewer").hide().appendTo(scan_container_node);
        scan_set_preview(scan_container_node);
        return scan_container_node;
    }
    function add_scan_container()
    {
        create_scan_container("").appendTo("div.scans-collection-container");
        document_item.scans.push("");
        _debug && console.log("document_item.scans: ");
        _debug && console.log(document_item.scans);
    }
    function remove_scan(scan_container_node)
    {
        let index = scan_container_node.index();
        _debug && console.log("scan index: " + index);
        document_item.scans.splice(index, 1);
        _debug && console.log("document_item.scans: ");
        _debug && console.log(document_item.scans);
        scan_container_node.remove();
    }
    $("#add-scan").on("click", e => add_scan_container() || e.preventDefault());
    let document_item = JSON.parse(form.node.find("input[type='hidden'][name='json']").val().trim());
    console.log(document_item);
    $("input[name='document_name']").val(document_item.name);
    $("table.general-info").find("input[name='id']").val(document_item.id);
    $("textarea[name='description']").html(document_item.description);
    let ckes = [];
    ClassicEditor
        .create(document.querySelector("textarea[name='description']"))
        .then(editor => { ckes['description'] = editor })
        .catch(error => { console.error( error ); });
    document_item.tags.forEach(item => CreateTag(item.text).appendTo("div.tags-collection-container"));
    new Combobox(
            $("div.combobox.folder"), 
            {
                list_items_source: "html",
                ajax_url: "/dms/documents/form-ajax-folders.php",
                select_callback: folder_select_callback,
                allow_new_items: false,
                value: document_item.folder_label_text
            });
    document_item.scans.forEach(scan_item => create_scan_container(scan_item['filename']).appendTo("div.scans-collection-container"));
});