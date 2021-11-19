<?php
	function dbConnect()
	{
		try
		{
			$GLOBALS["dbh"] = new PDO("mysql:host=localhost;dbname=sam;charset=utf8", "app_sam_general", "app_sam_general");
		    $GLOBALS["dbh"]->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    //echo "Connected successfully<br>";
		}
		catch(PDOException $e)
		{
		    echo "Database connection failed: " . $e->getMessage() . "<br>";
		}
	}
	function dbClose()
	{
		$GLOBALS["dbh"] = null;
	}