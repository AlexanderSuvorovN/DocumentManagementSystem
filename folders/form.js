$(function()
{
    const _debug = true;
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
                else
                {
                    if(folder_item.documents.length)
                    {
                        let msg_items = [];
                        msg_items.push("The folder contains " + folder_item.documents.length + " associated " + ((folder_item.documents.length === 1) ? "document" : "documents") + ".\n");
                        msg_items.push("If this folder gets deleted the folder association information for these documents will be erased.\n");
                        msg_items.push("Please confirm delete of this record and erase of the folder information for the associated documents.");
                        let message = "";
                        msg_items.forEach(item => message += item );
                        if(!confirm(message))
                        {
                            form_submit = false;
                            return false;
                        }
                    }
                }
            }
            if(folder_item.label_text.length === 0)
            {
                alert("Label text must be specified.");
                form_submit = false;
            }
            folder_item.description = description_cke.getData();
            if(form_submit)
            {
                json = JSON.stringify(folder_item);
                form.json_node.val(json);
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
    form.node = $("#folder-item-form");
    form.mode = form.node.find("input[name='form_mode']").val().trim();
    form.operation = form.node.find("input[name='form_operation']").val().trim();
    form.select = $("section.actionbar > div.operation-container > select[name='operation']");
    form.submit_button = $("section.actionbar > a.button.submit");    
    form.operations = {};
    form.node.find("input[type='hidden'][name='form_operations[]']")
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
    form.json_node = form.node.find("input[type='hidden'][name='json']");
    let folder_item = JSON.parse(form.json_node.val().trim());
    console.log(folder_item);
    $("section.folder-item").find("input[type='text'][name='label_text']").val(folder_item.label_text).on("input", e => folder_item.label_text = $(e.currentTarget).val().trim());
    function image_select_callback()
    {
        let combobox = this;
        preview_node.css("background-image", "url('/dms/folders/images/" + combobox.value + "')").show();
    }
    new Combobox(
            $("div.combobox.label-image"), 
            {
                list_items_source: "html",
                select_callback: image_select_callback,
                allow_new_items: false,
                value: folder_item.label_image
            });
    let preview_node = $("section.folder-item").find("div.preview");
    let description_node = $("section.folder-item").find("textarea[name='description']");
    description_node.html(folder_item.description);
    let description_cke = null;
    ClassicEditor.create(description_node[0]).then(editor => { description_cke = editor }).catch(error => { console.error( error )});
});