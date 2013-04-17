<?php
/*Master values and parameters for app are set here. 
Based on https://developers.google.com/youtube/2.0/developers_guide_protocol*/ 

//set default value for developer key
$key = isset($_GET['key']) ? $_GET['key'] : 'AI39si42I3ISyPvJOqooURFnY79c6NxDYWZFi3Y6rlI6LwoMMsm5uqtnSDsa-htnzN7QyzMktlLcJe8mMQGhgQz34zt6ROzSiw';
//set default value for user, page will show this user's videos
$user = isset($_GET['user']) ? strip_tags(htmlentities($_GET['user'])) : 'msulibrary';
//set default value for API version
$version = isset($_GET['v']) ? strip_tags((int)$_GET['v']) : '2';
//set default value for video format
$format = isset($_GET['format']) ? strip_tags(htmlentities($_GET['format'])) : '1,5,6';
//set default value for API format
$form = isset($_GET['form']) ? strip_tags(htmlentities($_GET['form'])) : 'jsonc';
//set default value for page start index
$start = isset($_GET['start']) ? strip_tags((int)$_GET['start']) : '1';
//set default value for page length (number of entries to display)
$limit = isset($_GET['limit']) ? strip_tags((int)$_GET['limit']) : '50';
//set default value for tag
//$tag = isset($_GET['tag']) ? strip_tags(htmlentities($_GET['tag'])) : null;
//set default value for ordering results
$order = isset($_GET['order']) ? strip_tags(htmlentities($_GET['order'])) : 'published';
//set default value for subject (based on playlist title)
$subject = isset($_GET['subject']) ? strip_tags(htmlentities($_GET['subject'])) : null;
//set default value for subject id (based on playlist id)
$sid = isset($_GET['sid']) ? strip_tags(htmlentities($_GET['sid'])) : null;
?>