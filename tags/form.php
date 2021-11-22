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
                $tag_id = $_GET['id'] ?? null;
                if(!preg_match("/^\d+$/", $tag_id))
                {
                    throw new Exception("Record [id={$tag_id}] is invalid.");
                }
                dbConnect();
                $s = $dbh->prepare("SELECT `id`, `text` FROM `dms_tags` WHERE `id` = :tag_id LIMIT 1");
                $s->bindParam(":tag_id", $tag_id);
                $s->execute();
                $tag_item = $s->fetch(PDO::FETCH_ASSOC);
                if($tag_item === FALSE)
                {
                    throw new Exception("Can't retrieve data for the record [id={$tag_id}].");
                }
                foreach($tag_item as &$value)
                {   
                    $value = htmlspecialchars($value);
                }
                $tag_item['documents'] = [];
                $s = $dbh->prepare("SELECT `document_id`, `document_name` FROM `dms_tags_view_2` WHERE `tag_id` = :tag_id");
                $s->bindParam(":tag_id", $tag_item['id']);
                $s->execute();
                $fetch = $s->fetchAll(PDO::FETCH_ASSOC);
                if($fetch === FALSE)
                {
                    throw new Exception("Can't retrieve documents associated with the record [id={$tag_id}].");
                }
                foreach($fetch as $row)
                {
                    $document_item = [];
                    $document_item['id'] = $row['document_id'];
                    $document_item['name'] = $row['document_name'];
                    $tag_item['documents'][] = $document_item;
                }
                dbClose();
                $form_operations_list[] = "update";
                $form_operations_list[] = "delete";
                $form_operation = "update";
                $page_title = "Tag: {$tag_item['text']}";
                break;
            case "new":
            default:
                $form_operations_list[] = "add";
                $form_operation = "add";
                $tag_item = [];
                $tag_item['text'] = "";
                $page_title = "New Tag";
                break;
        }
        $tag_item_json = json_encode($tag_item);
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
    <?= $app->PageScript("form.js") ?>
</head>
<body>
    <?= $app->Header() ?>
    <section class="actionbar">
        <div class="breadcrumb">
            <a class="sitemap" href="/" title="Home"></a>&raquo;
            <a href="<?= $app->config['mainDirUrl'] ?>" title="Document Management System">Document Management System</a>&raquo;
            <a href="<?= $app->config['mainDirUrl'] ?>/tags" title="Tags">Tags</a>&raquo;
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
        <section class="tag-item">
            <form id="tag-item-form">
                <input type="hidden" name="json" value="<?= htmlspecialchars($tag_item_json) ?>">
                <input type="hidden" name="form_mode" value="<?= $form_mode ?>">
                <input type="hidden" name="form_operation" value="<?= $form_operation ?>">
                <?php foreach($form_operations as $op => $url): ?>
                    <input type="hidden" name="form_operations[]" data-operation="<?= $op ?>" data-url="<?= $url ?>">
                <?php endforeach; ?>
            </form>
            <h2>Documents Tag</h2>
            <table class="tag-data">
                <tbody>
                    <tr class="text">
                        <td class="label">
                            Tag Text
                        </td>
                        <td class="value">
                            <input type="text" name="text" placeholder="Enter tag text" value="">
                        </td>
                    </tr>
                </tbody>
            </table>
            <?php if($form_mode === "view"): ?>
                <h2>Associated Documents</h2>
                <?php if(count($tag_item['documents'])): ?>
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
                            <?php foreach($tag_item['documents'] as $document_item): ?>
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
                        There are no documents associated with this tag.
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </section>
    <?php else: ?>
        <section class="error">
            <h1>Operation Failed</h1>
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