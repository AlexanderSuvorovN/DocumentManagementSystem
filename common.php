<?php
	class WebApp 
	{
		public $name = "DMS";
		public $config = array();
		public $thisPageUrl = null;
		public $server = [];
		public function __construct()
		{
			$this->config['debug'] = false;
			if($_SERVER["SERVER_NAME"] !== "localhost" && $_SERVER["SERVER_NAME"] !== "127.0.0.1")
			{
				$this->config["runEnv"] = "PROD";
			}
			else 
			{
				$this->config["runEnv"] = "DEV";
			}
			if($this->config["debug"])
			{
				foreach($_SERVER as $key => $val)
				{
					echo "_SERVER['{$key}'] = {$val}<br>";
				}
			}
			$this->config["docRootPath"] = $_SERVER["DOCUMENT_ROOT"];
			/*
			mainDirUrl == __DIR__ - DOCUMENT_ROOT
			*/
			$preg_pattern = sprintf("/%s/i", preg_quote($this->config["docRootPath"], "/"));
			$preg_replace = "";
			$preg_source = preg_replace("/\\\/i", "/", __DIR__);		
			//echo "preg_pattern: " . $preg_pattern . "<br>";
			//echo "preg_source: " . $preg_source . "<br>";
			$this->config["mainDirUrl"] = preg_replace($preg_pattern, $preg_replace, $preg_source);
			$this->config["mainDirPath"] = __DIR__;
			/*
			thisDirUrl = pathinfo(SCRIPT_URL, PATHINFO_DIRNAME);
			*/
			$this->config["thisDirUrl"] = pathinfo($_SERVER["PHP_SELF"], PATHINFO_DIRNAME);
			$this->config["thisDirPath"] = getcwd();
			$this->config['appDirUrl'] = $this->config["mainDirUrl"];
			$this->config['imagesDirUrl'] =	"/images";
			$this->config['fontsDirUrl'] = "/fonts";
			if($this->config["debug"])
			{
				var_dump($this);
			}
			$this->thisPageUrl = preg_replace("/(\.html)|(\.htm)|(\.php)$/i", "", $_SERVER['REQUEST_URI']);
			$this->server = [];
		}
		public function PageMeta()
		{
			require_once("{$this->config["mainDirPath"]}/dms-page-meta.php");
		}
		public function PageTitle($title = "")
		{
			if($title === "")
			{
				$title = "Document Management System";
			}
			echo "<title>{$this->name} &ndash; {$title}</title>";
		}
		public function PageStyle($filename = "", $location = "thisDirUrl")
		{
			if($filename === "")
			{
				require_once("{$this->config["mainDirPath"]}/dms-page-style.php");
			}
			else
			{
				echo "<link type=\"text/css\" meadia=\"screen\" rel=\"stylesheet\" href=\"{$this->config[$location]}/{$filename}\">"; 
			}
		}
		public function PageScript($filename = "", $location = "thisDirUrl")
		{
			if($filename === "")
			{
				echo "<script src=\"{$this->config['mainDirUrl']}/jquery-3.3.1.js\"></script>";
			}
			else
			{
				echo "<script src=\"{$this->config[$location]}/{$filename}\"></script>";
			}
		}
		public function Header()
		{
			require_once("{$this->config["mainDirPath"]}/dms-header.php");
		}	
		public function Footer()
		{

			require_once("{$this->config["mainDirPath"]}/dms-footer.php");
		}
		public function ConsoleLog($msg)
		{
			$date = new DateTime("now", new DateTimeZone("EUROPE/Prague"));	
			$logMsg = __CLASS__ . ", " . $date->format("Y-m-d, G:i:s.u") . ": " . $msg;
			echo "<script>" . PHP_EOL;
			echo "console.log(\"{$logMsg}\");" . PHP_EOL;
			echo "</script>" . PHP_EOL;
		}
		public function HiddenInputNode($options)
		{
			if(!empty($options['name']) && !empty($options['value']))
			{
				$options['value'] = htmlspecialchars($options['value']);
				echo "<input type=\"hidden\" name=\"{$options['name']}\" value=\"{$options['value']}\">";
			}
		}
	};
	$app = new WebApp();