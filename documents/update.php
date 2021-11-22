<?php
    require_once("./../../common.php");
    require_once("./../../db.php");
    $json = json_decode($_POST['json']);
    print_r($_POST);
    dbConnect();
    $fields = ["id", "date", "city", "state", "headline", "introduction", "body", "tags"];
    $news_item = [];
    foreach($fields as $f)
    {
        $news_item[$f] = $json->{$f} ?? null;
    }
    array_shift($fields);
    $update_str = "";
    foreach($fields as $f)
    {
        $update_str .= "`{$f}` = :{$f},";
    }
    $update_str = rtrim($update_str, ",");
    $s = $dbh->prepare("UPDATE `news` SET {$update_str} WHERE `id` = :id LIMIT 1");
    $s->bindParam(":id", $news_item['id']);
    foreach($fields as $f)
    {
        $s->bindParam(":{$f}", $news_item[$f]);
    }
    $s->execute();
    dbClose();
    echo "Record {$news_item['id']} has been successfully updated";