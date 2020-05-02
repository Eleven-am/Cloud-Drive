<?php

session_start();
$_SESSION['root'] = ""; //this is the start directory aka scope, going outside this scope requires authentication -- see auth.php
$_SESSION["JSON"] = ""; //this is the location for the json files / necessary for the search, share and recent features -- see json.php
$_SESSION["search"] = ""; //this is the search scope of the server, session_root recommended
$_SESSION["password"] = ""; //this is the auth key allowing you to got outside session_root's scope, deleting, moving and renaming files
$server = ""; //this is the server's domain.name it is used in the og meta tags 
$name  = ""; //this is the still the Domain.Name but this can be stylised (capslock/not) it also is used in the og meta tags
$user = ""; //this is the proprietor of the server, you may use your name here. It is used in the og meta tags
// To finish config please head to js/properties.js and change line 49 to your server name

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Primary Meta Tags -->
    <title><?= $name; ?> - The Private web server for <?= $user; ?></title>
    <meta name="title" content="<?= $name; ?> - The Private web server for <?= $user; ?>">
    <meta name="description" content="Access files made available to you by the author. Share, store files and create folders on their server.">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://<?= $server; ?>/">
    <meta property="og:title" content="<?= $name; ?> - The Private web server for <?= $user; ?>">
    <meta property="og:description" content="Access files made available to you by the author. Share, store files and create folders on their server.">
    <meta property="og:image" content="src/meta.png">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://<?= $server; ?>/">
    <meta property="twitter:title" content="<?= $name; ?> - The Private web server for <?= $user; ?>">
    <meta property="twitter:description" content="Access files made available to you by the author. Share, store files and create folders on their server.">
    <meta property="twitter:image" content="src/meta.png">

    <!-- The favicons, fonts and stylsheets -->
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="apple-touch-icon" href="/apple-touch-icon.png" />
    <link rel="apple-touch-icon" sizes="57x57" href="favicon/apple-touch-icon-57x57.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="favicon/apple-touch-icon-72x72.png" />
    <link rel="apple-touch-icon" sizes="76x76" href="favicon/apple-touch-icon-76x76.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="favicon/apple-touch-icon-114x114.png" />
    <link rel="apple-touch-icon" sizes="120x120" href="favicon/apple-touch-icon-120x120.png" />
    <link rel="apple-touch-icon" sizes="144x144" href="favicon/apple-touch-icon-144x144.png" />
    <link rel="apple-touch-icon" sizes="152x152" href="favicon/apple-touch-icon-152x152.png" />
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon-180x180.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="manifest" href="favicon/site.webmanifest">
    <link rel="mask-icon" href="favicon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="favicon/favicon.ico">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-config" content="favicon/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
    <link href="https://fonts.googleapis.com/css?family=Barlow+Condensed&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Pacifico&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>

<!-- The header for the site -->

	<header>
		<div class="headcont">
			<div id="logo">
				<label class="link" data-id="home">myDrive</label>
			</div>
			<div class="search">
				<div id="searchForm">
					<div id="searchbar">
						<input id="searchb" type="text" name="search" placeholder="Search">
					</div>
					<div id="searchButton">
						<input id="SearchBtn" type="image" src="src/loupe.svg" width="25px" alt="SearchBtn">
					</div>
				</div>
			</div>
			<div>
				<nav>
					<ul class="navlist">
						<li><label class="leave" data-id="https://www.netflix.com">Netflix</label></li>
						<li><label class="leave" data-id="https://www.disneyplus.com/en-gb/">Disney+</label></li>
						<li><label class="leave" data-id="https://www.canalplus.com/">myCanal</label></li>
						<li><label class="leave" data-id="https://plex.<?= $server; ?>">Plex</label></li>
						<li><label class="leave" data-id="https://deluge.<?= $server; ?>">Deluge</label></li>
						<li><label onclick="validate()" id="auth">Log In</label></li>
					</ul>
				</nav>	
			</div>
		</div>
	</header>

<!-- The sidebar comes next -->
	
	<div id="sidebar">
        <div id="create-folder">
            <div id="inner-Create" class="newfldr">
                <img src="src/add-folder.svg" width="30px">
                <label>Create</label>
            </div>
        </div>

		<ul class="sidenav">
            <li id="side-upload" class="he">
				<img src="src/upload.svg" width="30px">
				<label>Upload</label>
			</li>

            <li class="link" data-id="home" id="side-root">
                <img class="image" src="src/folder-2.svg" width="30px">
                <label>Home</label>
            </li>

			<li class="link" data-id="Private" id="side-Private">
				<img class="image" src="src/folder-2.svg" width="30px">
				<label>Private</label>
			</li>
			
			<li class="link" data-id="Downloads" id="side-Downloads">
				<img class="image" src="src/folder-2.svg" width="30px">
				<label>Torrents</label>
			</li>
		
			<li data-id="media" onclick="media()" id="side-media">
				<img class="image" src="src/folder-2.svg" width="30px">
				<label>Media</label>
			</li>
		</ul>

        <div id="side-divider"></div>

        <div id="disk-info">
            <img class="image" src="src/server.svg" width="25px">
            <span id="storage">Storage Used: </span>
            <span id="space-deets"> </span>
        </div>
        <div id="a-space-groove">
            <div id="a-space-filler"></div>
        </div>
    </div>

<!-- Recently opened files -->

    <div id="file-structure">
        <div id="folder-head">
            <div id="backbtn">
                <img class="link" data-id="<?= $_SESSION['root']; ?>" id="backimg" src="src/back2.svg" width="20px">
                <span id="recent-labelDiv">Recently opened files</span>
            </div>
            <label id="foldername" class="link" data-id="#">maix server</label>
        </div>

        <div id="recently-opened">
        </div>

        <!-- creating the file manager interface -->

        <div id="dropzone">
            <ul id="filelist">

            </ul>
        </div>
    </div>

<!-- The context menu div -->

    <div id="upload-selector">
        <ul id="uSselector-ul">
            <li id="use-file">Upload Files</li>
            <div class="prop-divider"></div>
            <li id="use-folder">Upload folders</li>
        </ul>
    </div>

	<div id="properties">
		<ul class="rightbar">
            <li class="newfldr">
                <label>New Folder</label>
            </li>

            <div class="prop-divider"></div>

            <li class="element" id="sharebtn">
				<label>Share</label>
			</li>

            <li class="element" id="moveprop">
                <label>Move</label>
            </li>

            <div class="prop-divider"></div>

            <li class="element" id="renameprop">
                <label>Rename</label>
            </li>

			<li class="element download">
				<label>Download</label>
			</li>

            <div class="prop-divider"></div>

            <li class="element" id="infoprop">
                <label>Information</label>
            </li>

			<li class="element" id="deleteprop">
				<label>Delete</label>
			</li>
		</ul>
	</div>

<!-- The fucking modals -->

<!-- The modals that don't require a refresh -->

<!-- The Information container -->

    <div id="information-div">
        <div id="info-head">
            <span id="IH-Span">Information</span>
        </div>
        <div id="Info-body">
            <ul id="Info-list">
                <div class="prop-divider"></div>

                <li>
                    <label id="inform-name">Name: </label>
                    <span></span>
                </li>

                <div class="prop-divider"></div>

                <li>
                    <label id="inform-locate">Location: </label>
                    <span></span>
                </li>

                <div class="prop-divider"></div>

                <li>
                    <label id="inform-time">Last Opened: </label>
                    <span></span>
                </li>

                <div class="prop-divider"></div>

                <li>
                    <label id="inform-size">Size: </label>
                    <span></span>
                </li>
            </ul>
        </div>
    </div>

<!-- The move container -->

    <div id="movecontainer">
        <div id="movehead"><img src="src/back.svg" width="20px" onclick="back()"><label>Move to...</label></div>
        <div id="movebody">
            <ul id="movelist">

            </ul>
        </div>
        <div id="movesub">
            <button id="movebutton">Move</button>
        </div>
    </div>

<!-- The upload progress-->

    <div id="uploadProgressContainer">
        <div id="upload-head">
            <label> Uploading!</label>
        </div>
        <div id="uploadGroove">
            <div id="uploadFiller"></div>
        </div>
        <div id="uploadSub">
            <label>Star Wars.m4v</label>
        </div>
    </div>

<!-- Info reporting div -->

    <div id="info-reporting">
       <div id="info-reporting-container">
           <div id="info-reporting-img">
               <img id="info-reporting-image" src="src/tick.svg" width="40px">
           </div>
           <div id="info-reporting-div-divider"></div>
           <div id="info-reporting-context">
               <label id="info-type-label">Prompt</label>
               <div id="info-reporting-label-divider"></div>
               <label id="info-reporting-message">You have logged in successfully</label>
           </div>
       </div>
    </div>

<!-- The dropzone indicator -->

    <div id="dndbox">
        <div class="dboxhead">Upload to Server</div>
        <div class="dboxbody error-label">
            <label>Drag and Drop</label>
            <br>
            <label>files to Upload</label>
        </div>
        <div class="dboxsub errorsub">
            <label>Test</label>
        </div>
    </div>

<!-- The lightbox modals -->

    <div class="bg-modal" id="uploadblock">
        <div class="dbox" id="upload">
            <div class="dboxhead">Upload to Server</div>
            <div class="dboxbody">
                <div class="box" id="uploadcapture">
                    <input type="file" id="file-4" class="inputfile" data-multiple-caption="{count} files selected" webkitdirectory multiple/>
                    <label for="file-4"><span>Choose a file</span></label>
                </div>
            </div>
            <div class="dboxsub">
                <button id="uplbtn">Upload</button>
            </div>
        </div>
    </div>

    <div class="bg-modal" id="deletemodblock">
        <div class="dbox" id="deletemod">
            <div class="dboxhead">Ready to delete?</div>
            <div class="dboxbody error-label" id="removelabel">
                <label>Change</label>
            </div>
            <div id="deletesub">
                <ul>
                    <li id="rmyes">Yes</li>
                    <li id="rmno">No</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="bg-modal" id="rendboxblock">
        <div class="dbox" id="rendbox">
            <div class="dboxhead">Rename!</div>
            <div class="dboxbody renameform" id="rename-form">
                <input type="text" name="rename" placeholder="Rename" class="renamebar" id="rename-bar">
                <input type="image" src="src/edit.svg" width="20px" class="rnmbtn" id="rename-button">
            </div>
            <div class="dboxsub errorsub">
                <label>Test</label>
            </div>
        </div>
    </div>

    <div class="bg-modal" id="createboxblock">
        <div class="dbox" id="createbox">
            <div class="dboxhead">New Folder!</div>
            <div class="dboxbody renameform" id="createform">
                <input type="text" name="rename" placeholder="Create" class="renamebar" id="create-bar">
                <input type="image" src="src/edit.svg" width="20px" class="rnmbtn" id="create-button">
            </div>
            <div class="dboxsub errorsub">
                <label>Test</label>
            </div>
        </div>
    </div>

    <div class="bg-modal" id="authdboxblock">
        <div class="dbox" id="authdbox">
            <div class="dboxhead">Authenticate!</div>
            <div class="dboxbody renameform">
                <input type="password" autocomplete="current-password" name="key" placeholder="Authenticate" class="renamebar" id="authbar">
                <input type="image" src="src/edit.svg" width="20px" class="rnmbtn" id="authbtn">
            </div>
            <div class="dboxsub errorsub">
                <label>Test</label>
            </div>
        </div>
    </div>

    <div class="bg-modal" id="videodivblock">
        <img class="video-img" src="src/back-arrow.svg" width="40px" id="back-arrow">
        <div id="videodiv"></div>
        <img class="video-img" style="transform: scaleX(-1);" src="src/back-arrow.svg" width="40px" id="fow-arrow">
    </div>

<!-- The modals that do require a refresh -->

<!-- The actionable modals -->

<?php  if (isset($_SESSION['share'])) {
    $key = array_keys($_SESSION["share"]);
    $_SESSION["download"] = $key[0];

    $values = array_values($_SESSION["share"]);
    $name = basename($key[0]);
    $value = $values[0];


    ?>

    <div id="share-modal">
        <div class="dbox">
            <div class="dboxhead" id="title">Download!</div>
            <div id="share-div" class="dboxbody error-label" onclick="download()">
                <label><?= $name ?></label>
            </div>
            <div class="dboxsub errorsub">
                <label>Test</label>
            </div>
        </div>
    </div>
<?php } unset($_SESSION['share']); ?>

	<footer>
		<div id="copyright">
            <label>Copyright © 2020 Roy Ossai.</label>
            <br>
            <div id="copyright-span">
                <span>All rights reserved. No document may be reproduced for commercial use without written approval from the author.</span>
            </div>
        </div>
	</footer>

</body>	
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="js/clipboard.min.js"></script>
<script src="js/properties.js"></script>
<script src="js/classes.js"></script>
<script src="js/dropzone.js"></script>
<script src="js/index.js"></script>
<script src="js/modals.js"></script>
<script src="js/beau.js"></script>
</html>
