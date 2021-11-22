<?php
	require_once("./../../common.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>SBC &ndash; Career Opportunities</title>
    <link rel="icon" href="<?= $sbcWeb->config["mainDirUrl"] ?>/../favicon.png?v=3">
    <link type="text/css" meadia="screen" rel="stylesheet" href="<?= $sbcWeb->config["mainDirUrl"] ?>/avenir-lt-std.php">
	<?= $sbcWeb->jQuery() ?>
    <link href="<?= $sbcWeb->config["mainDirUrl"] ?>/tabulator-master/dist/css/tabulator.min.css" rel="stylesheet">
    <link href="<?= $sbcWeb->config["mainDirUrl"] ?>/tabulator-master/dist/css/bulma/tabulator_bulma.min.css" rel="stylesheet">
    <script src="<?= $sbcWeb->config["mainDirUrl"] ?>/tabulator-master/dist/js/tabulator.min.js"></script>
    <link type="text/css" meadia="screen" rel="stylesheet" href="<?= $sbcWeb->config["thisDirUrl"] ?>/index-css.php">
    <script src="<?= $sbcWeb->config["thisDirUrl"] ?>/index.js"></script>
	<?=	$sbcWeb->webDeveloper("htmlHead") ?>
</head>
<body>
    <section class="action-bar">
        <a class="button add" href="<?= $sbcWeb->config['thisDirUrl'].'/new' ?>">Add Opportunity</a>
    </section>
    <section class="career-opportunities">
        <h1>Career Opportunities</h1>
        <div id="career-opportunities-table">
        </div>
    </section>
    <?= $sbcWeb->webDeveloper("dir") ?>
</body>
</html>