<?php
    require_once("./../common.php");
    require_once("./../db.php");
    $form_operations_list = [];
    $form_error = "";
    $form_mode = $form_mode ?? "new";

    switch($form_mode)
    {
        case "view":
            if(isset($_GET['id']))
            {
                $document_id = $_GET['id'];
                dbConnect();
                $s = $dbh->prepare("SELECT `id`, `name`, `folder_id`, `description` FROM `dms_documents` WHERE `id` = :document_id LIMIT 1");
                $s->bindParam(":document_id", $document_id);
                $s->execute();
                $document_item = $s->fetch(PDO::FETCH_ASSOC);
                foreach($document_item as &$value)
                {
                    $value = htmlspecialchars($value);
                }
                dbClose();
                if($document_item)
                {
                    dbConnect();
                    if($document_item['folder_id'])
                    {
                        $s = $dbh->prepare("SELECT `id`, `label_text` FROM `dms_folders` WHERE `id` = :folder_id LIMIT 1");
                        $s->bindParam(":folder_id", $document_item['folder_id']);
                        $s->execute();
                        $fetch = $s->fetch(PDO::FETCH_ASSOC);
                        if($fetch)
                        {
                            $document_item['folder_label_text'] = $fetch['label_text'];
                        }
                        else 
                        {
                            throw new Exception("Can't fetch data for the folder associated with the record.");
                        }
                    }
                    $document_item['tags'] = [];
                    $s = $dbh->prepare("SELECT `tag_id`, `tag_text` FROM `dms_documents_tags_view_2` WHERE `document_id` = :document_id");
                    $s->bindParam(":document_id", $document_id);
                    $s->execute();
                    $fetch = $s->fetchAll(PDO::FETCH_ASSOC);
                    foreach($fetch as $row)
                    {
                        $tag_item = [];
                        $tag_item['id'] = $row['tag_id'];
                        $tag_item['text'] = $row['tag_text'];
                        $document_item['tags'][] = $tag_item;
                    }
                    $document_item['scans'] = [];
                    $s = $dbh->prepare("SELECT `scan_id`, `scan_order`, `scan_filename` FROM `dms_documents_scans_view_2` WHERE `document_id` = :document_id");
                    $s->bindParam(":document_id", $document_id);
                    $s->execute();
                    $fetch = $s->fetchAll(PDO::FETCH_ASSOC);
                    foreach($fetch as $row)
                    {
                        $scan_item = [];
                        $scan_item['id'] = $row['scan_id'];
                        $scan_item['order'] = $row['scan_order'];
                        $scan_item['filename'] = $row['scan_filename'];
                        $document_item['scans'][] = $scan_item;
                    }
                    dbClose();
                    $form_operations_list[] = "update";
                    $form_operations_list[] = "delete";
                    $form_operation = "update";
                }
                else
                {
                    $form_error = "record-not-found";
                }
            }
            else
            {
                $document_id = null;
                $form_error = "missing-record-id";
            }
            break;
        case "new":
        default:
            $form_operations_list[] = "add";
            $form_operation = "add";
            $document_item = [];
            $document_item['name'] = "";
            $document_item['description'] = "";
            $document_item['folder_id'] = null;
            $document_item['tags'] = [];
            $document_item['scans'] = [];
            break;
    }
    if(!$form_error)
    {
        $document_item_json = json_encode($document_item);
        dbConnect();
        $s = $dbh->prepare("SELECT `id`, `text` FROM `dms_tags` ORDER BY `text` ASC");
        $s->execute();
        $tags_list = $s->fetchAll(PDO::FETCH_ASSOC);
        $s = $dbh->prepare("SELECT `id`, `label_text` FROM `dms_folders` ORDER BY `label_text` ASC");
        $s->execute();
        $folders_list = $s->fetchAll(PDO::FETCH_ASSOC);
        $s = $dbh->prepare("SELECT `id`, `filename` FROM `dms_documents_scans` WHERE `document_id` IS NULL");
        $s->execute();
        $scans_list = $s->fetchAll(PDO::FETCH_ASSOC);
        dbClose();
    }
    $form_operations = [];
    $url_pfx = "{$app->config['mainDirUrl']}/documents";
    $url_sfx = "";
    foreach($form_operations_list as $fo)
    {
        $form_operations[$fo] = "{$url_pfx}/{$fo}{$url_sfx}";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?= $app->PageMeta() ?>
    <?= $app->PageTitle(($form_mode === "view") ? $document_item['name'] : "New") ?>
    <?= $app->PageStyle() ?>
    <?= $app->PageStyle("form-actionbar-css.php") ?>
    <?= $app->PageStyle("form-css.php") ?>
    <?= $app->PageStyle("combobox-css.php", "mainDirUrl") ?>
    <?= $app->PageStyle("form-tags-collection-container-css.php") ?>
    <?= $app->PageStyle("form-scans-collection-container-css.php") ?>
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
            <a href="<?= $app->config['mainDirUrl'] ?>/documents" title="Documents">Documents</a>&raquo;
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
        <section class="document-item">
            <form id="document-item-form">
                <input type="hidden" name="json" value="<?= htmlspecialchars($document_item_json) ?>">
            </form>
            <input type="hidden" name="form_mode" value="<?= $form_mode ?>">
            <input type="hidden" name="form_operation" value="<?= $form_operation ?>">
            <?php foreach($form_operations as $op => $url): ?>
                <input type="hidden" name="form_operations[]" data-operation="<?= $op ?>" data-url="<?= $url ?>">
            <?php endforeach; ?>
            <input type="text" name="document_name" placeholder="Document name" value="">
            <table class="general-info">
                <tbody>
                    <?php if($form_mode === "view"): ?>
                        <tr class="id">
                            <td class="label">
                                Id
                            </td>
                            <td class="value">
                                <input type="text" name="id" readonly value="">
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <h2>Description</h2>
            <textarea name="description" placeholder="Description"></textarea>
            <h2>Tags</h2>
            <div class="combobox tags">
                <input type="text" placeholder="Tags, e.g. invoice, employment">
                <?php foreach($tags_list as $tag_item): ?>
                    <option value="<?= $tag_item['id'] ?>"><?= $tag_item['text'] ?></option>
                <?php endforeach; ?>
            </div>
            <div class="tags-collection-container">
            </div>
            <h2>Folder</h2>
            <div>
                <div class="combobox folder">
                    <input type="text" placeholder="folder name">
                    <?php foreach($folders_list as $folder_item): ?>
                        <option value="<?= $folder_item['id'] ?>"><?= $folder_item['label_text'] ?></option>
                    <?php endforeach; ?>
                </div>
            </div>
            <h2>Scans</h2>
            <div class="scans-collection-container">
            </div>
            <div>
                <a id="add-scan" href="">Add</a>
            </div>
        </section>
    <?php else: ?>
        <section class="error">
            <h1>Operation Failed</h1>
            <input type="hidden" name="form_error" value="<?= $form_error ?>">
            <div class="text">
                <?php if($form_error === "missing-record-id"): ?>
                    Record id needs to be supplied in order to display record.
                <?php elseif($form_error === "record-not-found"): ?>
                    Can't display record with gived id: <?= $opportunityId ?>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>
    <section class="dummy" style="display: none">
        <div class="scan-container">
            <div class="control-bar">
                <div class="combobox scan">
                    <input type="text" placeholder="type or choose filename">
                    <?php foreach($scans_list as $scan_item): ?>
                        <option value="<?= $scan_item['id'] ?>"><?= $scan_item['filename'] ?></option>
                    <?php endforeach; ?>
                </div>
                <div class="controls">
                    <div class="remove"></div>
                    <!-- <div class="reorder"></div> -->
                </div>
            </div>
        </div>
        <div class="tag">
            <span class="text"></span>
            <div class="button remove"></div>
        </div>
    </section>
    <?= $app->Footer() ?>
</body>
</html>