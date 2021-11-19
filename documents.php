<?php
	require_once("./common.php");
    require_once("./db.php");
    dbConnect();
    $query = "SELECT `id`, `name`, `tags_count`, `folder_label_text`, `folder_label_image`, `scans_count`, `description` FROM `dms_documents_view_1`";
    $s = $dbh->prepare($query);
    $s->execute();
    $fetch = $s->fetchAll(PDO::FETCH_ASSOC);
    dbClose();
    $documents = [];
    foreach($fetch as $row)
    {
    	$doc = $row;
    	if(!$doc['folder_label_text'])
    	{
    		$doc['folder_label_text'] = "-";
    	}
    	$documents[] = $doc;
    }
    $records_count = count($documents);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?= $app->PageMeta() ?>
	<?= $app->PageTitle("Documents") ?>
	<?= $app->PageStyle() ?>
	<?= $app->PageStyle("documents-css.php") ?>
	<?= $app->PageScript() ?>
	<?= $app->PageScript("documents.js") ?>
</head>
<body>
	<?= $app->Header() ?>
	<section class="actionbar">
    	<div class="breadcrumb">
        	<a class="sitemap" href="/"></a>&raquo;
            <a href="<?= $app->config['mainDirUrl'] ?>">Document Management System</a>&raquo;
            <span class="this-page">Documents</span>
        </div>
        <div class="right">
            <a href="/dms/documents/new" title="Create new document item">New</a>
        </div>
    </section>
	<section class="documents">
		<h1>Documents</h1>
		<div class="table-container">
			<?php if($records_count): ?>
				<table>
					<thead>
						<tr>
							<th class="id right">
								Id
							</th>
							<th class="name">
								Name
							</th>
							<th class="folder">
								Folder
							</th>
							<th class="description">
								Description
							</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($documents as $doc): ?>
							<tr>
								<td class="id right">
									<a href="<?= $app->config['thisDirUrl'] ?>/documents/view?id=<?= $doc['id'] ?>" title="view / update"><?= $doc['id'] ?></a>
								</td>
								<td class="name">
									<?= $doc['name'] ?>
								</td>
								<td class="folder">
									<?= $doc['folder_label_text'] ?>
								</td>
								<td class="description">
									<?= $doc['description'] ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>
		</div>
		<div class="records-count">
			Found <?= $records_count ?> record(s).
		</div>
	</section>
	<?= $app->Footer() ?>
</body>
</html>