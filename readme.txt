=== Social Profile ===
Contributors: avothemes
Tags: twitter, profile, social media, twitter profile, user profile
Requires at least: 4.1
Tested up to: 4.7.3
Stable tag: 1.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display beautiful Twitter profiles using shortcode.

== Description ==

The plugin allows you to display beautiful Twitter profiles by using shortcode.

You just need to provide Twitter username(s) and the plugin will make the rest.

The plugin utilizes Twitter API to get the following data:

* Name
* Profile image
* Bio
* Followers count
* Following count
* Tweets count

All results are being cached for 4-8 hours.

The Plugin uses Twitter API library which is [available on Github](https://github.com/timwhitlock/wp-twitter-api)

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/social-profile` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress.
1. Configure Twitter API Settings using "Connect to Twitter" link in "Plugins" section.
1. You can now use [twitter_profile] shortcode with "users" atrribute.

Display single profile:

`[twitter_profile users="youtube"]`

Display multiple profiles:

`[twitter_profile users="youtube,twitter,instagram,cristiano,kingjames,google,facebook"]`

== Frequently Asked Questions ==

= How to configure Twitter API? =

Go to 'Plugins' screen in WordPress, find "Social Profile" plugin and click "Connect to Twitter" link.

= Where to find Twitter OAuth credentials =

Visit [Application Management page on Twitter](https://dev.twitter.com/apps) and click "Create New App" button. On the next screen provide only "Name", "Description" and "Website" - leave empty "Callback URL" field. 

= How to display single Twitter profile? =

Use [twitter_profile] shortcode with Twitter username passed in "users" attribute. 

Example:
`[twitter_profile users="youtube"]`

= How to display multiple Twitter profiles? =

Use [twitter_profile] shortcode with Twitter usernames passed in "users" attribute, separated by comma.

Example:
`[twitter_profile users="youtube,google,facebook"]`


== Screenshots ==

1. Twitter profiles displayed using [twitter_profile] shortcode

== Changelog ==

= 1.0.2 =
* Changed Cache TTL to 4-8 hours

= 1.0.1 =
* Updated CSS code to better display longer profile descriptions

= 1.0.0 =
* First public version of the Social Profile plugin