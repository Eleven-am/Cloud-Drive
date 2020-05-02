<?php 

	session_start();
	require("class.php");
	include("json.php");

	if (isset($_POST['search']) && isset($_SESSION['root'])){
		$searchq = $_POST['search'];

		$datab = [];
		if (file_exists($_SESSION["JSON"]."search.json")){
			$rootfolder = retrieve($_SESSION["JSON"]."search.json");

			if (!empty($rootfolder)){
				$datab =  $rootfolder;
			}
		}
		else $datab =  folderscan($_SESSION["share"]);

	}

	else {
		$result =  new info("error", "File not found or inaccessible!");
		$myJSON =  json_encode($result);
		die($myJSON);
	}

		function searcharray($searchq, $datab){
			natsort($datab);
			$keys = array();

			foreach ($datab as $key => $value) {
				
				if (stripos($value, $searchq) !== FALSE){

					$new = array_push($keys , $key);

				}

			}

			return $keys;

		}

		function scanarray($obj, $obj2, $obj3, $datab){
			$keys = array();
			natsort($datab);

			foreach ($datab as $key => $value) {
				
				if (stripos($value, $obj) !== FALSE || stripos($value, $obj2) !== FALSE || stripos($value, $obj3) !== FALSE){

					$new = array_push($keys , $key);

				}

			}

			return $keys;
		}

		$present = new fsOBJ("Search Results", "root", "url-id", "root", "-", "Search Results");
		if ($searchq != "media") { $result= searcharray($searchq, $datab); }

		else {
			$present->name = "Media";
			$result = scanarray("mkv", "mp4", "m4v", $datab);
		}

		if (empty($result)){
			$result =  new info("prompt", "No results found!");
			$myJSON =  json_encode($result);
			die($myJSON);

		}else{
			$result = random($result, "root", 1);
			$result = handle($result, $present);
			$myJSON =  json_encode($result);
			echo $myJSON;

	  }

?>

