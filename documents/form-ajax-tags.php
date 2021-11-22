<?php
	require_once("./../db.php");
	$search = $_GET['search'] ?? "";
	$search = preg_replace("/(_|%)/", "\\\\$1", $search);
	dbConnect();
	$query = "SELECT DISTINCT `name` FROM `dms_tags` WHERE `name` LIKE CONCAT('%', :search, '%') ORDER BY `name` ASC";
	$s = $dbh->prepare($query);
	$s->bindParam(":search", $search);
	$s->execute();
	$fetch = $s->fetchAll(PDO::FETCH_ASSOC);
	$items = [];
	foreach($fetch as $row)
	{
		$items[] = $row['name'];
	}
	dbClose();
	echo json_encode($items);