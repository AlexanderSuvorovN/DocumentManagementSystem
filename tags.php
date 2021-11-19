<?php
	require_once("./common.php");
    require_once("./db.php");
    dbConnect();
    $query = "SELECT `id`, `text`, `documents_count` FROM `dms_tags_view_1` ORDER BY `text` ASC";
    $s = $dbh->prepare($query);
    $s->execute();
    $fetch = $s->fetchAll(PDO::FETCH_ASSOC);
    dbClose();
    $tags = [];
    foreach($fetch as $row)
    {
    	$tag_item = $row;
    	$tags[] = $tag_item;
    }
    $records_count = count($tags);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?= $app->PageMeta() ?>
	<?= $app->PageTitle("Tags") ?>
	<?= $app->PageStyle() ?>
	<?= $app->PageStyle("tags-css.php") ?>
	<?= $app->PageScript() ?>
	<?= $app->PageScript("tags.js") ?>
</head>
<body>
	<?= $app->Header() ?>
	<section class="actionbar">
    	<div class="breadcrumb">
        	<a class="sitemap" href="/" title="Home"></a>&raquo;
            <a href="<?= $app->config['mainDirUrl'] ?>" title="Document Management System">Document Management System</a>&raquo;
            <span class="this-page" title="Tags">Tags</span>
        </div>
        <div class="right">
            <a class="button" href="<?= $app->config['mainDirUrl'] ?>/tags/new" title="New tag">New</a>
        </div>
    </section>
	<section class="tags">
		<h1>Tags</h1>
		<div class="table-container">
			<?php if($records_count): ?>
				<table>
					<thead>
						<tr>
							<th class="id">
								Id
							</th>
							<th class="text">
								Text
							</th>
							<th class="documents-count">
								Documents
							</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($tags as $tag_item): ?>
							<tr>
								<td class="id">
									<a href="<?= $app->config['thisDirUrl'] ?>/tags/view?id=<?= $tag_item['id'] ?>" title="view / update"><?= $tag_item['id'] ?></a>
								</td>
								<td class="text">
									<?= $tag_item['text'] ?>
								</td>
								<td class="documents-count">
									<?= $tag_item['documents_count'] ?>
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
	<section class="dummy" style="display: none;">
		<a href="<?= $app->config['thisDirUrl'] ?>/scans/view?id=" title="view / update"></a>
	</section>
	<?= $app->Footer() ?>
</body>
</html>