<?php
    require_once("./../common.php");
    require_once("./../db.php");
    $json = json_decode($_POST['json']);
    // print_r($_POST);
    $return_code = null;
    $messages = [];
    $tag_item = [];
    foreach(['id', 'text'] as $field)
    {
        $tag_item[$field] = $json->{$field} ?? null;
    }
    try
    {
        if(!preg_match("/^[0-9]+$/", $tag_item['id']))
        {
            throw new Exception("Record identifier [{$tag_item['id']}] is invalid.");
        }
        if($tag_item['text'] === "")
        {
            throw new Exception("Tag text can't be empty string.");
        }
        dbConnect();
        $s = $dbh->prepare("UPDATE `dms_tags` SET `text` = :tag_text WHERE `id` = :tag_id LIMIT 1");
        $s->bindParam(":tag_text", $tag_item['text']);
        $s->bindParam(":tag_id", $tag_item['id']);
        $s->execute();
        dbClose();
        $messages[] = "Record [{$tag_item['id']}] has been successfully updated.";
        $return_code = "ok";
    }
    catch(Exception $e)
    {
        $messages[] = "Update operation failed: ".$e->getMessage();
        $return_code = "notok";
    }
    $json = [];
    $json['return_code'] = $return_code;
    $json['messages'] = $messages;
    echo json_encode((object)$json);
