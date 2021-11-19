<?php
	require_once("./common.php");
	require_once("./db.php");
	dbConnect();
    $folder_id = 4;
    $s = $dbh->prepare("SELECT `id`, `label_text` FROM `dms_folders` WHERE `id` = :folder_id LIMIT 1");
    $s->bindParam(":folder_id", $folder_id);
    $s->execute();
    $fetch = $s->fetch(PDO::FETCH_ASSOC);
	dbClose();
    var_dump($fetch);