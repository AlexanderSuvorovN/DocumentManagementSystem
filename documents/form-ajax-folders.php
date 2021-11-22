<?php
	require_once("./../db.php");
	$search = $_GET['search'] ?? "";
	$check = $_GET['check'] ?? false;
	$check_status = null;
	$json = null;
	dbConnect();
	if(!$check)
	{
		$search = preg_replace("/(_|%)/", "\\\\$1", $search);
		$query = "SELECT `label_text` FROM `dms_folders` WHERE `label_text` LIKE CONCAT('%', :search, '%') ORDER BY `label_text` ASC";
		$s = $dbh->prepare($query);
		$s->bindParam(":search", $search);
		$s->execute();
		$fetch = $s->fetchAll(PDO::FETCH_ASSOC);
		$items = [];
		foreach($fetch as $row)
		{
			$items[] = $row['label_text'];
		}
		$json = json_encode($items);
	}
	else
	{
		$query = "SELECT COUNT(*) FROM `dms_folders` WHERE `label_text` = :search LIMIT 1";
		$s = $dbh->prepare($query);
		$s->bindParam(":search", $search);
		$s->execute();
		$check_status = ($s->fetchColumn() > 0);
		$json = json_encode($check_status);
	}
	dbClose();
	echo $json;