<?php 

session_start();
require('class.php');
	
	$password = "quthes-wumvoT-febte0";
	
	if (isset($_POST['key'])){

		$key = $_POST['key'];

		if ($key === $password){

			unset($_SESSION['auth']);
			$_SESSION['auth'] = (int)(microtime(true) * 1000);

			$result =  new info("prompt", "Authentication succesful!");

		}else {

            $result =  new info("error", "Authentication failed!");

		}

	}

	if (isset($_GET['path'])){
		if ((((int)(microtime(true) * 1000))  - $_SESSION['auth']) < 300000){
			$result = 	true;
			
		}
		else { $result = false; }

	}

	if (isset($_GET['key'])){
		unset($_SESSION['auth']);
		$result =  new info("prompt", "Logged Out succesfully!");

	}

	$delete = json_encode($result);
	echo $delete;

?>