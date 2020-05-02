<?php 

session_start();
require("class.php");
include("json.php");

//handles my rename, create and share processes

	if (isset($_GET["url"])){
	    $path = $_GET["url"];
        $shares = retrieve($_SESSION["JSON"]."share.json");

        $link = array_key_exists($path, $shares)? $shares[$path]:"";

        if ($link != "" && file_exists($link)){
            $value =  genrandom(13);
            $array = array($link => $value);
            $_SESSION["cypher"] = $array;
            $_SESSION['share'] = $_SESSION["cypher"];

        }
        header('Location: https://www.maix.ovh');

	}

	if (isset($_POST["rename"]) && isset($_GET["path"])){

		$oldname = decypher($_GET['path'], 1, TRUE);
		
		$filepath = dirname($oldname);
		$filename = basename($oldname);

		$fileExt = strtolower(substr(strrchr($filename,"."),1));

		$info = $_POST['rename'];

		$newname = (is_dir($oldname))? $filepath.'/'.$info: $filepath.'/'.$info.".".$fileExt;
		$type = (is_dir($oldname))? "folder": "file";

        if ((((int)(microtime(true) * 1000))  - $_SESSION['auth']) < 300000){
            if (file_exists($newname)) {

                $result = new info("error", "A " . $type . " with this name: " . $info . "." . $fileExt . " already exists");


            } else {
                $new = is_dir($oldname) ? $newname . "/" : $newname;
                if (rename($oldname, $newname)) {
                    informJSON($oldname, $new, 2);
                    $result = new info("prompt", "Rename Succesful");


                } else {

                    $result = new info("error", "An error occured, You may not have permission to rename this");

                }

            }

        }else {

            $result =  new info("alert","You need to be logged in to perform this task");

        }

        $delete = json_encode($result);
        echo $delete;
	}

	if (isset($_POST["create"]) && isset($_GET["path"])){
	    $info = $_POST["create"];
	    $path = decypher($_GET["path"], 1, TRUE);
	    $folderpath = $path.$info;

	    if (file_exists($folderpath)){
            $result = new info("error", "An error occured, A folder with that name already exists");
        }

	    else{
	        mkdir($folderpath, 0777, FALSE);
            $result = new info("prompt", "Folder creation successfully");
        }

        $delete = json_encode($result);
        echo $delete;
    }


	//useful for my upload function
	if (isset($_GET["code"])){
        $code = decypher($_GET["code"], 1, TRUE);

        $delete = json_encode($code);
        echo $delete;
    }

	//encoding the link that has just been shared
	if (isset($_GET["encode"])){
	    $path = decypher($_GET["encode"], 1, TRUE);
	    if ($path == "root"){
            echo json_encode(FALSE);
        }

	    else {
            shareJSON($path, $_GET["encode"]);
            echo json_encode(TRUE);
        }

    }
?>