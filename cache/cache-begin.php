<?php
$url = isset($_SERVER['REQUEST_URI']) ? strip_tags(htmlentities($_SERVER['REQUEST_URI'])) : null;
if (parse_url($url, PHP_URL_QUERY)) {
	$file = basename($_SERVER["SCRIPT_NAME"], '.php');
	$query = str_replace("=", "-", parse_url($url, PHP_URL_QUERY));
	$cachefile = 'cache/cached-'.$file.'-'.$query.'.html';	
} else { 
	$file = basename($_SERVER["SCRIPT_NAME"], '.php');
	$cachefile = 'cache/cached-'.$file.'.html';
}
//set app to cache file for 3 minutes
$cachetime = 180;

/* if you don't know file extension...
$url = $_SERVER["SCRIPT_NAME"];
$break = explode('/', $url);
$file = $break[count($break) - 1];
$cachefile = 'cache/cached-'.substr_replace($file ,"",-4).'.html';
$cachefile = 'cache/cached-'.$file.'.html';
$cachetime = 900;
*/

//serve from the cache if it is younger than $cachetime
//if (file_exists($cachefile) && (filemtime($cachefile) > (time() - 60 * 5 ))) {
if (file_exists($cachefile) && time() - $cachetime < filemtime($cachefile)) {
    echo "<!-- Cached copy, generated ".date('H:i', filemtime($cachefile))." -->\n";
    include($cachefile);
    exit;
}
ob_start(); // Start the output buffer
?>