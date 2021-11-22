<?php
	require_once("./../../common.php");
	require_once("./../../db.php");
	const DEBUG = false;
	dbConnect();
	$page = $_GET["page"] ?? 1;
	$size = $_GET["size"] ?? 25;
	$sorters = $_GET["sorters"] ?? null;
	$filters = $_GET["filters"] ?? null;
	if(DEBUG)
	{
		ob_start();
		print_r($_GET);
		$get = ob_get_clean();
	}
	$order = "";
	if($sorters)
	{
		foreach($sorters as $sort)
		{
			$sort["field"] = "`{$sort["field"]}`";
			$sort["dir"] = strtoupper($sort["dir"]);
			$order .= (($order !== "") ? "," : "") . "{$sort["field"]} {$sort["dir"]}";
		}
		$order = "ORDER BY {$order}";
	}
	$where = "";
	if($filters)
	{
		foreach($filters as $f)
		{
			$field = "`{$f["field"]}`";
			$type = strtolower($f["type"]);
			$value = $dbh->quote($f["value"]);
			switch($type)
			{
				case "!=":
					$cond = "{$field} <> {$value}";
					break;
				case "like":
					$cond = "{$field} LIKE CONCAT('%',{$value},'%')";
					break;
				default:
					$cond = "{$field} {$type} {$value}";
					break;
			}
			$where = (($where !== "") ? "," : "") . $cond;
		}
		$where = "WHERE {$where}";
	}
	$s = $dbh->prepare("SELECT COUNT(*) as `count` FROM `career_opportunities` {$where} {$order}");
	$s->execute();
	$count = $s->fetchColumn();
	$offset = ($page - 1) * $size;
	$s = $dbh->prepare("SELECT `id`, `role`, `seniority`, `location` FROM `career_opportunities` {$where} {$order} LIMIT {$offset},{$size}");
	$s->execute();
	$fetch = $s->fetchAll();
	dbClose();
	$careerOpportunities = ($fetch) ? $fetch : array();
	$lastPage = ceil($count / $size);
	if(DEBUG)
	{
		array_unshift($careerOpportunities,
			array(
				"id" => "id",
				"role" => "count:{$count}",
				"seniority" => "get:{$get}",
				"location" => "order:{$order}"
			));
	}
	$response = array();
	$response["last_page"] = $lastPage;
	$response["data"] = $careerOpportunities;
	echo json_encode($response);