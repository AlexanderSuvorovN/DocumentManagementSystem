<?php
    require_once("./../common.php");
    require_once("./../db.php");
    $json = json_decode($_POST['json']);
    // print_r($_POST);
    $folder_item = [];
    foreach(['id'] as $field)
    {
        $folder_item[$field] = $json->{$field} ?? null;
    }
    $json = [];
    $json['return_code'] = null;
    $json['messages'] = [];
    try
    {
        if(!preg_match("/^\d+$/", $folder_item['id']))
        {
            throw new Exception("Record identifier [{$folder_item['id']}] is invalid.");
        }
        dbConnect();
        $dbh->beginTransaction();
        $s = $dbh->prepare("DELETE FROM `dms_folders` WHERE `id` = :folder_id LIMIT 1");
        $s->bindParam(":folder_id", $folder_item['id']);
        $s->execute();
        $s = $dbh->prepare("UPDATE `dms_documents` SET `folder_id` = NULL WHERE `folder_id` = :folder_id");
        $s->bindParam(":folder_id", $folder_item['id']);
        $s->execute();
        $dbh->commit();
        dbClose();
        $json['messages'][] = "Record [{$folder_item['id']}] has been successfully deleted.";
        $json['return_code'] = "ok";
    }
    catch(Exception $e)
    {
        $dbh->rollBack();
        $json['messages'][] = "Delete operation failed: ".$e->getMessage();
        $json['return_code'] = "notok";
    }
    echo json_encode((object)$json);