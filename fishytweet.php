<?php
/*
Plugin Name: Fishy Tweet
Plugin URI: http://fiskeben.dk/fishytweet
Description: Adds "Tweet this" to posts.
Version: 1.5
Author: Ricco Førgaard <ricco@fiskeben.dk>
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
		'anchor' => 'Tweet this!',
		'surround_tag' => 'p',
		'surround_tag_class' => 'fishytweet',
		'url_engine' => 'http://tinyurl.com/api-create.php?url='
		);
	var $tags = array(null, 'p', 'div', 'span', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6');
	var $engines = array(
		'TinyURL' => 'http://tinyurl.com/api-create.php?url=',
		'tr.im' => 'http://tr.im/api/trim_simple?url='
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
			$this->options['title'] = trim($_POST['fishytweet_title']);
		if (isset($_POST['fishytweet_anchor']))
			$this->options['anchor'] = trim($_POST['fishytweet_anchor']);
		if (isset($_POST['fishytweet_surround_tag']))
			$this->options['surround_tag'] = $_POST['fishytweet_surround_tag'];
		if (isset($_POST['fishytweet_tag_class']))
			$this->options['surround_tag_class'] = trim($_POST['fishytweet_tag_class']);
		if (isset($_POST['fishytweet_url_engine']))
			$this->options['url_engine'] = $_POST['fishytweet_url_engine'];
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
			<h3><?php _e('Surrounding tag', 'FishyTweet'); ?></h3>
			<p><?php _e("Select a tag to surround the tweet link in", "FishyTweet")?>.</p>
			<select name="fishytweet_surround_tag">
				<?php
				foreach ($this->tags as $tag) {
					$tagtext = $tag;
					if ($tag == null)
						$tagtext = "-- " . __('None', 'FishyTweet') . " --";
					echo "<option value=\"$tag\"";
					if ($tag == $this->options['surround_tag'])
						echo " selected=\"selected\"";
					echo ">$tagtext</option>\n";
				}
				?>
			</select>
			<h3>Surrounding tag class</h3>
			<p><?php _e("If necessary, put the class names for the surrounding tag here", "FishyTweet"); ?>.</p>
			<input type="text"  name="fishytweet_tag_class" value="<?php echo $this->options['surround_tag_class']; ?>"/>
			<h3>URL tiny-ifier</h3>
			<p><?php _e("Select your preferred URL shortener", "FishyTweet"); ?>.</p>
			<select name="fishytweet_url_engine">
				<?php
				foreach ($this->engines as $engine => $url) {
					echo "<option value=\"$url\"";
					if ($url == $this->options['url_engine'])
						echo " selected=\"selected\"";
					echo ">$engine</option>\n";
				}
				?>
			</select>
			<div class="submit">
				<input type="submit" name="update_fishytweet" value="<?php _e("Update Settings", "FishyTweet"); ?>" />
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
		
		$start_tag = "";
		$end_tag = "";
		if ($this->options['surround_tag']) {
			$start_tag = "<" . $this->options['surround_tag'];
			if ($this->options['surround_tag_class'])
				$start_tag .= " class=\"" . $this->options['surround_tag_class'] . "\"";
			$start_tag .= ">";
			$end_tag = "</" . $this->options['surround_tag'] . ">";
		}
		$html = $start_tag . "<a href=\"$out_url\" title=\"" . $this->options['title'] . "\">" . $this->options['anchor'] . "</a>" . $end_tag;
		
		return $text . $html;
	}
}
?>