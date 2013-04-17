<?php
//begin caching routine
//include 'cache/cache-begin.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Search, Digital Library - Built with YouTube Data API version 2</title>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link rel="stylesheet" href="meta/styles/master.css" type="text/css"/>
</head>
<body>
<div id="contain">
<div id="main">
<div class="gutter"><a name="mainContent"></a>
	<h1 class="mainHeading" id="media">Search Videos (Your Digital Library)
	<?php include 'meta/inc/nav.php'; ?>
	</h1>
<?php
//declare variables to be used in query and display
$params = (count($_POST)) ? $_POST : $_GET;
//set query variable, trim whitespaces/tabs, strip html tags
$q = trim(strip_tags(urlencode($params['q'])));
$link = '<p><a class="refresh" href="./index.php">Try another search?</a></p>'."\n";
//create message if search form was empty 
if (!$q) {
	echo '<h3 class="warn">No search term given.</h3>'."\n";
	echo $link;
	exit;
}
//load default values for YouTube Data API
include 'meta/inc/config.php';
//override default value for page length (number of entries to display)
$limit = isset($_GET['limit']) ? strip_tags((int)$_GET['limit']) : '30';
//override default value for ordering results
$order = isset($_GET['order']) ? strip_tags(htmlentities($_GET['order'])) : 'relevance';
//set default value for query
$query = isset($q) ? $q : 'video';

//set feed URL
$feedURL = 'http://gdata.youtube.com/feeds/api/videos?v='.$version.'&format='.$format.'&author='.$user.'&alt='.$form.'&max-results='.$limit.'&orderby='.$order.'&q='.$query.'&key='.$key.'';
//echo $feedURL;

//call API and get data
$request = file_get_contents($feedURL);

//create json object(s) out of response from API; set to "true" to turn response into an array
//$result = json_decode($request,true);
$result = json_decode($request);

//get values in json data for number of search results returned
$totalItems = $result->data->totalItems;
?>
<h2 class="mainHeading">Your search for <strong><?php echo urldecode($query); ?></strong> resulted in <?php echo $totalItems; ?> match(es).</h2>
<?php
//check for empty results, display message to user
if ($totalItems == 0) {
	echo '<h3 class="warn">Sorry, no matching results.</h3>'."\n";
	echo $link;
	exit; 
}
//parse returned data elements from api call and display as html
foreach ($result->data->items as $item) {
	$title = htmlentities($item->title);
	$id = $item->id;
	//get published date
	$timestamp = strtotime($item->uploaded);
	$uploaded = date('M j, Y', $timestamp);
	$watch = $item->player->default;
	$image = $item->thumbnail->sqDefault;
	//get video description
	$description = isset($item->description) ? $item->description : 'No description available.';
	$viewCount = $item->viewCount;
	$duration = gmdate('H:i:s', intval($item->duration));
?>
<ul class="object">
				<li class="play">
					<a title="<?php echo $description; ?>" href="./item.php?id=<?php echo $id; ?>"><img width="120" height="90" src="<?php echo $image; ?>" alt="<?php echo $description; ?>" /></a>
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
echo $link;
?>
</div><!-- end main div -->
<div id="footer">
<p>Copyright &#169; 2013 <a href="http://jasonclark.info">Jason A. Clark</a>. Code covered by the <a rel="license" href="http://opensource.org/licenses/MIT">MIT license</a>.</p>
</div><!-- end footer div -->
</div><!-- end contain div -->
</body>
</html>
<?php
//end caching routine
//include('cache/cache-end.php');
?>