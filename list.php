<?php
//begin caching routine
include 'cache/cache-begin.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>List View, Digital Library - Built with YouTube Data API version 2</title>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link rel="stylesheet" href="meta/styles/master.css" type="text/css"/>
</head>
<body>
<div id="contain">
<div id="main">
<?php
//load default values for YouTube Data API
include 'meta/inc/config.php';

//set feed URL
if (is_null($subject)) {
	$feedURL = 'http://gdata.youtube.com/feeds/api/users/'.$user.'/uploads?v='.$version.'&alt='.$form.'&start-index='.$start.'&max-results='.$limit.'&orderby='.$order.'&key='.$key.'';
} else {
	$feedURL = 'http://gdata.youtube.com/feeds/api/playlists/'.$sid.'?v='.$version.'&alt='.$form.'&start-index='.$start.'&max-results='.$limit.'&orderby='.$order.'&key='.$key.'';
}
	
//call API and get data
$request = file_get_contents($feedURL);

//create json object(s) out of response from API
$result = json_decode($request);

//get total results to display count
$totalResults = $result->data->totalItems;
//assign value for title of page
$pageHeading = is_null($subject) ? 'All Videos (Your Digital Library) - '.$totalResults.' videos' : $subject.' (library channel) - '.$totalResults.' Videos';
?>
<h1 class="mainHeading" id="media"><?php echo($pageHeading); ?>
<?php include 'meta/inc/nav.php'; ?>
</h1>
<?php
//parse returned data elements from api call and display as html
foreach ($result->data->items as $item) {
	$title = is_null($subject) ? htmlentities($item->title) : htmlentities($item->video->title);
	$id = is_null($subject) ? $item->id : $item->video->id;
	//get published date
	$timestamp = is_null($subject) ? strtotime($item->uploaded) : strtotime($item->video->uploaded);
	$uploaded = date('M j, Y', $timestamp);
	$watch = is_null($subject) ? $item->player->default : $item->video->player->default;
	$image = is_null($subject) ? $item->thumbnail->sqDefault : $item->video->thumbnail->sqDefault;
	//get video description
	$description = is_null($subject) ? $item->description : $item->video->description;	
	$viewCount = is_null($subject) ? $item->viewCount : $item->video->viewCount;
	$duration = is_null($subject) ? gmdate('H:i:s', intval($item->duration)) : gmdate('H:i:s', intval($item->video->duration));
?>
<ul class="object">
	<li class="play">
		<a title="<?php echo $description; ?>" href="./item.php?id=<?php echo $id; ?>"><img width="240" height="160" src="<?php echo $image; ?>" alt="<?php echo $description; ?>" /></a>
	</li>
	<li class="info">
		<p><a title="<?php echo $title; ?>" href="<?php echo './item.php?id='.$id.''; ?>"><?php echo $title; ?></a></p>
		<p><?php echo $description; ?></p>
		
	</li>
	<li class="social">
		<p>View(s): <?php echo $viewCount; ?></p>
		<p>Uploaded: <?php echo $uploaded; ?></p>
		<p>Duration: <?php echo $duration ?></p>
	</li>
</ul>     
<?php
}
?>
<?php
if ($start == 1 && $totalResults > 50) { 
$next = $start + 50;
?>
<ul class="pages">
	<li>
	<a href="<?php echo strip_tags(htmlentities(basename(__FILE__))); ?>?start=<?php echo $next; ?>">View next 50</a>
	</li>
</ul>
<?php } elseif ( $start > 1 && $totalResults > 50) {
$previous = $start - 50;
?>
<ul class="pages">
	<li>
	<a href="<?php echo strip_tags(htmlentities(basename(__FILE__))); ?>?start=<?php echo $previous; ?>">View previous 50</a>
	</li>
</ul>
<?php 
} 
?>
<p><a href="./index.php">Try another search?</a></p>
</div><!-- end main div -->
<div id="footer">
<p>Copyright &#169; 2013 <a href="http://jasonclark.info">Jason A. Clark</a>. Code covered by the <a rel="license" href="http://opensource.org/licenses/MIT">MIT license</a>.</p>
</div><!-- end footer div -->
</div><!-- end contain div -->
</body>
</html>
<?php
//end caching routine
include 'cache/cache-end.php';
?>