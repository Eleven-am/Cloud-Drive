<?php
session_start();

    function genrandom($length) {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }

    function random($array, $string, $int){
        $keys = [];
        $values = [];

        $tmp2 = $string;
        $tmp3 = genrandom(13);
        array_push($keys, $tmp2);
        array_push($values, $tmp3);

        $tmp2 = dirname($string)."/";
        $tmp3 = genrandom(13);
        array_push($keys, $tmp2);
        array_push($values, $tmp3);

        foreach ($array as $item){
            if (is_dir($item)){
                array_push($keys, $item."/");
            }else {
                array_push($keys, $item);
            }

            $tmp = genrandom(13);
            while (array_search($tmp, $values)){
                $tmp = genrandom(13);
            }
            array_push($values, $tmp);
        }

        $result = array_combine($keys, $values);

        if ($int === 2) {
            $_SESSION["movearray"] = $result;
        }

        return $result;
    }

    function decypher($string, $int, $bool){
        $standard = array(
            "root" => "root",
            "back" => "back",
            "Private" => "/home/Private/",
            "Downloads" => "/home/deluge/Downloads/",
            "Uploads" => "/home/maix/Uploads/",
            "home" => "home"
        );

        $keys = array_keys($standard);
        $values = array_values($standard);

        $dir = "";
        if ($int === 1){
            if (isset($_SESSION["cypher"])){
                $standard = $_SESSION["cypher"];
                $standardDo = array_combine($keys, $values);
                $standard = array_merge($standard, $standardDo);
                
            }

            $dir = array_key_exists($string, $standard)? $standard[$string]: "root";

        } else if ($int === 2) {
            if (isset($_SESSION["movearray"])){
                $standard = $_SESSION["movearray"];
                $standardDo = array_combine($keys, $values);
                $standard = array_merge($standard, $standardDo);
            }

            $dir = (array_search($string, $standard)) ? array_search($string, $standard) : "root";
        }
        if (!$bool){
            recentJSON($dir);
        }
        return $dir;
    }

    function videothumb($video){
        $dic = array(
            " " => "\ ",
            "(" => "\(",
            ")" => "\)"
        );

        $video = strtr($video, $dic);

        $videodeets = pathinfo($video);
        $image = $videodeets["filename"].".png";

        $command = "ffmpeg -i ".$video." -vframes 1 -an -s 480x270 -ss 30 ".$_SESSION["JSON"].$image;
        return $command;
    }

    function create($myArray, $name){
        $myArray = json_encode($myArray);
        file_put_contents($name, $myArray);

    }

    function retrieve($name){
        if (file_exists($name)){
            $myArray = file_get_contents($name);
            $myArray = json_decode($myArray);

            $keys = array();
            $values = array();

            foreach ($myArray as $item => $value) {
                array_push($keys, $item);
                array_push($values, $value);
            }

            $myArray = array_combine($keys, $values);
            return $myArray;
        }

    }

    function update($dir, $value, $name, $int){
        $myArray = retrieve($name);
        $keys = array_keys($myArray);
        $values = array_values($myArray);

        array_push($keys, $dir);
        array_push($values, $value);

        $myArray = array_combine($keys, $values);

        if ($int != 0){
            while(count($myArray) > $int){
                array_shift($myArray);
            }
        }

        create($myArray, $name);

    }


    function recentJSON($dir){
        if(is_file($dir)){
            $value = genrandom(13);

            if (file_exists($_SESSION["JSON"]."recent.json")){
                update($dir, $value,$_SESSION["JSON"]."recent.json", 4);
                recentimages(retrieve($_SESSION["JSON"]."recent.json"));

            }else {
                $myArray = array($dir => $value);
                create($myArray,$_SESSION["JSON"]."recent.json");
                recentimages($myArray);
            }
        }

    }

    function shareJSON($dir, $value){
        if (file_exists($_SESSION["JSON"]."share.json")){
            update($value, $dir, $_SESSION["JSON"]."share.json", 0);
        }

        else {
            $myArray = array($value => $dir);
            create($myArray,$_SESSION["JSON"]."share.json");
        }
    }

    function make_thumb($src, $dest, $desired_width) {

        /* read the source image */
        $tmp  = strtolower(end(explode(".",$src)));
        $source_image = ($tmp === "png")?imagecreatefrompng($src): imagecreatefromjpeg($src);
        $width = imagesx($source_image);
        $height = imagesy($source_image);

        /* find the "desired height" of this thumbnail, relative to the desired width  */
        $desired_height = floor($height * ($desired_width / $width));
        $desired_height = ($desired_height > 270)? 270: $desired_height;

        /* create a new, "virtual" image */
        $virtual_image = imagecreatetruecolor($desired_width, $desired_height);

        /* copy source image at a resized size */
        imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

        /* create the physical thumbnail image to its destination */
        imagejpeg($virtual_image, $dest);
    }

    function recentimages($myArray){
        $default = "src/doc.png";

        $keys = array_keys($myArray);
        foreach ($keys as $value){
            if(is_file($value)){
                $filedeets = pathinfo($value);
                $image = $_SESSION["JSON"].$filedeets["filename"].".png";

                if (!file_exists($image)){
                    $tmp  = strtolower(end(explode(".",$value)));

                    if ($tmp === "jpeg" || $tmp === "jpg" || $tmp === "png"){
                        make_thumb($value, $image, 480);

                    } else if ($tmp === "mkv" || $tmp === "mp4" || $tmp === "m4v" || $tmp === "webm") {
                        $command  = videothumb($value);
                        exec($command);

                    } else {
                        copy($default, $image);
                    }

                }

            }
        }

        $list = preg_grep('/^([^.])/', scandir($_SESSION["JSON"]));

        foreach ($list as $item){
            $tmp  = strtolower(end(explode(".",$item)));
            if ($tmp === "png" && $item != "doc.png"){
                $check = FALSE;
                foreach ($keys as $key){
                    $key2 = pathinfo($key);
                    $key2 = $key2["filename"].".png";
                    if ($key2 === $item){
                        $check = TRUE;
                    }
                }

                if (!$check) unlink($_SESSION["JSON"].$item);
            }

        }

    }

    function informJSON($dir, $new, $int){
        $dic = array($dir => $new);

        if (file_exists($_SESSION["JSON"]."share.json")){
            $share = retrieve($_SESSION["JSON"] . "share.json");
            if (!empty($share)){
                $skeys = [];
                $svalues = [];

                if ($int === 2) {
                    foreach ($share as $code => $link) {
                        $tscode = $code;
                        $tslink = strtr($link, $dic);

                        array_push($skeys, $tscode);
                        array_push($svalues, $tslink);
                    }

                } else if ($int === 1) {
                    foreach ($share as $code => $link) {
                        if (strpos($link, $dir) === FALSE) {
                            $tscode = $code;
                            $tslink = $link;

                            array_push($skeys, $tscode);
                            array_push($svalues, $tslink);

                        }
                    }
                }

                $share = array_combine($skeys, $svalues);
                create($share, $_SESSION["JSON"] . "share.json");
            }
        }

        if (file_exists($_SESSION["JSON"]."recent.json")) {
            $recent = retrieve($_SESSION["JSON"] . "recent.json");
            if (!empty($recent)){
                $rkeys = [];
                $rvalues = [];

                if ($int === 2) {
                    foreach ($recent as $link => $code) {
                        $trcode = $code;
                        $trlink = strtr($link, $dic);

                        array_push($rkeys, $trlink);
                        array_push($rvalues, $trcode);
                    }

                } else if ($int === 1) {
                    foreach ($recent as $link => $code) {
                        if (strpos($link, $dir) === FALSE) {
                            $trcode = $code;
                            $trlink = $link;

                            array_push($rkeys, $trlink);
                            array_push($rvalues, $trcode);

                        }
                    }
                }

                $recent = array_combine($rkeys, $rvalues);
                create($recent, $_SESSION["JSON"] . "recent.json");
            }
        }
    }

    function locate($dir){
        $server = array("Gdrive" => "Google Drive", "Mega" => "Mega.nz", "Onedrive" => "Onedrive");

        $locale = "Host Server";
        foreach($server as $host => $value){
            if (strpos($dir, $host) != FALSE){
               $locale = $value;
            }
        }

        return $locale;
    }

