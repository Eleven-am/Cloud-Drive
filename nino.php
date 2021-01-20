<?php
    session_start();

    $dicDo = array(
        " (1080p HD).m4v" => "",
        " (HD).m4v" => "",
        "_" => "",
        ".mp4" => "",".m4v" => "",
        "1080p.BluRay.x264-[YTS.AG]" => "",
        "1080p.BrRip.x264.YIFY.mp4" => "",
        "1080p.BluRay.x264.YIFY.mp4" => "",
        "1080p.BluRay.x264-[YTS.AM].mp4" => "",
        "1080p.BluRay.x264.AAC5.1-[YTS.MX].mp4" => "",
        "1080p.BluRay.x264-[YTS.LT].mp4" => "",
        "1080p.BluRay.x264.AAC5.1-[YTS.LT].m4v" => "",
        "1080p.BluRay.x264.[YTS.AG].mp4" => "",
        ".ECE.2009.1080p.BrRip.x264.bitloks.YIFY.mp4" => "",
        "Extended.1994.BrRip.x264.YIFY.mp4" => "",
        "1080p.BluRay.x264.YIFY.[YTS.AG].mp4" => "",
        "2010.Bluray.1080p.x264.YIFY.mp4" => "",
        "2011.1080p.BrRip.x264.YIFY+HI.mp4" => "",
        "2012" => "",
        "2008" => "",
        "2009" => "",
        "2010" => "",
        "2011" => "",
        "2013" => "",
        "2014" => "",
        "2015" => "",
        "2016" => "",
        "2017" => "",
        "2018" => "",
        "2019" => "",
        "2020" => "",
        "." => " "
    );
    
    if (isset($_GET["check"])) {
        $movieDirectory = ['/home/maix/Gdrive/Movies/', '/home/maix/Gdrive/Multimedia/Movies/', '/home/maix/Mega/Movies/', '/home/maix/Onedrive/Movies/'];
        $tvDirectory = ['/home/maix/Gdrive/TV Shows/', '/home/maix/Gdrive/Multimedia/TV Shows/', '/home/maix/Mega/TV Shows/', '/home/maix/Onedrive/TV Shows/'];

        $movies = gettv();
        //$movies = getItems($movieDirectory);
        $video = strtr(basename($movies[0]), $dicDo);
        $movies = (object)['link' => $movies[0], 'number' => 0, 'name' => $video];
        $myJSON = json_encode($movies);
        echo $myJSON;

    }else if (isset($_POST)) {
        $data = json_decode(file_get_contents('php://input'));
        $object = (object)[
            "overview" => $data->overview,
            "poster" => $data->poster,
            "backdrop" => $data->backdrop,
            "logo" => $data->logo, 
            "name" => $data->name
        ];
            update($data->location, $object, "/home/temp/JSON/movies.json", 0);
            $movieDirectory = ['/home/maix/Gdrive/Movies/', '/home/maix/Gdrive/Multimedia/Movies/', '/home/maix/Mega/Movies/', '/home/maix/Onedrive/Movies/'];
            $tvDirectory = ['/home/maix/Gdrive/TV Shows/', '/home/maix/Gdrive/Multimedia/TV Shows/', '/home/maix/Mega/TV Shows/', '/home/maix/Onedrive/TV Shows/'];


            $num = intval($_GET["next"]);
            $movies = gettv();
            //$movies = getItems($movieDirectory);
            $video = strtr(basename($movies[0]), $dicDo);
            $movies = (object)['link' => $movies[0], 'number' => 0, 'name' => $video];
            $myJSON = json_encode($movies);
            echo $myJSON;
        }

    function gettv(){
        $array = [];
        $checked = retrieve("/home/temp/JSON/tv.json");
        $list =  retrieve("/home/temp/JSON/tvdb.json");
        
        foreach ($list as $item => $value) {
            if (!empty($checked) && !array_key_exists($item, $checked)){
                 array_push($array, $item);
            }
            else if (empty($checked)){
                array_push($array, $item);
            }
        }
        return $array;
    }

    function getItems($directories){
        $array = [];
        $checked = retrieve("/home/temp/JSON/movies.json");

        foreach ($directories as $dir) {
            $list = preg_grep('/^([^.])/', scandir($dir));

            foreach ($list as $item) {
                if (!empty($checked) && !array_key_exists($dir.$item, $checked)){
                  array_push($array, $dir . $item);
                }
                else if (empty($checked)){
                    array_push($array, $dir . $item);
                }
            }
        }

        return $array;
    }

    function create($myArray, $name){
        $myArray = json_encode($myArray);
        file_put_contents($name, $myArray);

    }

    function retrieve($name){
        $myArray = [];
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
        }

        return $myArray;
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

