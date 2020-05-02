<?php

session_start();

	$locale = $_GET['link'];
	
	foreach ($_FILES['submit']["tmp_name"] as $key => $value) {
		$targetPath =  $locale.'/'.($_FILES['submit']['name'][$key]);
		if (file_exists($locale)){
			move_uploaded_file($value, $targetPath);
		}
		else {
			mkdir($locale, 0777, FALSE);
			move_uploaded_file($value, $targetPath);
		}
	}
  
?>
