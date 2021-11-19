<?php
	require_once("./common.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?= $app->PageMeta() ?>
	<?= $app->PageTitle("Document Management System") ?>
	<?= $app->PageStyle() ?>
	<?= $app->PageStyle("index-css.php") ?>
	<?= $app->PageScript() ?>
</head>
<body>
	<?= $app->Header() ?>
	<section class="actionbar">
    	<div class="breadcrumb">
        	<a class="sitemap" href="/"></a>&raquo;
            <span class="this-page">Document Management System</span>
        </div>

    </section>
	<section class="index">
		<ul>
			<li>
				<a class="dir" href="/dms/documents">Documents</a>
			</li>
			<li>
				<a class="dir" href="/dms/folders">Folders</a>
			</li>
			<li>
				<a class="dir" href="/dms/tags">Tags</a>
			</li>
			<li>
				<a class="dir" href="/dms/scans">Scans</a>
			</li>
		</ul>
	</section>
	<?= $app->Footer() ?>
</body>
</html>
