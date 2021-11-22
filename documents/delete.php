<?php
    require_once("./../../common.php");
    require_once("./../../db.php");
    require_once("./../../debug.php");
    $debug = new Debug(true);
    $debug->output($_POST);
    $opportunityId = $_POST["opportunityId"] ?? null;
    if($opportunityId)
    {
        dbConnect();
        $s = $dbh->prepare("DELETE FROM `career_opportunities` WHERE `id` = :id LIMIT 1");
        $s->bindParam(":id", $opportunityId);
        $s->execute();
        dbClose();
        echo "delete opportunity '{$opportunityId}' has successfully completed.";
    }
    else
    {
        echo "delete operation can't be performed due to missing opportunity id.";
    }