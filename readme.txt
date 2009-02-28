=== FishyTweet ===
Contributors: fiskeben
Tags: Twitter, microblogging
Requires at least: 2.3
Tested up to: 2.7.1
Stable tag: 1.7

Adds a "Tweet this" link to posts, shortens URLs with tr.im or TinyURL and sends the user to Twitter.com.

== Description ==

Adds a link to each post that points the user to Twitter.com. Before the redirect, the URL for the actual post is sent to
Tinyurl.com and the tinyurl-ified URL is used as an argument to Twitter.com thus putting it in the status field when the
page loads.

== Installation ==

Upload the folder to your plugins folder and activate it. See "Settings" to customize it.

= Configuration =

Under "Settings" you can change the following options:
* The anchor text. This is the text that will make up the link that takes users to Twitter.com.
* The title attribute of the anchor. This is what will go into the tool tip of the link.
* Surrounding tag: Select a proper tag to put the link into.
* Surrounding tag class: If you want to add CSS rules for the link you can specify one or more class names. Use spaces to separate multiple class names.
* URL shortener: Select your preferred engine for shortening URLs.

== Frequently Asked Questions ==

= Are there any special requirements to use this plugin? =

Yes, PHP on the server you install the plugin on must have enabled the setting allow_url_fopen. If not, the plugin cannot shorten the URL.

= What if I'm not logged in at Twitter.com =

If you aren't logged in when you click the "tweet" link, it's not a problem. First, you'll be redirected to the login page at Twitter.com. Once you've logged in, you are redirected to your home page at Twitter.com and the URL will be ready for you in the status field.

= Can I use an image as a link? =

Unfortunately no. This will be available in a later version.

= I get an error about 'allow_url_fopen'? =

If you get this error it means that PHP on the server you use isn't allowed to access other web pages using file_get_contents(). Unfortunately, this is the way FishyTweet connects to the selected URL shortener to shorten the URL to your post. The plugin will still work but URLs won't be shortened.