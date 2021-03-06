<?php

$handler = false;
include "sbnr/config.php";
include "sbnr/security.php";
putenv('GDFONTPATH=' . realpath('.') . '/sbnr/captcha_fonts/');
include "sbnr/captcha.php";
include "sbnr/utils.php";
include "sbnr/pre.php";

//START OF PAGE LOADER
$page = noHTML($_GET["page"]);
if(strlen($page) == 0) { //default to home page if not empty
	$page = "home";
	if($SBNR_GEN_ONE_PAGE) { $page = "home-1p"; }
}
if(checkString($page, 1, 32, 0, 0, 0)) { //validate string to prevent accessing out of self resources
	$page = str_replace("&lowbar;", "_", $page);
	$pageRaw = $page;
	$page = "pages/" . $page . ".html";
	if(file_exists($page)) { //check if page exists
		$pageReal = $page;
		$pageIsHome = ($page === "pages/home.html" || $page === "pages/home-1p.html");
	} else { //doesn't exist
		$pageContents = genErrorPage(404);
	}
} else { //invalidate request
	$pageContents = genErrorPage(400);
}

//END OF PAGE LOADER

//START OF PAGE
include "fragments/header.html";
if(isset($pageReal)) { //load the page if exists
	include $pageReal;
} else { //page doesn't exist, show internally generated page
	print($pageContents);
}
include "fragments/footer.html";
//END OF PAGE

include "sbnr/post.php";
print("<!-- Server Side Rendering Time: " . (microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]) . "s -->");

?>
