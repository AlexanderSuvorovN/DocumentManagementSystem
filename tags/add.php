<?php
    require_once("./../common.php");
    require_once("./../db.php");
    $json = json_decode($_POST['json']);
    // var_dump($_POST);
    dbConnect();
    $fields = ['id', 'text'];
    $tag_item = [];
    foreach($fields as $f)
    {
        $tag_item[$f] = $json->{$f} ?? null;
    }
    $return_code = null;
    $messages = [];
    try
    {
        $dbh->beginTransaction();
        if(!$tag_item['text'])
        {
            throw new Exception("Tag text can't be empty string.");
        }
        $s = $dbh->prepare("SELECT `id` FROM `dms_tags` WHERE `text` = :tag_text LIMIT 1");
        $s->bindParam(":tag_text", $tag_item['text']);
        $s->execute();
        $fetch = $s->fetchColumn();
        if($fetch !== FALSE)
        {
            throw new Exception("Tag '{$tag_item['text']}' already exists [id={$fetch}].");
        }
        $s = $dbh->prepare("INSERT INTO `dms_tags`(`text`) VALUES(:tag_text)");
        $s->bindParam(":tag_text", $tag_item['text']);
        $s->execute();
        $s = $dbh->prepare("SELECT LAST_INSERT_ID()");
        $s->execute();
        $tag_id = $s->fetchColumn();
        if($tag_id === FALSE)
        {
            throw new Exception("Can't fetch indentifier of the newly inserted document record.");
        }
        $dbh->commit();
        $messages[] = "Successfully added tag record '{$tag_item['text']}'[id={$tag_id}].";
        $return_code = "ok";
    }
    catch(Exception $e)
    {
        $dbh->rollBack();
        $messages[] = "Add tag operation has been cancelled: " . $e->getMessage();
        $return_code = "notok";
    }
    dbClose();
    $json = [];
    $json['return_code'] = $return_code;
    $json['messages'] = $messages;
    echo json_encode((object)$json);