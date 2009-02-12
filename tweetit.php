<?php
/*
Tinyurl-ifies a URL and redirects to Twitter.com.
*/
$url = $_GET['url'];
if (strlen($url)) {
	$tinyurl = file_get_contents("http://tinyurl.com/api-create.php?url=" . $url);
	header("Location: http://twitter.com/home?status=" . $tinyurl);
} else {
	header("Location: " . $_SERVER['REFERER']);
}
?>