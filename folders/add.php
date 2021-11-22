<?php
    require_once("./../common.php");
    require_once("./../db.php");
    $json = json_decode($_POST['json']);
    // var_dump($_POST);
    dbConnect();
    $folder_item = [];
    foreach(['label_text', 'label_image', 'description'] as $field)
    {
        $folder_item[$field] = $json->{$field} ?? null;
    }
    $json = [];
    $json['return_code'] = null;
    $json['messages'] = [];
    try
    {
        $dbh->beginTransaction();
        if(!$folder_item['label_text'])
        {
            throw new Exception("Label text can't be empty string.");
        }
        $s = $dbh->prepare("SELECT `id` FROM `dms_folders` WHERE `label_text` = :label_text LIMIT 1");
        $s->bindParam(":label_text", $folder_item['label_text']);
        $s->execute();
        $fetch = $s->fetchColumn();
        if($fetch !== FALSE)
        {
            throw new Exception("Folder with label text '{$folder_item['label_text']}' already exists [id={$fetch}].");
        }
        $s = $dbh->prepare("INSERT INTO `dms_folders`(`label_text`, `label_image`, `description`) VALUES(:label_text, :label_image, :description)");
        $s->bindParam(":label_text", $folder_item['label_text']);
        $s->bindParam(":label_image", $folder_item['label_image']);
        $s->bindParam(":description", $folder_item['description']);
        $s->execute();
        $s = $dbh->prepare("SELECT LAST_INSERT_ID()");
        $s->execute();
        $folder_id = $s->fetchColumn();
        if($folder_id === FALSE)
        {
            throw new Exception("Can't fetch identifier of the newly inserted folder record.");
        }
        $dbh->commit();
        $json['messages'][] = "Successfully added folder record '{$folder_item['label_text']}'[id={$folder_id}].";
        $json['return_code'] = "ok";
    }
    catch(Exception $e)
    {
        $dbh->rollBack();
        $json['messages'][] = "Add folder operation has been cancelled: " . $e->getMessage();
        $json['return_code'] = "notok";
    }
    dbClose();
    echo json_encode((object)$json);