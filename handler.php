<?php  

session_start();
include("class.php");
include("json.php");

$dir_old = decypher($_GET["resource"], 1, FALSE);
	if (isset($_SESSION["state"]) && $dir_old == "root"){
		$dir_old = $_SESSION["state"];
	}

$dir_old = ($dir_old  != "home")? $dir_old : $_SESSION['root'];
if (file_exists($_SESSION["JSON"]."recent.json")) $_SESSION["recent"] = retrieve($_SESSION["JSON"]."recent.json");
//if (!file_exists($_SESSION["JSON"]."search.json")) create(folderscan($_SESSION["search"]),$_SESSION["JSON"]."search.json");

	if (startsWith($dir_old, $_SESSION['root'])){
		$dir = $dir_old;

	}

	else if (isset($_SESSION['auth'])){
		if ((((int)(microtime(true) * 1000))  - $_SESSION['auth']) < 300000){
			$dir = $dir_old;
    

		}else {
			unset($_SESSION['auth']);
			$result =  new info("alert", "Please reauthenticate to continue");
			$result = json_encode($result);
			die($result);

		}

	}

	else if (!isset($_SESSION['auth'])){
		$result =  new info("alert", "You need to authenticate to view requested folder");
		$result = json_encode($result);
		die($result);
	}

	else {
		$dir = $_SESSION['root'];	
	}

	$_SESSION["state"] = $dir;
	$list = preg_grep('/^([^.])/', scandir($dir));

	    $tempkey = array();
	    foreach ($list as $element) {
	    	array_push($tempkey, $dir.$element);
	    }

	$list = random($tempkey, $dir, 1);
		$dir2 = array_values($list)[0];
		$dir3 = array_values($list)[1];

		$present = new fsOBJ(basename($dir), $dir2, "url-id", $dir3, "-", basename($dir));
		$response = handle($list, $present);


	$myJSON =  json_encode($response);
	echo $myJSON;

?>
