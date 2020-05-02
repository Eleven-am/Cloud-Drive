<?php  
	class info{
		public $type;
		public $message;

		function __construct($type, $message){
			$this->type = $type;
			$this->message = $message;
		}
	}
	
	class fsOBJ{
		public $name;
		public $url_link;
		public $class;
		public $image;
		public $size;
		public $data_name;

		function __construct ($name, $url_link, $class, $image, $size, $data_name){
			$this->name = $name;
			$this->url_link = $url_link;
			$this->class = $class;
			$this->image = $image;
			$this->size = $size;
			$this->data_name = $data_name;
		}
	}

	function handle($list, $obj){
		$keys = array_keys($list);
		$values = array_values($list);

		if (isset($_SESSION["recent"])) {
			$keys2 = array_keys($_SESSION["recent"]);
			$value2 = array_values($_SESSION["recent"]);

			$keys = array_merge($keys, $keys2);
			$values = array_merge($values, $value2);
		}

		$_SESSION["cypher"] = array_combine($values, $keys);

		$folders = array();
		$files_list = array();

		array_push($folders, $obj);

		array_shift($list);
		array_shift($list);

		foreach ($list as $element => $value) {
			$tmp  = strtolower(end(explode(".",$element)));
			$size = sizefinder($element);

				if(is_dir($element)){
					$tmp = new fsOBJ(basename($element), $value, "disk link", "src/folder.svg", $size, "-");
					array_push($folders, $tmp);
				}

				else if($tmp === "mkv" || $tmp === "mp4" || $tmp === "m4v" || $tmp === "webm"){
					$tmp = new fsOBJ(basename($element), $value, "disk cons media", "src/video.svg", $size, basename($element));
					array_push($files_list, $tmp);
				}

				else if ($tmp === "png" || $tmp === "jpg"){
					$tmp = new fsOBJ(basename($element), $value, "disk cons image", "src/image.svg", $size, basename($element));
					array_push($files_list, $tmp);
				}

				else {
					$tmp = new fsOBJ(basename($element), $value, "disk download","src/file.svg", $size, basename($element));
					array_push($files_list, $tmp);
				}
		}

		$list = array_merge($folders, $files_list);
		return $list;
	}

	function startsWith ($string, $startString) 
	{ 
	    $len = strlen($startString); 
	    return (substr($string, 0, $len) === $startString); 
	}

	function FileSizeConvert($bytes){
	    $bytes = floatval($bytes);
	        $arBytes = array(
	            0 => array(
	                "UNIT" => "TB",
	                "VALUE" => pow(1024, 4)
	            ),
	            1 => array(
	                "UNIT" => "GB",
	                "VALUE" => pow(1024, 3)
	            ),
	            2 => array(
	                "UNIT" => "MB",
	                "VALUE" => pow(1024, 2)
	            ),
	            3 => array(
	                "UNIT" => "KB",
	                "VALUE" => 1024
	            ),
	            4 => array(
	                "UNIT" => "B",
	                "VALUE" => 1
	            ),
	        );

	    foreach($arBytes as $arItem)
	    {
	        if($bytes >= $arItem["VALUE"])
	        {
	            $result = $bytes / $arItem["VALUE"];
	            $result = str_replace(".", "," , strval(round($result, 2)))." ".$arItem["UNIT"];
	            break;
	        }
	    }
	    return $result;
	}

	function diskinfo($disk){
		$total = disk_total_space($disk);
		$free = disk_free_space($disk);
		$used = $total - $free;

		$percent = ($used/$total) * 100;
		$obj = (object)["total" => FileSizeConvert($total), "used" => FileSizeConvert($used), "percent" => $percent, "size" => $free];
		return $obj;
	}

	function sizefinder ($dir)
	{
		if (is_dir($dir)) {
			$size = "--";

		}

		else{
			$size = filesize($dir);
			$size = FileSizeConvert($size);
		}

		return $size;
	}


	function folderscan ($dir){
		$list = preg_grep('/^([^.])/', scandir($dir));

		$keys = array();
		$values = array();

		foreach ($list as $element) {

			if (!is_dir($dir.$element)){

				array_push($keys , $dir.$element);
				array_push($values , $element);

			}

			else {

				array_push($keys , $dir.$element);
				array_push($values , $element);

				if (!is_null(folderscan($dir.$element.'/'))){
					$tmp = folderscan($dir.$element.'/');

					$tmpval = array_values($tmp);
					$tmpkey = array_keys($tmp);

					$keys = array_merge($keys , $tmpkey);
					$values = array_merge($values , $tmpval);

				}

			}
		}


		$result = array_combine($keys, $values);
		return $result;

	}
