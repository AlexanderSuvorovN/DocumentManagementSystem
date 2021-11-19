<?php
	require_once("db.php");
	dbConnect();
	$query = "SELECT `d`.`id`, `d`.`name`, `d`.`description`, `d`.`tags`, `f`.`label_text` FROM `dms_document` AS `d` JOIN `dms_folder` AS `f` ON(`d`.`folder_id` = `f`.`id`)";
	$s = $dbh->prepare($query);
	$s->execute();
	$fetch = $s->fetchAll(PDO::FETCH_ASSOC);
	dbClose();
	$documents = [];
	foreach($fetch as $d)
	{
		$documents[] = $d;
	}
	$records_count = count($documents);
	$columns = ["id", "name", "description", "tags", "label_text"];
?>
<input type="hidden" name="query" value="<?= $query ?>">
<input type="hidden" name="records_count" value="<?= $records_count ?>">
<?php if($documents): ?>
<table>
	<thead>
		<tr>
			<?php foreach($columns as $column): ?>
				<th><?= $column ?></th>
			<?php endforeach; ?>
		</tr>
	</thead>
	<tbody>
		<?php foreach($documents as $d): ?>
			<tr>
				<td class="id"><a href="/news/view?id=<?= $news_item['id'] ?>"><?= $d["id"] ?></a></td>
				<td class="name"><?= $d["name"] ?></td>
				<td class="description"><?= $d["description"] ?></td>
				<td class="tags"><?= $d["tags"] ?></td>
				<td class="label_text"><?= $d["label_text"] ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
<table>
<?php endif; ?>