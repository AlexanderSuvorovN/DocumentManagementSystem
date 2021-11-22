<?php
    require_once("./../common.php");
    require_once("./../db.php");
    $form_operations_list = [];
    $form_error = "";
    $form_mode = $form_mode ?? "new";
    try
    {
        switch($form_mode)
        {
            case "view":
                $folder_id = $_GET['id'] ?? null;
                if(!preg_match("/^\d+$/", $folder_id))
                {
                    throw new Exception("Record [id={$folder_id}] is invalid.");
                }
                dbConnect();
                $s = $dbh->prepare("SELECT `id`, `label_text`, `label_image`, `description` FROM `dms_folders` WHERE `id` = :folder_id LIMIT 1");
                $s->bindParam(":folder_id", $folder_id);
                $s->execute();
                $folder_item = $s->fetch(PDO::FETCH_ASSOC);
                if($folder_item === FALSE)
                {
                    throw new Exception("Can't retrieve data for the record [id={$folder_id}].");
                }
                foreach($folder_item as &$value)
                {   
                    $value = htmlspecialchars($value);
                }
                $folder_item['documents'] = [];
                $s = $dbh->prepare("SELECT `id`, `name` FROM `dms_documents` WHERE `folder_id` = :folder_id");
                $s->bindParam(":folder_id", $folder_item['id']);
                $s->execute();
                $fetch = $s->fetchAll(PDO::FETCH_ASSOC);
                if($fetch === FALSE)
                {
                    throw new Exception("Can't retrieve documents associated with the record [id={$folder_id}].");
                }
                dbClose();
                foreach($fetch as $row)
                {
                    $folder_item['documents'][] = $row;
                }
                $form_operations_list[] = "update";
                $form_operations_list[] = "delete";
                $form_operation = "update";
                $page_title = "Folder: {$folder_item['label_text']}";
                break;
            case "new":
            default:
                $form_operations_list[] = "add";
                $form_operation = "add";
                $folder_item = [];
                foreach(['label_text', 'label_image', 'description'] as $field)
                {
                    $folder_item[$field] = "";
                }
                $page_title = "New Folder";
                break;
        }
        $images = [];
        foreach(scandir("./images") as $sd)
        {
            if($sd !== "." && $sd !== "..")
            {
                $images[] = $sd;
            }
        }
        $folder_item_json = json_encode($folder_item);
        $form_operations = [];
        $url_pfx = "{$app->config['thisDirUrl']}";
        $url_sfx = "";
        foreach($form_operations_list as $fo)
        {
            $form_operations[$fo] = "{$url_pfx}/{$fo}{$url_sfx}";
        }
    }
    catch(Exception $e)
    {
        $form_error = $e->getMessage();
        $page_title = "Error";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?= $app->PageMeta() ?>
    <?= $app->PageTitle($page_title) ?>
    <?= $app->PageStyle() ?>
    <?= $app->PageStyle("form-actionbar-css.php") ?>
    <?= $app->PageStyle("form-css.php") ?>
    <?= $app->PageScript() ?>
    <?= $app->PageScript("ckeditor5-build-classic/ckeditor.js", "mainDirUrl") ?>
    <?= $app->PageScript("combobox.js", "mainDirUrl") ?>
    <?= $app->PageScript("form.js") ?>
</head>
<body>
    <?= $app->Header() ?>
    <section class="actionbar">
        <div class="breadcrumb">
            <a class="sitemap" href="/" title="Home"></a>&raquo;
            <a href="<?= $app->config['mainDirUrl'] ?>" title="Document Management System">Document Management System</a>&raquo;
            <a href="<?= $app->config['mainDirUrl'] ?>/folders" title="Folders">Folders</a>&raquo;
            <span class="this-page" title="<?= ucwords($form_mode) ?>"><?= ucwords($form_mode) ?></span>
        </div>
        <?php if(!$form_error): ?>
            <div class="operation-container">
                <label>Operation</label>
                <select name="operation">
                </select>
            </div>
            <a class="button submit" href="">Submit</a>
        <?php endif; ?>
    </section>
    <?php if(!$form_error): ?>
        <section class="folder-item">
            <form id="folder-item-form">
                <input type="hidden" name="json" value="<?= htmlspecialchars($folder_item_json) ?>">
                <input type="hidden" name="form_mode" value="<?= $form_mode ?>">
                <input type="hidden" name="form_operation" value="<?= $form_operation ?>">
                <?php foreach($form_operations as $op => $url): ?>
                    <input type="hidden" name="form_operations[]" data-operation="<?= $op ?>" data-url="<?= $url ?>">
                <?php endforeach; ?>
                <input type="hidden" name="id" value="<?= ($form_mode === "view") ? $folder_item['id'] : null ?>">
            </form>
            <h1>Documents Folder</h1>
            <h2>Label Text</h2>
            <input type="text" name="label_text" value="" placeholder="Enter folder label text">
            <h2>Label Image</h2>
            <div class="combobox label-image">
                <?php foreach($images as $img): ?>
                    <option style="display: none"><?= $img ?></option>
                <?php endforeach; ?>
            </div>
            <div class="preview" style="display: none"></div>
            <h2>Description</h2>
            <textarea name="description" placeholder="Description of the folder"></textarea>
            <?php if($form_mode === "view"): ?>
                <h2>Associated Documents</h2>
                <?php if(count($folder_item['documents'])): ?>
                    <table class="associated-documents">
                        <thead>
                            <th class="document-id">
                                Id
                            </th>
                            <th class="document-name">
                                Document Name
                            </th>
                        </thead>
                        <tbody>
                            <?php foreach($folder_item['documents'] as $document_item): ?>
                                <tr>
                                    <td class="document-id">
                                        <a href="<?= $app->config['mainDirUrl'] ?>/documents/view?id=<?= $document_item['id'] ?>">
                                            <?= $document_item['id'] ?>
                                        </a>
                                    </td>
                                    <td class="document-name">
                                        <?= $document_item['name'] ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="associated-documents">
                        No documents associated with this folder
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </section>
    <?php else: ?>
        <section class="error">
            <h1>Operation Failed</h1>
            <input type="hidden" name="form_error" value="<?= $form_error ?>">
            <div class="text">
                <?= $form_error ?>
            </div>
        </section>
    <?php endif; ?>
    <section class="dummy" style="display: none">
    </section>
    <?= $app->Footer() ?>
</body>
</html>