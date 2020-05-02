<?php

session_start();
require("class.php");
include("json.php");

	// Function to remove folders and files 
    function rrmdir($dir) {
        if (is_dir($dir)) {
            $files = scandir($dir);
            foreach ($files as $file)
                if ($file != "." && $file != "..") rrmdir("$dir/$file");
            rmdir($dir);
        }
        else if (file_exists($dir)) unlink($dir);
    }

    // Function to Copy folders and files       
    function rcopy($src, $dst) {
        if (file_exists ( $dst ))
            rrmdir ( $dst );
        if (is_dir ( $src )) {
            mkdir ( $dst );
            $files = scandir ( $src );
            foreach ( $files as $file )
                if ($file != "." && $file != "..")
                    rcopy ( "$src/$file", "$dst/$file" );
        } else if (file_exists ( $src ))
            copy ( $src, $dst );

        rrmdir($src);    
    }

	if (isset($_GET['path'])){
	    $path = decypher($_GET['path'], 2, FALSE);
        $dir = ($path != 'root')? $path: $_SESSION['root'];
        $dir = ($dir != 'back')? $dir: dirname($_SESSION['movelocale']);
		$dir = (startsWith($dir, $_SESSION['root']))? $dir: $_SESSION['root'];

		$_SESSION['movelocale'] = $dir;
		$list = preg_grep('/^([^.])/', scandir($dir));
		$tmp = [];

		foreach ($list as $value){
            if(is_dir($dir.$value)){
                $temp = $dir.$value."/";
            }else{
                $temp = $dir.$value;
            }
            array_push($tmp, $temp);
        }

		$list = $tmp;
        $list = random($list, $dir, 2);

        $folders = [];
        $files = [];

        array_shift($list);
        array_shift($list);

		foreach ($list as $element => $value) {
            $tmp  = strtolower(end(explode(".",$element)));

            if (is_dir($element)){
				$tmp = (object)["path" => $value, "css_class" => "move_element", "value" => basename($element), "jsclick" => "opendir(event)", "image" => "src/folder-2.svg"];
				array_push($folders, $tmp);
			}

			else {
			    $image = ($tmp === "mkv" || $tmp === "mp4" || $tmp === "m4v" || $tmp === "webm")? "src/video.svg": "src/file.svg";

                $tmp = (object)["path" => $value, "css_class" => "unmovable-file", "value" => basename($element), "jsclick" => "empty(e)", "image" => $image];
                array_push($files, $tmp);
            }
		}

        $result = array_merge($folders, $files);
		$myJSON =  json_encode($result);
		echo $myJSON;

	} else if (isset($_GET["disk"])){
        $disk = floatval($_GET["disk"]);

        $result = diskinfo("/");
        $myJSON =  json_encode($result);
        echo $myJSON;

        if ($disk != 0 && disk_free_space("/") != $disk){
            create(folderscan($_SESSION["search"]), $_SESSION["JSON"]."search.json");
        }


    } else {
        if ((((int)(microtime(true) * 1000))  - $_SESSION['auth']) < 300000){
            $file = decypher($_GET['move'], 1, TRUE);

            $new = is_dir($file)? $_SESSION['movelocale'] . basename($file) . "/" : $_SESSION['movelocale'] . basename($file);
            $obj = is_dir($file)? "Folder": "File";

            informJSON($file, $new, 2);
            rcopy($file, $_SESSION['movelocale'] . basename($file));

            $result = new info("prompt", $obj." moved succesfully!");

        }else {
            $result =  new info("alert","You need to be logged in to perform this task");

        }


        $myJSON =  json_encode($result);
        echo $myJSON;
	}
?>