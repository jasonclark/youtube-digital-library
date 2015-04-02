<?php
//begin caching routine
include 'cache/cache-begin.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Digital Library - Built with YouTube Data API version 2</title>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link rel="stylesheet" href="meta/styles/master.css" type="text/css"/>
</head>
<body>
<div id="contain">
<div id="main">
<h1 class="mainHeading" id="media"><strong>Your Digital Video Library</strong>
<?php include 'meta/inc/nav.php'; ?>
</h1>
<?php
//load default values for YouTube Data API
include 'meta/inc/config.php';
//override default value for page length of 50 (number of entries to display)
$limit = isset($_GET['limit']) ? strip_tags((int)$_GET['limit']) : '9';
//set feed URL
$feedURL = 'http://gdata.youtube.com/feeds/api/users/'.$user.'/uploads?v='.$version.'&alt='.$form.'&max-results='.$limit.'&key='.$key.'';
	
//call API and get data
$request = file_get_contents($feedURL);

if ($request === FALSE) {
	//API call failed, display message to user
	echo '<p><strong>It looks like we can\'t communicate with the API at the moment.</strong></p>'."\n";
	exit(); 		
}

//create json object(s) out of response from API; set to "true" to turn response into an array
//$result = json_decode($request,true);
$result = json_decode($request);
?>
    <ul id="listColumns">
		<li id="search">
        <form id="searchBox" method="get" action="./search.php"> 
        <fieldset> 
        <label for="q">Search</label> 
        <input type="text" maxlength="200" name="q" id="q" tabindex="1" value="" placeholder="Search..." autofocus /> 
        <button type="submit" class="button">Search</button> 
        </fieldset> 
        </form>
		</li>
		<li id="browse">
		<p>
		<a href="./list.php?order=viewCount" title="most popular videos from montana state university (msu) library">Most Popular</a> | <a href="./list.php" title="all videos from montana state university (msu) library">All</a>
		<?php
		//set dynamic browse points based on playlists created around master subjects
		$setBrowsePoints = 'https://gdata.youtube.com/feeds/api/users/'.$user.'/playlists?v='.$version.'&alt='.$form.'&key='.$key.'';
		
		//call API and get playlist titles and ids
		$requestSubjects = file_get_contents($setBrowsePoints);
		
		if ($requestSubjects === FALSE) {
			// API call failed, display message to user
			echo '<p><strong>It looks like we can\'t communicate with the API at the moment.</strong></p>'."\n";
			exit(); 		
		}

		//create json object(s) out of response from API; set to "true" to turn response into an array
		//$result = json_decode($request,true);
		$subjects = json_decode($requestSubjects);
		
		foreach ($subjects->data->items as $item) {
			$sid = $item->id;
			$title = htmlentities($item->title);
			$description = isset($item->description) ? $item->description : 'No description available.';
		?>
		| <a href="./list.php?subject=<?php echo $title; ?>&sid=<?php echo $sid; ?>" title="<?php echo $description; ?>"><?php echo $title; ?></a> 
		<?php
		}
		?>
		</p>
		</li>
    </ul> 
  <h2 class="mainHeading">Featured Videos</h2>
  <ul class="grid">
<?php
//parse returned data elements from api call and display as html
foreach ($result->data->items as $item) {
	$title = htmlentities($item->title);
	$id = $item->id;
	//get published date
	$timestamp = strtotime($item->uploaded);
	$uploaded = date('M j, Y', $timestamp);
	$watch = $item->player->default;
	$image = $item->thumbnail->hqDefault;
	//get video description
	$description = isset($item->description) ? $item->description : 'No description available.';
	$duration = gmdate('H:i:s', intval($item->duration));
?>
	<li>
		<a title="<?php echo $description; ?>" href="./item.php?id=<?php echo $id; ?>"><img width="240" height="160" src="<?php echo $image; ?>" alt="<?php echo $description; ?>" /><span><?php echo $duration ?></span></a>
        <p><a title="<?php echo $description; ?>" href="<?php echo './item.php?id='.$id.''; ?>"><?php echo $title; ?></a></p>
	</li>    
<?php
}
?>
</ul><!-- end <ul> boxes --> 
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
