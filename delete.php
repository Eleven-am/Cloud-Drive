<?php  
	
session_start();
require('class.php');
include("json.php");


function folderDelete($path){
    $list = preg_grep('/^([^.])/', scandir($path));
	foreach ($list as $element) {
		if (is_dir($path.$element)) {
			folderDelete($path.$element."/");
		}
		else {
			unlink($path.$element);
		}
	}
	rmdir($path);

}

	if (isset($_GET["file"])){

		$path = decypher($_GET["file"], 1, TRUE);

		$filepath = dirname($path);
		$filename = basename($path);
		$stat = (is_dir($path))? "directory" : "file";
	
		if ((((int)(microtime(true) * 1000))  - $_SESSION['auth']) < 300000){
			if (file_exists($path)){
				if (!is_dir($path)){
				 	if (!unlink($path)) {
						$result =  new info("error", "An error occured; You may not have permission to delete this");
	
				 	} else {
                        informJSON($path, "", 1);
                        $result =  new info("prompt", "File delete succesful");

				 	}
	
				} else {
					
					folderDelete($path);
				 	$result =  new info("prompt", "Folder delete succesful");
				 	
				}

			}else {
                $result =  new info("error", "The ".$stat." ".$filename." does not exist");

			}

		}else {

			$result =  new info("alert","You need to be logged in to perform this task");

		}
		
		$delete = json_encode($result);
		echo $delete;

	}
	
?>