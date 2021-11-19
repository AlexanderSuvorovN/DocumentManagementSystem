<?php
	require_once("./common.php");
    require_once("./db.php");
    $json = [];
    $json['data'] = null;
    $json['return_code'] = null;
    $json['messages'] = [];
	try
	{
		$scandir = scandir("{$app->config['mainDirPath']}/scans/files");
		$fs_scans = [];
		foreach($scandir as $sd)
		{
			if($sd !== "." && $sd !== "..")
			{
				$fs_scans[] = $sd;
			}
		}
	    $scans = [];
		dbConnect();
		foreach($fs_scans as $fs_scan_filename)
		{
			$scan_item = [];
			$s = $dbh->prepare("SELECT `id`, `filename`, `document_id` FROM `dms_documents_scans` WHERE `filename` = :fs_scan_filename LIMIT 1");
			$s->bindParam(":fs_scan_filename", $fs_scan_filename);
			$s->execute();
			$fetch = $s->fetch(PDO::FETCH_ASSOC);
			if($fetch)
			{
				$scan_item['scan_id'] = $fetch['id'];
				$scan_item['scan_filename'] = $fetch['filename'];
				$json['messages'][] = "Scan with filename '{$scan_item['scan_filename']}' exists in the database [{$scan_item['scan_id']}].";
				$document_id = $fetch['document_id'];
				if($document_id)
				{
					$s = $dbh->prepare("SELECT `name` FROM `dms_documents` WHERE `id` = :document_id LIMIT 1");
					$s->bindParam(":document_id", $document_id);
					$s->execute();
					$fetch = $s->fetchColumn();
					if($fetch === FALSE)
					{
						throw new Exception("Can't retrieve document name [{$document_id}] for scan item {$scan_item['scan_filename']}.");
					}
					$json['messages'][] = "Successfully retrieved information for document [{$document_id}] associated with scan '{$scan_item['scan_filename']}'.";
					$scan_item['document_name'] = $fetch;
				}
			}
			else
			{
				$dbh->beginTransaction();
				$s = $dbh->prepare("INSERT INTO `dms_documents_scans`(`filename`, `order`, `document_id`) VALUES(:fs_scan_filename, NULL, NULL)");
				$s->bindParam(":fs_scan_filename", $fs_scan_filename);
				$s->execute();
				$s = $dbh->prepare("SELECT LAST_INSERT_ID()");
				$s->execute();
				$fetch = $s->fetchColumn();
				if($fetch === FALSE)
				{
					throw new Exception("Can't retrieve identifier for newly created record '{$fs_scan_filename}'.");
				}
				$dbh->commit();
				$scan_item['scan_id'] = $fetch;
				$scan_item['scan_filename'] = $fs_scan_filename;
				$scan_item['document_name'] = null;
				$json['messages'][] = "Successfully created new scan item record for '{$scan_item['scan_filename']}' in the database [{$scan_item['scan_id']}].";
			}
			$scan_item['scan_file_exists'] = true;
			$scans[] = $scan_item;
		}
	    dbClose();
	    $json['messages'][] = "Reload operation has successfully completed.";
	    $json['data'] = $scans;
	    $json['return_code'] = "ok";
	}
	catch(Exception $e)
	{
		$dbh->rollBack();
		$json['messages'][] = "Reload operation failed: ".$e->getMessage();
		$json['return_code'] = "notok";
	}
    echo json_encode((object)$json);