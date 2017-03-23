=== Wise Builds cURL Options ===
Contributors: reidbusi
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=Y9YNJJT7CCUDN
Tags: cURL, HTTP API, Paypal, TLS, SSL
Requires at least: 4.4.2
Tested up to: 4.7
Stable tag: 1.1
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

Allows configuration of cURL connection options.

== Description ==
Description: Allows configuration and testing of cURL connection options for individual services such as Paypal or Moneris who have changing connection requirements that the WordPress defaults may not satisfy on your server.

Please refer to the PHP documentation for options and valid values:

* [curl_setopt](http://php.net/manual/en/function.curl-setopt.php)
* [Predefined Constants](http://php.net/manual/en/curl.constants.php)

Note the versions of PHP when various constants were defined. Some constant option values such as CURL_SSLVERSION_TLSv1_2 for CURLOPT_SSLVERSION can still be set using their integer values prior to their definition in PHP. There will be some trial and error involved to determine what works on your server depending on the version of PHP and cURL and which cryptography library was used to build cURL. Thus the testing function, which should come in handy for this (at least for http(s) POST requests).

Note the cipher suite string formats for CURLOPT_SSL_CIPHER_LIST option values are dependent on the cryptography library that was used to build cURL:

* [OpenSSL Ciphers](https://www.openssl.org/docs/manmaster/apps/ciphers.html)
* [mod_nss Configuration Directives](https://git.fedorahosted.org/cgit/mod_nss.git/plain/docs/mod_nss.html#Directives)

*(Unfortunatly the NSS cipher names seem to only be available there where the file is served as plain text, you can save it locally to view the page in your browser)*

Note that for cURL built with NSS, the mod_nss-style cipher definitions do not appear to work; the individual cipher names must be used. Cipher strings may work with cURL built with OpenSSL. Again, there will be some trial and error involved to determine what works on your server.

**For advanced users, site administrators and developers.**

If you find Wise Builds cURL Options useful and appreciate the considerable effort that went into producing it, please consider [making a donation](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=Y9YNJJT7CCUDN) to support maintenance of the plugin and development of similar plugins, thanks!

== Installation ==

1. Upload the plugin folder 'reidplugins-curl-options' to the '/wp-content/plugins' directory, or install the plugin through the WordPress plugins page directly.
2. Activate the plugin through the 'Plugins' page in WordPress.
3. Configure options under Settings -> cURL Options.

== Screenshots ==

1. An example of the options page on a shared hosting server provisioned in 2011 running PHP 5.4.45 with cURL 7.19.7 built with NSS 3.19.1.

== Changelog ==

= 1.1 =
* Release date: 2016-12-09
* Tested on WordPress 4.7
* Updated to use new Requests library in WP 4.7
* Adjusted plugin action priority
* Renamed plugin (same developer, new business)
* Added donation link

= 1.0.1 =
* Release date: 2016-04-23
* Initial Release
