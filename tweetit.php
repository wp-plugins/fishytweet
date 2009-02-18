<?php
/*
Tinyurl-ifies a URL and redirects to Twitter.com.
*/
if (strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) === false) {
	die("Please only call this script from the same domain (" . $_SERVER['HTTP_REFERER'] . ", " . $_SERVER['HTTP_HOST'] . ")");
}
$url = $_GET['url'];
if (strlen($url)) {
	include ("../../../wp-blog-header.php");
	$options = $wpdb->get_var("select option_value from $wpdb->options where option_name = 'fishytweetoptions'");
	if ($options) {
		$options = unserialize($options);
		$tinyurl = file_get_contents($options['url_engine'] . $url);
		if (is_array($http_response_header, $options, $url)) {
			if (substr($http_response_header[0], '200') === false) {
				fishytweet_fail($http_response_header, $options['url_engine'], $url);
			}
		}
		header("Location: http://twitter.com/home?status=" . $tinyurl);
	}
} else {
	header("Location: " . $_SERVER['HTTP_REFERER']);
}

function fishytweet_fail($headers, $engine, $url) {
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
		"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
	<html>
		<head>
			<title>FishyTweet Error</title>
		<body>
			<h1>Error shortening URL</h1>
			<p>Unfortunately, an unexpected error occurred while shortening your URL.</p>
			<p>The server returned the following response: <?php echo $headers[0]; ?>.</p>
			<p>Please use the Back button in your browser to return to the blog and perhaps try again.</p>
			<p>If the problem persists then you could contact the
				<a href="http://wordpress.org/extend/plugins/fishytweet/" title="Click here to go to the plugin home page">plugin developer</a>
				and file a bug report.</p>
			<p>
				<small>
					If you do so, please include the following information:
					<ul>
						<li>URL: <?php echo $url; ?></li>
						<li>URL shortener: <?php echo $engine; ?></li>
						<li>Headers:
							<ul>
								<?php
								foreach ($headers as $h)
									echo "<li>$h</li>\n";
								?>
							</ul>
						</li>
					</ul>
				</small>
			</p>
		</body>
	</html>
	<?php
	die();
}
?>