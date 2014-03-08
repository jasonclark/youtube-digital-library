<?php
//begin caching routine
include 'cache/cache-begin.php';
//get video ID from url 
if (!isset($_GET['id'])) { 
	echo '<h2 class="warn">ERROR: Missing video ID</h2>'."\n";
	echo '<p><a href="./index.php">Try another search?</a></p>'."\n";
	exit;  
} else {
	//set default value for id, page will show this default videos if video id is missing
	$id = isset($_GET['id']) ? strip_tags(htmlentities($_GET['id'])) : 'oTCxgtod5xQ';	
}
//load default values for YouTube Data API
include 'meta/inc/config.php';
//set video data feed URL
$feedURL = 'http://gdata.youtube.com/feeds/api/videos/'.$id.'?v='.$version.'&alt='.$form.'&key='.$key.'';
//echo $feedURL;

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

//parse returned data elements from api call and display as html
$item = $result->data;

	$id = $item->id;
	$title = htmlentities($item->title);
	$user = $item->uploader;
	//get video category
	$category = isset($item->category) ? $item->category : 'No category available.';
	//get video description
	$description = isset($item->description) ? $item->description : 'No description available.';
	$image = $item->thumbnail->hqDefault;
	//get published date
	$timestamp = strtotime($item->uploaded);
	$uploaded = date('M j, Y', $timestamp);
	$watch = $item->player->default;
	$duration = gmdate('H:i:s', intval($item->duration));
	$aspectRatio = isset($item->aspectRatio) ? $item->aspectRatio : 'Not available.';
	$viewCount = $item->viewCount;
	$favoriteCount = $item->favoriteCount;
	$commentCount = $item->commentCount;
	
//assign value for title of page
$pageTitle = $title.', Digital Library: Browse Individual Video';

?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $pageTitle; ?></title>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link rel="stylesheet" href="meta/styles/master.css" type="text/css"/>
</head>
<body>
<div id="contain">
<div id="main" itemscope itemtype="http://schema.org/ItemPage">
<h1 class="mainHeading" id="media"><strong>Your Digital Video Library, Browse Individual Video</strong>
<?php include 'meta/inc/nav.php'; ?>
</h1>
			<ul class="item" vocab="http://schema.org/" itemprop="about" itemscope="itemscope" itemtype="http://schema.org/VideoObject" typeof="VideoObject">
				<li class="item-play">
					<iframe itemprop="video" width="480" height="360" 
    					src="http://www.youtube.com/embed/<?php echo $id; ?>?hd=1&modestbranding=1&version=3&autohide=1&showinfo=0&rel=0" 
       					 frameborder="0" 
        				allowfullscreen>
					</iframe>
				</li>
				<li class="item-info">
					<p><span itemprop="name" property="name"><?php echo $title; ?></span></p>
					<p><span itemprop="description" property="description"><?php echo $description; ?></span></p>
					<meta itemprop="thumbnail" content="<?php echo $image; ?>" />
					<!--<p>Tags: <?php //echo $keywords; ?></p>-->
					<!--<p>Category: <?php //echo $category; ?></p>-->
                    <p>Duration: <span itemprop="duration"><?php echo $duration; ?></span></p>
                    <p>Aspect Ratio: <span itemprop="videoFrameSize"><?php echo $aspectRatio; ?></span></p>                
                    <p>Uploaded: <span itemprop="datePublished" property="datePublished"><?php echo $uploaded; ?></span></p>
                    <p>View(s): <span itemprop="interactionCount"><?php echo $viewCount; ?></span> | Favorite(s): <span itemprop="aggregateRating"><?php echo $favoriteCount; ?></span></p>
                </li>
				<li class="item-social">
                    <p><a class="permalink" property="url" title="permalink for <?php echo $title; ?>"  itemprop="url" href="<?php echo 'http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['PHP_SELF']); ?>/item/<?php echo $id; ?>">Persistent link</a>
                    </p>
                    <p>
                    <!-- AddThis Button BEGIN -->
                    <script type="text/javascript">var addthis_pub = "jaclark"; var addthis_options = "favorites,delicious,twitter,facebook,myspace,google,yahoobkm,friendfeed,more";var addthis_offset_top = -15;addthis_caption_share="Bookmark and share";</script><a class="share" href="http://www.addthis.com/bookmark.php" onClick="return addthis_open(this, '', '[URL]', '[TITLE]')" onMouseOut="addthis_close()">Bookmark and share</a><script type="text/javascript" src="http://s7.addthis.com/js/152/addthis_widget.js"></script>
                    <!-- AddThis Button END -->
                    </p>
					<p><a class="watch" itemprop="discussionUrl" title="link to <?php echo $title; ?>" href="<?php echo $watch; ?>">Watch item on YouTube</a></p>
                    <p><a class="watch" rel="nofollow" itemprop="contentUrl" title="download <?php echo $title; ?>" href="<?php echo 'http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['PHP_SELF']); ?>/objects/<?php echo $id; ?>.mp4" download="<?php echo 'http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['PHP_SELF']); ?>/objects/<?php echo $id; ?>.mp4">Download</a></p>
 					<p>
					<form action="" name="embedForm" id="embedForm">
						<label class="embed" for="embed">Embed this item</label>
						<input id="embed" name="embed" type="text" onClick="this.select();" onFocus="this.select();" readonly="readonly" value="&lt;iframe width=&quot;480&quot; height=&quot;360&quot; src=&quot;http://www.youtube.com/embed/<?php echo $id; ?>?hd=1&amp;modestbranding=1&amp;version=3&amp;autohide=1&amp;showinfo=0&amp;rel=0&quot; frameborder=&quot;0&quot; allowfullscreen&gt;&lt;/iframe&gt;" />
					</form>
					</p>

				</li>
			</ul>
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