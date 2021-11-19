<?php
	require("./common.php");
	$pathname = $_POST['pathname'] ?? null;
	if(!empty($pathname))
	{
		unset($_POST['pathname']);
		session_start();
		foreach($_POST as $name => $value)
		{
			switch($name)
			{
				case "view_list_type":
					$_SESSION[$pathname][$name] = $value;
					echo "_SESSION['{$pathname}']['{$name}'] = {$_SESSION[$pathname][$name]}".PHP_EOL;
					break;
				default:
					echo "unrecognized parameter '{$name}'.".PHP_EOL;
					break;
			}
		}
	}
	else
	{
		echo "'pathname' needs to be provided.";
	}