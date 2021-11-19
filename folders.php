<?php
	require_once("./common.php");
	require_once("./db.php");
	$json = [];
	try
	{
		dbConnect();
		$query = "SELECT `id`, `label_text`, `label_image`, `description`, `documents_count` FROM `dms_folders_view_1` ORDER BY `label_text` ASC";
		$s = $dbh->prepare($query);
		$s->execute();
		$fetch = $s->fetchAll(PDO::FETCH_ASSOC);
		dbClose();
		$json['folders'] = [];
		foreach($fetch as $row)
		{
			$folder_item = $row;
			$json['folders'][] = $folder_item;
		}
		session_start();
	    $json['view_list_type'] = $_SESSION[$app->thisPageUrl]['view_list_type'] ?? "table";
	}
	catch(Exception $e)
	{
		$json['error'] = "An error has occured: ".$e->getMessage();
	}
	$json = json_encode((object)$json);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?= $app->PageMeta() ?>
	<?= $app->PageTitle("Folders") ?>
	<?= $app->PageStyle() ?>
	<?= $app->PageStyle("folders-css.php") ?>
	<?= $app->PageScript() ?>
	<?= $app->PageScript("folders.js") ?>
</head>
<body>
	<?= $app->HiddenInputNode(['name' => 'json', 'value' => $json]) ?>
	<?= $app->Header() ?>
	<section class="actionbar">
    	<div class="breadcrumb">
        	<a class="sitemap" href="/" title="Home"></a>&raquo;
            <a href="<?= $app->config['mainDirUrl'] ?>" title="Document Management System">Document Management System</a>&raquo;
            <span class="this-page" title="Folders">Folders</span>
        </div>
        <div class="right">
	    	<div class="separator"></div>
        	<a class="button view list-type-table" title="View as table"></a>
        	<a class="button view list-type-list" title="View as list"></a>
        	<div class="separator"></div>
            <a class="button" href="<?= $app->config['mainDirUrl'] ?>/folders/new" title="New folder">New</a>
        </div>
    </section>
	<section class="folders">
		<h1>Folders</h1>
		<div class="list-container">
			<?php if(!empty($records_count)): ?>
				<table>
					<thead>
						<tr>
							<th class="id">
								Id
							</th>
							<th class="label-text">
								Label Text
							</th>
							<th class="label-image">
								Label Image
							</th>
							<th class="description">
								Description
							</th>
							<th class="documents-count">
								Documents
							</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($folders as $folder_item): ?>
							<tr>
								<td class="id">
									<a href="<?= $app->config['thisDirUrl'] ?>/folders/view?id=<?= $folder_item['id'] ?>" title="view / update"><?= $folder_item['id'] ?></a>
								</td>
								<td class="label-text">
									<?= $folder_item['label_text'] ?>
								</td>
								<td class="label-image">
									<?= $folder_item['label_image'] ?>
								</td>
								<td class="description">
									<?= $folder_item['description'] ?>
								</td>
								<td class="documents-count">
									<?= $folder_item['documents_count'] ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>
		</div>
	</section>
	<section class="dummy" style="display: none;">
	</section>
	<?= $app->Footer() ?>
</body>
</html>