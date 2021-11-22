<?php
    require_once("./../common.php");
    require_once("./../db.php");
    $json = json_decode($_POST['json']);
    // print_r($_POST);
    $tag_item = [];
    foreach(['id'] as $field)
    {
        $tag_item[$field] = $json->{$field} ?? null;
    }
    $json = [];
    $json['return_code'] = null;
    $json['messages'] = [];
    try
    {
        if(!preg_match("/^\d+$/", $tag_item['id']))
        {
            throw new Exception("Record identifier [{$tag_item['id']}] is invalid.");
        }
        dbConnect();
        $dbh->beginTransaction();
        $s = $dbh->prepare("DELETE FROM `dms_tags` WHERE `id` = :tag_id LIMIT 1");
        $s->bindParam(":tag_id", $tag_item['id']);
        $s->execute();
        $s = $dbh->prepare("DELETE FROM `dms_documents_to_tags` WHERE `tag_id` = :tag_id");
        $s->bindParam(":tag_id", $tag_item['id']);
        $s->execute();
        $dbh->commit();
        dbClose();
        $json['messages'][] = "Record [{$tag_item['id']}] has been successfully deleted.";
        $json['return_code'] = "ok";
    }
    catch(Exception $e)
    {
        $dbh->rollBack();
        $json['messages'][] = "Delete operation failed: ".$e->getMessage();
        $json['return_code'] = "notok";
    }
    echo json_encode((object)$json);