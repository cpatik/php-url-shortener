<?php

require 'config.php';

$url = isset($_GET['url']) ? urldecode(trim($_GET['url'])) : '';
$isBookmarklet = isset($_GET['bm']) ? true : false;
$customString = isset($_GET['custom']) ? urldecode(trim($_GET['custom'])) : '';
$longurl = $url;  # Keep track of original URL so we can display it for reference
$response = "";   # Either the shortened URL or an error message to be displayed
$success = true; # Whether or not a short URL was created/retrieved

if (in_array($url, array('', 'about:blank', 'undefined', 'http://localhost/'))) {
	$response = "Enter a URL";
	$success = false;
}

function nextLetter(&$str) {
	$str = ('z' === $str ? 'a' : ++$str);
}

function getNextShortURL($s) {
	$a = str_split($s);
	$c = count($a);
	if (preg_match('/^z*$/', $s)) { // string consists entirely of `z`
		return str_repeat('a', $c + 1);
	}
	while ('z' === $a[--$c]) {
		nextLetter($a[$c]);
	}
	nextLetter($a[$c]);
	return implode($a);
}

if ($success) {
	$db = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE);
	$db->set_charset('utf8');

	$url = $db->real_escape_string($url);

	$result = $db->query('SELECT slug FROM redirect WHERE url = "' . $url . '" LIMIT 1');
	if ($result && $result->num_rows > 0) { // If there’s already a short URL for this URL
		$response = SHORT_URL . $result->fetch_object()->slug;
		$success = true;
	}
	else {
		# Try to use custom string if available
		if ($customString) {
			$result = $db->query("SELECT slug, url FROM redirect WHERE slug='$customString'");
			if ($result && $result->num_rows > 0) {
				$slug = $customString;
			}
		}
		# Get new slug
		else {
			$result = $db->query('SELECT slug, url FROM redirect ORDER BY date DESC LIMIT 1');
			if ($result && $result->num_rows > 0) {
				$slug = getNextShortURL($result->fetch_object()->slug);
			}
		}
		if ($db->query('INSERT INTO redirect (slug, url, date, hits) VALUES ("' . $slug . '", "' . $url . '", NOW(), 0)')) {
			$response = SHORT_URL . $slug;
			$success = true;
			header('HTTP/1.1 201 Created');
			$db->query('OPTIMIZE TABLE `redirect`');
		}
	}
}
# Display result

if ($isBookmarklet) {
	# Bookmarklets get plain text
	header('Content-Type: text/plain;charset=UTF-8');
	echo $response;
}
else {
	# Non-bookmarklets (i.e., direct navigation) get a nicer HTML page
	header('Content-Type: text/html;charset=UTF-8');
	?>
	<!doctype html>
	<html>
	<head>
		<title>crgp.tk URL Shortener</title>
		<!--[if lt IE 9]><script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
		<link href="style.css" rel="stylesheet">
	</head>
	<body>
	<?php
	# Short URL worked
	if ($success) {
		?>
		<section>
			<h1>Short URL</h1>
			<p><input id="urlInput" type="url" size="<?php echo (strlen($response) + 3); ?>" value="<?php echo $response; ?>"></p>
			<p class="orig">Shortened URL: <a href="<?php echo $response; ?>"><?php echo str_replace('http://', '', $response); ?></a></p>
			<p class="orig">Original URL: <a href="<?php echo $longurl; ?>"><?php echo $longurl; ?></a></p>
		</section>
		<section class="bm">
			<h1>Bookmarklets</h1>
			<p>Drag these to your bookmarks to shorten other URLs</p>
			<section>
				<p>Prompt</p>
				<p><a href="javascript:(function(){var%20q=prompt('URL:');if(q){document.location='http://crgp.tk/shorten?bm=t&amp;url='+encodeURIComponent(q)}}());">Shorten a URL</a></p>
			</section>
			<section>
				<p>Use current page</p>
				<p><a href="javascript:(function(){document.location='http://crgp.tk/shorten?bm=t&amp;url='+encodeURIComponent(location.href)}());">Shorten this URL</a></p>
			</section>
		</section>
		<?php
	}
	# Error message
	else {
		?>
		<section>
			<p><?php echo $response; ?></p>
		</section>
		<section>
			<form action="shorten.php" method="GET">
				<label>URL: <input type="url" id="url" value=""></label>
				<label>Custom: <input type="text" id="custom" value="" placeholder="(Optional)"></label>
				<br>
				<input type="submit" value="Shorten">
			</form>
		</section>
		<?php
	}
	?>
		<script>
		function setFocus() {
			var input = document.getElementById("urlInput");
			input.focus();
			try { input.select(); } catch(e) { } <?php /* Sometimes fails in some browsers */ ?>
		}
		if (window.addEventListener) {
			window.addEventListener("load", setFocus, false);
		}
		else if (window.attachEvent) { // Le sigh.
			window.attachEvent("onload", setFocus);
		}
		</script>
	</body>
	</html>
	<?php
}
?>
