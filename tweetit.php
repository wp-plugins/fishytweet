<?php
/*
Tinyurl-ifies a URL and redirects to Twitter.com.
*/
$url = $_GET['url'];
if (strlen($url)) {
	include ("../../../wp-blog-header.php");
	$options = $wpdb->get_var("select option_value from $wpdb->options where option_name = 'fishytweetoptions'");
	if ($options) {
		$options = unserialize($options);
		$tinyurl = file_get_contents($options['url_engine'] . $url);
		header("Location: http://twitter.com/home?status=" . $tinyurl);
	}
} else {
	header("Location: " . $_SERVER['REFERER']);
}
?>