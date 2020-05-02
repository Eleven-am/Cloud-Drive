<?php 
	
	session_start();
	include("json.php");

	/*set your folder*/
	$file_path = (isset($_GET["file"])) ? decypher($_GET["file"], 1, FALSE) : $_SESSION["download"];
	$base = dirname($file_path)."/";
	$file_download = basename($file_path);

		/*output must be folder/yourfile*/
	if (is_dir($file_path)){
		
     	$zip_path = "/home/temp/".$file_download.".zip";
      
      // Initialize archive object
      new GoodZipArchive($file_path, $zip_path);
	}

	else{

		output_file($base, $file_path, ''.$file_download.'', $row['type']);
	
	}

  class GoodZipArchive extends ZipArchive 
  {
    //@author Nicolas Heimann
    public function __construct($a=false, $b=false) { $this->create_func($a, $b);  }

    public function create_func($input_folder=false, $output_zip_file=false)
    {
      if($input_folder !== false && $output_zip_file !== false)
      {
        $res = $this->open($output_zip_file, ZipArchive::CREATE);
        if($res === TRUE) 	{ $this->addDir($input_folder, basename($input_folder)); $this->close(); 
                               header("Content-type: application/zip"); 
                               header('Content-Disposition: attachment; filename="'.basename($output_zip_file).'"');
                               header("Content-length: " . filesize($output_zip_file)); 
                               header("Pragma: no-cache"); 
                               header("Expires: 0"); 
                               readfile($output_zip_file);
                               exit;                 
     	}else  				{ $_SESSION['error'] = 'Something went wrong: Could not create a zip archive.'; }  
      }
    }

      // Add a Dir with Files and Subdirs to the archive
      public function addDir($location, $name) {
          $this->addEmptyDir($name);
          $this->addDirDo($location, $name);
      }

      // Add Files & Dirs to archive 
      private function addDirDo($location, $name) {
          $name .= '/';         $location .= '/';
        // Read all Files in Dir
          $dir = opendir ($location);
          while ($file = readdir($dir))    {
              if ($file == '.' || $file == '..') continue;
            // Rekursiv, If dir: GoodZipArchive::addDir(), else ::File();
              $do = (filetype( $location . $file) == 'dir') ? 'addDir' : 'addFile';
              $this->$do($location . $file, $name . $file);
          }
      } 
  }

	function output_file($baseUrl, $file, $name, $mime_type='')
	{
	    if(!is_readable($file)) {
	    	$_SESSION['error'] = "File not found or inaccessible!";
	    	header('Location:index.php?dir='.$baseUrl);
	    }

	    $size = filesize($file);
	    $name = rawurldecode($name);
	    $known_mime_types=array(
	        "htm" => "text/html",
	        "exe" => "application/octet-stream",
	        "zip" => "application/zip",
	        "doc" => "application/msword",
	        "jpg" => "image/jpg",
	        "php" => "text/plain",
	        "xls" => "application/vnd.ms-excel",
	        "ppt" => "application/vnd.ms-powerpoint",
	        "gif" => "image/gif",
	        "pdf" => "application/pdf",
	        "txt" => "text/plain",
	        "html"=> "text/html",
	        "png" => "image/png",
	        "jpeg"=> "image/jpg"
	    );

	    if($mime_type==''){
	        $file_extension = strtolower(substr(strrchr($file,"."),1));
	        if(array_key_exists($file_extension, $known_mime_types)){
	            $mime_type=$known_mime_types[$file_extension];
	        } else {
	            $mime_type="application/force-download";
	        };
	    };

	    @ob_end_clean();
	    if(ini_get('zlib.output_compression'))
	    ini_set('zlib.output_compression', 'Off');
	    header('Content-Type: ' . $mime_type);

	    if ($mime_type != "application/force-download"){
	    	header('Content-Disposition: inline; filename="'.$name.'"');
	    }

	    else{
	    	header('Content-Disposition: attachment; filename="'.$name.'"');
	    }
	    
	    header("Content-Transfer-Encoding: binary");
	    header('Accept-Ranges: bytes');

	    if(isset($_SERVER['HTTP_RANGE']))
	    {
	        list($a, $range) = explode("=",$_SERVER['HTTP_RANGE'],2);
	        list($range) = explode(",",$range,2);
	        list($range, $range_end) = explode("-", $range);
	        $range=intval($range);
	        if(!$range_end) {
	            $range_end=$size-1;
	        } else {
	            $range_end=intval($range_end);
	        }

	        $new_length = $range_end-$range+1;
	        header("HTTP/1.1 206 Partial Content");
	        header("Content-Length: $new_length");
	        header("Content-Range: bytes $range-$range_end/$size");
	    } else {
	        $new_length=$size;
	        header("Content-Length: ".$size);
	    }

	    $chunksize = 1*(1024*1024);
	    $bytes_send = 0;

	    if ($file = fopen($file, 'r'))
	    {
	        if(isset($_SERVER['HTTP_RANGE']))
	        fseek($file, $range);

	        while(!feof($file) &&
	            (!connection_aborted()) &&
	            ($bytes_send<$new_length)
	        )
	        {
	            $buffer = fread($file, $chunksize);
	            echo($buffer);
	            flush();
	            $bytes_send += strlen($buffer);
	        }
	        fclose($file);
	    } else{
	    	$_SESSION['error'] = (isset($_SESSION['error']))? $_SESSION['error']: "Error - can not open file.";
	    	header('Location:index.php?dir='.$base);
	    }

	    die();
	}
	set_time_limit(0);


	/*back to index.php while downloading*/
	header('Location:index.php?dir='.$base);

?>

