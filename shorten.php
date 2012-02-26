<?php

require 'config.php';

$url = isset($_GET['url']) ? urldecode(trim($_GET['url'])) : '';
$isBookmarklet = (isset($_GET['bm']) && strlen($_GET['bm']) > 0) ? true : false;
$longurl = $url;
$response = "";
$success = false;

if (in_array($url, array('', 'about:blank', 'undefined', 'http://localhost/'))) {
	$response = "Enter a URL.";
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

$db = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE);
$db->set_charset('utf8');

$url = $db->real_escape_string($url);

$result = $db->query('SELECT slug FROM redirect WHERE url = "' . $url . '" LIMIT 1');
if ($result && $result->num_rows > 0) { // If there’s already a short URL for this URL
	$response = SHORT_URL . $result->fetch_object()->slug;
	$success = true;
}
else {
	$result = $db->query('SELECT slug, url FROM redirect ORDER BY date DESC LIMIT 1');
	if ($result && $result->num_rows > 0) {
		$slug = getNextShortURL($result->fetch_object()->slug);
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
	header('Content-Type: text/plain;charset=UTF-8');
	echo $response;
}
else {
	header('Content-Type: text/html;charset=UTF-8');
	?>
	<!doctype html>
	<html>
	<head>
		<title>crgp.tk URL Shortener</title>
		<style>
		body {
			font: 1.2em/2em "Myriad Pro", "Helvetica Neue", Helvetica, "Segoe UI", sans-serif;
			color: #444;
			max-width: 32em;
			margin: 1em auto;
			background-color: #f4f4f4;
		}
		input, a {
			font: 1em/1.8em Menlo, Consolas, "Courier New", monospace;
		}
		input {
			color: #444;
			margin-left: 0.5em;
		}
		a {
			color: #0086B3;
			border-bottom: 1px solid transparent;
			text-decoration: none;
			-webkit-transition: color 0.2s ease, border-bottom-color 0.2s ease;
			   -moz-transition: color 0.2s ease, border-bottom-color 0.2s ease;
			    -ms-transition: color 0.2s ease, border-bottom-color 0.2s ease;
			     -o-transition: color 0.2s ease, border-bottom-color 0.2s ease;
			        transition: color 0.2s ease, border-bottom-color 0.2s ease;
		}
		a:hover,
		a:focus {
			color: #00B9F7;
			border-bottom: 1px solid #00B9F7;
		}
		.orig {
			font-size: smaller;
		}
		.orig, .orig a {
			color: #999;
		}
		</style>
	</head>
	<body>
	<?php
	if ($success) {
		?>
		<p>Short URL: <a href="<?php echo $response; ?>"><?php echo $response; ?></a></p>
		<p>Copy: <input id="urlInput" type="url" size="<?php echo (strlen($response) + 3); ?>" value="<?php echo $response; ?>"></p>
		<p class="orig">Original URL: <a href="<?php echo $longurl; ?>"><?php echo $longurl; ?></a></p>
		<?php
	}
	else {
		?>
		<p><?php echo $response; ?></p>
		<?php
	}
	?>
		<script>
		function setFocus() {
			var input = document.getElementById("urlInput");
			input.focus();
			try { input.select(); } catch(e) { }
		}
		if (window.addEventListener) {
			window.addEventListener("load", setFocus, false);
		}
		else if (window.attachEvent) {
			window.attachEvent("onload", setFocus);
		}
		</script>
	</body>
	</html>
	<?php
}
?>
