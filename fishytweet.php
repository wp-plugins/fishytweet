<?php
/*
Plugin Name: Fishy Tweet
Plugin URI: http://fiskeben.dk/
Description: Adds "Tweet this" to posts.
Version: 0.1
Author: Ricco Førgaard
Author URI: http://fiskeben.dk
*/
if (!function_exists('fishy_twitter_admin_setup')) {
	function fishy_twitter_admin_setup() {
		global $fishy_twitter;
		if (!isset($fishy_twitter))
			return;
		if (function_exists('add_options_page'))
			add_options_page('FishyTweet Settings', 'FishyTweet', 9, basename(__FILE__), array(&$fishy_twitter, 'admin_menu'));
	}
}
$fishy_twitter = new FishyTweet();
add_filter('the_content',  array(&$fishy_twitter, 'get_tweet_code'),  10,  1);
add_filter('fishytweet/fishytweet.php', array(&$fishy_twitter, 'init'));
add_action('admin_menu', 'fishy_twitter_admin_setup');

class FishyTweet {
	var $base_path = "/wp-content/plugins/fishytweet/tweetit.php";
	var $options = array(
		'title' => "Click here to tweet about this post",
		'anchor' => 'Tweet this!'
		);
		
	function init() {
		$this->get_admin_options();
	}
	
	function get_admin_options() {
		$stored_options = get_option('fishytweetoptions');
		if (!empty($stored_options)) {
			foreach ($stored_options as $key => $val)
				$this->options[$key] = $val;
		}
		update_option('fishytweetoptions', $this->options);
		return $this->options;
	}
	
	function admin_menu() {
		$this->get_admin_options();
		
		if (isset($_POST['fishytweet_title']))
			$this->options['title'] = $_POST['fishytweet_title'];
		if (isset($_POST['fishytweet_anchor']))
			$this->options['anchor'] = $_POST['fishytweet_anchor'];
		update_option('fishytweetoptions', $this->options);
		
		?>
		<div class="wrap">
		<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
		<h2><?php _e("FishyTweet Options", "FishyTweet"); ?></h2>
		<h3><?php _e("Anchor Text", "FishyTweet"); ?></h3>
		<p><?php _e("This text will make up the link", "FishyTweet"); ?>.</p>
		<input type="text" name="fishytweet_anchor" value="<?php echo $this->options['anchor']; ?>" />
		<h3><?php _e("Title text", "FishyTweet"); ?></h3>
		<p><?php _e("This text will pop up when someone hovers his mouse over the link", "FishyTweet"); ?>.</p>
		<input type="text" name="fishytweet_title" value="<?php echo $this->options['title']; ?>" />
		<div class="submit">
			<input type="submit" name="update_fishytweet" value="<?php _e('Update Settings', 'FishyTweet') ?>" />
		</div>
		</form>
		 </div>
		
		<?
	}
	
	function get_tweet_code($text = '') {
		$this->get_admin_options();
		global $post;
		$out_url = get_bloginfo("url");
		$out_url .= $this->base_path;
		$out_url .= "?url=" . get_permalink($post->ID);
		
		$html = "<p class=\"fishytweet\"><a href=\"$out_url\" title=\"" . $this->options['title'] . "\">" . $this->options['anchor'] . "</a></p>";
		
		return $text . $html;
	}
}
?>