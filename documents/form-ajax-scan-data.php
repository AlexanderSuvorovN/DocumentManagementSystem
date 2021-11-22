<?php
	require_once("./../common.php");
	$scan_filename = (isset($_GET['scan'])) ? trim($_GET['scan']) : "";
	$json = [];
	$json['scan_filename'] = $scan_filename;
	$json['return_code'] = null;
	try
	{
		if(trim($scan_filename) === "")
		{
			throw new Exception("scan filename is empty");
		}
		$scan_filename_full = "{$app->config['mainDirPath']}/scans/{$scan_filename}";
		if(file_exists($scan_filename_full))
		{			
			$ext = pathinfo($scan_filename_full, PATHINFO_EXTENSION);
			if($ext === "jpg" || $ext === "jpeg")
			{
				$json['data'] = getimagesize($scan_filename_full);				
			}
			else
			{
				$json['data'] = null;
			}
			$json['return_code'] = "ok";
		}
		else
		{
			throw new Exception("scan filename does not exist");
		}
	}
	catch(Exception $e)
	{
		$json['return_code'] = "notok";
		$json['message'] = $e->getMessage();
	}
	echo json_encode((object)$json);