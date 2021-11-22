<?php
    require_once("./../common.php");
    require_once("./../db.php");
    $json = json_decode($_POST['json']);
    $folder_item = [];
    foreach(['id', 'label_text', 'label_image', 'description'] as $field)
    {
        $folder_item[$field] = $json->{$field} ?? null;
    }
    $json = [];
    $json['return_code'] = null;
    $json['messages'] = [];
    $json['messages'][] = "_POST: {$_POST['json']}";
    try
    {
        if(!preg_match("/^\d+$/", $folder_item['id']))
        {
            throw new Exception("Record identifier [{$folder_item['id']}] is invalid.");
        }
        if($folder_item['label_text'] === "")
        {
            throw new Exception("Label text can't be empty string.");
        }
        dbConnect();
        $s = $dbh->prepare("UPDATE `dms_folders` SET `label_text` = :label_text, `label_image` = :label_image, `description` = :description WHERE `id` = :folder_id LIMIT 1");
        $s->bindParam(":label_text", $folder_item['label_text']);
        $s->bindParam(":label_image", $folder_item['label_image']);
        $s->bindParam(":description", $folder_item['description']);
        $s->bindParam(":folder_id", $folder_item['id']);
        $s->execute();
        dbClose();
        $json['messages'][] = "Record [{$folder_item['id']}] has been successfully updated.";
        $json['return_code'] = "ok";
    }
    catch(Exception $e)
    {
        $json['messages'][] = "Update operation failed: ".$e->getMessage();
        $json['return_code'] = "notok";
    }
    echo json_encode((object)$json);
