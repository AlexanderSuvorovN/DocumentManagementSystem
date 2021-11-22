<?php
	$search = $_GET['search'] ?? "";
	$scans_dir = "scans";
	//$folder_dir = $_GET[];
	$scandir = scandir("./../".$scans_dir);
	// print_r($categories);
	$scans = [];
	foreach($scandir as $sd)
	{
		if($sd === "." || $sd === ".." || preg_match("/{$search}/i", $sd) === 0)
		{
			continue;
		}
		$scans[] = $sd;
	}
	echo json_encode($scans);