<?php
	require_once("./common.php");
    require_once("./db.php");
    dbConnect();
    $query = "SELECT `scan_id`, `scan_filename`, `document_name` FROM `dms_scans_view_1`";
    $s = $dbh->prepare($query);
    $s->execute();
    $fetch = $s->fetchAll(PDO::FETCH_ASSOC);
    dbClose();
    $scans = [];
    foreach($fetch as $row)
    {
    	$scan_item = $row;
        $scan_item['scan_file_exists'] = file_exists("{$app->config['mainDirPath']}/scans/files/{$scan_item['scan_filename']}");
    	$scans[] = $scan_item;
    }
	$scans_json = json_encode($scans);
	session_start();
    $view_list_type = $_SESSION[$app->thisPageUrl]['view_list_type'] ?? "table";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?= $app->PageMeta() ?>
	<?= $app->PageTitle("Scans") ?>
	<?= $app->PageStyle() ?>
	<?= $app->PageStyle("scans-css.php") ?>
	<?= $app->PageScript() ?>
	<?= $app->PageScript("scans.js") ?>
</head>
<body>
	<?= $app->Header() ?>
	<section class="actionbar">
    	<div class="breadcrumb">
        	<a class="sitemap" href="/" title="Home"></a>&raquo;
            <a href="<?= $app->config['mainDirUrl'] ?>" title="Document Management System">Document Management System</a>&raquo;
            <span class="this-page" title="Scans">Scans</span>
        </div>
        <div class="right">
	    	<div class="separator"></div>
        	<a class="button view list-type-table" title="View as table"></a>
        	<a class="button view list-type-list" title="View as list"></a>
        	<div class="separator"></div>
            <a class="button reload-scans" title="Reload scan files data into database">Reload</a>
        </div>
    </section>
	<section class="scans">
		<input type="hidden" name="json" value="<?= htmlspecialchars($scans_json) ?>">
		<input type="hidden" name="view_list_type" value="<?= $view_list_type ?>">
		<h1>Scans</h1>
		<div class="list-container"></div>
		<div class="records-count"></div>
	</section>
	<section class="dummy" style="display: none;">
	</section>
	<?= $app->Footer() ?>
</body>
</html>