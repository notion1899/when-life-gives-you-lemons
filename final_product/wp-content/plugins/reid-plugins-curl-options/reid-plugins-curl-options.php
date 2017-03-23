<?php
/*
Plugin Name: Wise Builds cURL Options
Description: Allows configuration of cURL connection options.
Version: 1.1
Author: Wise Builds
Author URI: https://wisebuilds.ca
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( is_admin() ){
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'rpco_plugin_action_links' );
	add_action( 'admin_menu', 'rpco_admin_menu' );
	add_action( 'admin_init', 'rpco_settings_init' );
	add_action( 'admin_enqueue_scripts', 'rpco_admin_enqueue_scripts' );
	add_action( 'wp_ajax_rpco_test', 'rpco_ajax_test_options' );
	add_action( 'wp_ajax_nopriv_rpco_test', 'rpco_ajax_test_options' );
}

define( 'RPCO_VERSION', '1.1' );
define( 'RPCO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'RPCO_CURL_EXISTS', function_exists( 'curl_version' ) );
define( 'RPCO_PHP_VERSION', phpversion() );

if ( RPCO_CURL_EXISTS ) {
	global $wp_version;
	$rpco_curl_version = curl_version();
	if ( version_compare( $wp_version, "4.6", ">=" ) ) {
		if ( class_exists( 'Requests' ) ) {
			add_action( 'requests-curl.before_send', 'rpco_http_api_curl', PHP_INT_MAX, 1 );
		}
	} else {
		add_action( 'http_api_curl', 'rpco_http_api_curl', PHP_INT_MAX, 1 );
	}
}

function rpco_admin_menu() {
	global $rpco_settings_page;
	$rpco_settings_page = add_submenu_page( 'options-general.php', 'cURL Options', 'cURL Options', 'manage_options', 'curl-options', 'rpco_settings_page' );
}

function rpco_plugin_action_links( $links ) {
	return array_merge( array(
		'<a href="' . admin_url( 'options-general.php?page=curl-options' ) . '">' . 'Settings' . '</a>'
	), $links );
}

function rpco_admin_enqueue_scripts($hook) {
	global $rpco_settings_page;
	if ( $hook == $rpco_settings_page ) {
		wp_enqueue_script( 'rpco-admin-js', RPCO_PLUGIN_URL . 'js/curl-options.js', array( 'jquery', 'jquery-form' ), RPCO_VERSION );
	}
}

function rpco_settings_page() {

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'You do not have sufficient permissions to access this page.' );
	}
	
	echo '<div class="wrap">
		<h2>Wise Builds cURL Options</h2>';
		
	settings_errors( 'rpco_settings_arr', true, true );
	
	echo '<div style="display: inline-block; float: right; width: 150px; text-align: center;">
	<p style="text-align: left;">Please consider making a donation to show your appreciation and support maintenance of the plugin, thanks!</p>
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="hidden" name="hosted_button_id" value="Y9YNJJT7CCUDN">
	<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
	<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
	</form>
	</div>';
	
	if ( RPCO_CURL_EXISTS ) {
		echo '<form method="post" action="options.php" id="rpco-settings-form">';
		settings_fields('rpco_settings_group');
		do_settings_sections( 'curl-options' );
		submit_button();
		echo '</form>';
		
		echo '<div style="border: 3px groove #ccc; display: inline-block; padding: 6px 18px;">
			<form id="rpco-test-form" action="' . admin_url( 'admin-ajax.php' ) . '" method="post">
				<p><strong>Test cURL Rules</strong> <em>(Save rules changes above before testing)</em></p>
				<p><strong>Example test</strong> <em>(Paypal sandbox IPN)</em>:<br>
				<strong>URL:</strong> https://www.sandbox.paypal.com/cgi-bin/webscr<br>
				<strong>Post Fields:</strong> test_ipn=1&amp;cmd=_notify-validate</p>
				<table>
				<tr><td><strong>URL</strong></td><td><strong>Post Fields</strong> <em>(as query string like: para1=val1&amp;para2=val2&amp;...)</em></td></tr>
				<tr>
				<td><input name="rpco-test-url" size="50" type="text" value="" /></td>
				<td><input name="rpco-test-post" size="50" type="text" value="" /></td>
				</tr>
				</table>
				<input type="hidden" name="action" value="rpco_test" />
				<p><input type="submit" value="Test Rules" /></p>
				<p><strong>Result</strong> <em>of wp_safe_remote_post():</em></p>
				<div style="border: 1px dotted #ccc; margin: 12px 0; padding: 6px 18px; background-color: #f5f5f5; min-height: 20px;">
					<div id="rpco-testing-ind" style="display: none; color: #003399; font-weight: bold;">TESTING...</div>
					<div id="rpco-test-result"></div>
				</div>
			</form>
		</div>';
	
	} else {
		echo '<p>PHP cURL is not installed or enabled on your server, this plugin will not do anything.</p>';
	}
	
	echo '</div>';

}


function rpco_settings_init(){	
	
	register_setting( 'rpco_settings_group', 'rpco_settings_arr', 'rpco_settings_sanitize' );
	
	add_settings_section( 'rpco_gen_sect_id', '', 'rpco_main_section_desc', 'curl-options' );
	add_settings_field( 'rpco_curl_rules_id', 'cURL Rules:', 'rpco_curl_rules', 'curl-options', 'rpco_gen_sect_id' );
	
}


function rpco_main_section_desc() {
	
	global $rpco_curl_version;
	
	echo '<p>Settings for WordPress PHP cURL connections.</p>';

	echo '<p><strong>Current PHP version:</strong> ' . RPCO_PHP_VERSION . '</p>';
	
	echo '<table>';
	echo '<tr><td colspan="2"><strong>Your server\'s PHP cURL version information and features:</strong></td></tr>';
	echo '<tr><td>cURL version:</td><td>' . $rpco_curl_version['version'] . '</td></tr>';
	echo '<tr><td>Host:</td><td>' . $rpco_curl_version['host'] . '</td></tr>';
	echo '<tr><td>IPv6 support:</td><td>' . (($rpco_curl_version['features'] & CURL_VERSION_IPV6) ? 'yes' : 'no') . '</td></tr>';
	echo '<tr><td>Kerberos V4 support:</td><td>' . (($rpco_curl_version['features'] & CURL_VERSION_KERBEROS4) ? 'yes' : 'no') . '</td></tr>';
	echo '<tr><td>SSL support:</td><td>' . (($rpco_curl_version['features'] & CURL_VERSION_SSL) ? 'yes' : 'no') . '</td></tr>';
	if ($rpco_curl_version['features'] & CURL_VERSION_SSL) {
		echo '<tr><td>SSL version:</td><td>' . $rpco_curl_version['ssl_version'] . '</td></tr>';
	}
	echo '<tr><td>libz HTTP deflate support:</td><td>' . (($rpco_curl_version['features'] & CURL_VERSION_LIBZ) ? 'yes' : 'no') . '</td></tr>';
	if ($rpco_curl_version['features'] & CURL_VERSION_LIBZ) {
		echo '<tr><td>libz version:</td><td>' . $rpco_curl_version['libz_version'] . '</td></tr>';
	}
	echo '<tr><td>Protocols:</td><td>' . implode( ', ', $rpco_curl_version['protocols'] ) . '</td></tr>';
	echo '</table>';
	
	echo '<p><strong>Please refer to the documentation for <a href="http://php.net/manual/en/function.curl-setopt.php" target="_blank">curl_setopt</a> in the PHP manual for options and valid values.</strong></p>';
	
	echo '<p><strong>NOTES:</strong><br>
			* If you set CURLOPT_SSL_CIPHER_LIST it must be in the proper format for your SSL version (NSS or OpenSSL).<br>
			* Stream resource or curl share option values will be ignored.</br>
			* Enter array option values as a comma separated list.<br>
		</p>';
	
	echo '<p><strong><em>Example rule for Paypal IPN:</em></strong><br>
			Host: www.paypal.com<br>
			Protocol: https<br>
			Option: CURLOPT_SSLVERSION<br>
			Value: 6</p>';

}


function rpco_curl_rules() {
	
	global $rpco_curl_version;
	
	$options = get_option('rpco_settings_arr');
	$value = "";
	
	echo '<table id="rpco-curl-rules-table">';
	
	// table header row
	echo '<tr>';
	echo '<td style="padding: 0;"><strong>Host</strong></td>';
	echo '<td style="padding: 0;"><strong>Protocol</strong></td>';
	echo '<td style="padding: 0;"><strong>Option</strong></td>';
	echo '<td style="padding: 0;"><strong>Value</strong></td>';
	echo '<td style="padding: 0;"></td>';
	echo '</tr>';
	
	// rule template row
	echo '<tr id="rpco-curl-rule-row-template" style="display: none;">';
	echo '<td style="padding: 0;"><input class="rpco-rule-host" size="30" type="text" value="" /></td>';
	echo '<td style="padding: 0;">';
	echo '<select class="rpco-rule-protocol">';
	foreach ( $rpco_curl_version['protocols'] as $protocol ) {
		echo '<option value="'.$protocol.'">'.$protocol.'</option>';
	}
	echo '</select>';
	echo '</td>';
	echo '<td style="padding: 0;"><input class="rpco-rule-option" size="30" type="text" value="" /></td>';
	echo '<td style="padding: 0;"><input class="rpco-rule-value" size="30" type="text" value="" /></td>';
	echo '<td style="padding: 0;"><button class="rpco-del-rule-btn" type="button">x</button></td>';
	echo '</tr>';
	
	// existing rules rows
	$rule_count = 0;
	if ( !empty( $options ) ) {
		if ( !empty( $options['curl_rules'] ) ) {
			foreach ( $options['curl_rules'] as $curl_rule ) {
				echo '<tr>';
				echo '<td style="padding: 0;"><input class="rpco-rule-host" name="rpco_settings_arr[curl_rules]['.$rule_count.'][host]" size="30" type="text" value="'.$curl_rule['host'].'" /></td>';
				echo '<td style="padding: 0;">';
				echo '<select class="rpco-rule-protocol" name="rpco_settings_arr[curl_rules]['.$rule_count.'][protocol]">';
				foreach ( $rpco_curl_version['protocols'] as $protocol ) {
					echo '<option value="'.$protocol.'" '.($protocol == $curl_rule['protocol'] ? 'selected' : '').'>'.$protocol.'</option>';
				}
				echo '</select>';
				echo '</td>';
				echo '<td style="padding: 0;"><input class="rpco-rule-option" name="rpco_settings_arr[curl_rules]['.$rule_count.'][option]" size="30" type="text" value="'.$curl_rule['option'].'" /></td>';
				echo '<td style="padding: 0;"><input class="rpco-rule-value" name="rpco_settings_arr[curl_rules]['.$rule_count.'][value]" size="30" type="text" value="'.$curl_rule['value'].'" /></td>';
				echo '<td style="padding: 0;"><button class="rpco-del-rule-btn" type="button">x</button></td>';
				echo '</tr>';
				$rule_count = $rule_count + 1;
			}
		}
	}
	
	
	echo '</table>';
	echo '<button id="rpco-add-rule-btn" type="button">Add Rule</button>';
	echo '<script type="text/javascript">
			//<![CDATA[
					var rpcoRuleCount = '.$rule_count.';
			//]]>
		</script>';

}


function rpco_settings_sanitize( $input ) {

	global $rpco_curl_version;
	$options = get_option('rpco_settings_arr');
	
	if ( !empty( $options ) ) {
		
		if ( !empty( $options['php_version'] ) ) {
			if ( $options['php_version'] != RPCO_PHP_VERSION ) {
				add_settings_error(
					'rpco_settings_arr',
					'ncrmc-php-version-error',
					'The PHP version on your server has changed. -- Previous version: ' . $options['php_version'] . ' -- Current version: ' . RPCO_PHP_VERSION . ' -- Please revise and re-save your cURL options rules if required.' ,
					'error'
				);
			}
		}
		
		if ( !empty( $options['curl_version'] ) ) {
			if ( $options['curl_version'] != $rpco_curl_version['version'] ) {
				add_settings_error(
					'rpco_settings_arr',
					'ncrmc-curl-version-error',
					'The cURL version on your server has changed. -- Previous version: ' . $options['curl_version'] . ' -- Current version: ' . $rpco_curl_version['version'] . ' -- Please revise and re-save your cURL options rules if required.',
					'error'
				);
			}
		}
		
	}
	

	if ( !empty( $input ) ) {
		
		if ( !empty( $input['curl_rules'] ) ) {
			
			foreach ( $input['curl_rules'] as $key => $curl_rule ) {
				
				if ( $curl_rule['host'] == '' || 
				$curl_rule['protocol'] == '' ||
				$curl_rule['option'] == '' ||
				$curl_rule['value'] == '' ) {
					unset($input['curl_rules'][$key]);
				} elseif ( !in_array( $curl_rule['protocol'], $rpco_curl_version['protocols'] ) ) {
					unset($input['curl_rules'][$key]);
				} else {
					$input['curl_rules'][$key]['host'] = sanitize_text_field( trim($curl_rule['host']) );
					$input['curl_rules'][$key]['protocol'] = sanitize_text_field( trim($curl_rule['protocol']) );
					$input['curl_rules'][$key]['option'] = sanitize_text_field( trim($curl_rule['option']) );
					$input['curl_rules'][$key]['value'] = sanitize_text_field( trim($curl_rule['value']) );
				}
				
				$sort_host[] = $curl_rule['host'];
				$sort_protocol[] = $curl_rule['protocol'];
				$sort_option[] = $curl_rule['option'];
				
			}
			
			array_multisort($sort_host, SORT_ASC, SORT_STRING, $sort_protocol, SORT_ASC, SORT_STRING, $sort_option, SORT_ASC, SORT_STRING, $input['curl_rules']);
			
		}
		
	}  

	$options['php_version'] = RPCO_PHP_VERSION;
	$options['curl_version'] = $rpco_curl_version['version'];
	$options['curl_rules'] = $input['curl_rules'];
	
	return $options;

}

function rpco_admin_notices() {
    settings_errors( 'rpco_settings_arr', true, true );
}


function rpco_http_api_curl( &$cr ) {
	
	$options = get_option('rpco_settings_arr');
	
	$curlopt_booleans = array("CURLINFO_HEADER_OUT", "CURLOPT_AUTOREFERER", "CURLOPT_BINARYTRANSFER", "CURLOPT_CERTINFO", "CURLOPT_CONNECT_ONLY", "CURLOPT_COOKIESESSION", "CURLOPT_CRLF", "CURLOPT_DNS_USE_GLOBAL_CACHE", "CURLOPT_FAILONERROR", "CURLOPT_FILETIME", "CURLOPT_FOLLOWLOCATION", "CURLOPT_FORBID_REUSE", "CURLOPT_FRESH_CONNECT", "CURLOPT_FTP_CREATE_MISSING_DIRS", "CURLOPT_FTP_USE_EPRT", "CURLOPT_FTP_USE_EPSV", "CURLOPT_FTPAPPEND", "CURLOPT_FTPASCII", "CURLOPT_FTPLISTONLY", "CURLOPT_HEADER", "CURLOPT_HTTPGET", "CURLOPT_HTTPPROXYTUNNEL", "CURLOPT_MUTE", "CURLOPT_NETRC", "CURLOPT_NOBODY", "CURLOPT_NOPROGRESS", "CURLOPT_NOSIGNAL", "CURLOPT_POST", "CURLOPT_PUT", "CURLOPT_RETURNTRANSFER", "CURLOPT_SAFE_UPLOAD", "CURLOPT_SSL_VERIFYPEER", "CURLOPT_TCP_NODELAY", "CURLOPT_TRANSFERTEXT", "CURLOPT_UNRESTRICTED_AUTH", "CURLOPT_UPLOAD", "CURLOPT_VERBOSE");
	
	$curlopt_integers = array("CURLOPT_BUFFERSIZE", "CURLOPT_CLOSEPOLICY", "CURLOPT_CONNECTTIMEOUT", "CURLOPT_CONNECTTIMEOUT_MS", "CURLOPT_DNS_CACHE_TIMEOUT", "CURLOPT_FTPSSLAUTH", "CURLOPT_HTTP_VERSION", "CURLOPT_HTTPAUTH", "CURLOPT_INFILESIZE", "CURLOPT_IPRESOLVE", "CURLOPT_LOW_SPEED_LIMIT", "CURLOPT_LOW_SPEED_TIME", "CURLOPT_MAX_RECV_SPEED_LARGE", "CURLOPT_MAX_SEND_SPEED_LARGE", "CURLOPT_MAXCONNECTS", "CURLOPT_MAXREDIRS", "CURLOPT_PORT", "CURLOPT_POSTREDIR", "CURLOPT_PROTOCOLS", "CURLOPT_PROXYAUTH", "CURLOPT_PROXYPORT", "CURLOPT_PROXYTYPE", "CURLOPT_REDIR_PROTOCOLS", "CURLOPT_RESUME_FROM", "CURLOPT_SSH_AUTH_TYPES", "CURLOPT_SSL_VERIFYHOST", "CURLOPT_SSLVERSION", "CURLOPT_TIMECONDITION", "CURLOPT_TIMEOUT", "CURLOPT_TIMEOUT_MS", "CURLOPT_TIMEVALUE");
	
	$curlopt_strings = array("CURLOPT_BUFFERSIZE", "CURLOPT_CLOSEPOLICY", "CURLOPT_CONNECTTIMEOUT", "CURLOPT_CONNECTTIMEOUT_MS", "CURLOPT_DNS_CACHE_TIMEOUT", "CURLOPT_CAINFO", "CURLOPT_CAPATH", "CURLOPT_COOKIE", "CURLOPT_COOKIEFILE", "CURLOPT_COOKIEJAR", "CURLOPT_CUSTOMREQUEST", "CURLOPT_EGDSOCKET", "CURLOPT_ENCODING", "CURLOPT_FTPPORT", "CURLOPT_INTERFACE", "CURLOPT_KEYPASSWD", "CURLOPT_KRB4LEVEL", "CURLOPT_POSTFIELDS", "CURLOPT_PROXY", "CURLOPT_PROXYUSERPWD", "CURLOPT_RANDOM_FILE", "CURLOPT_RANGE", "CURLOPT_REFERER", "CURLOPT_SSH_HOST_PUBLIC_KEY_MD5", "CURLOPT_SSH_PRIVATE_KEYFILE", "CURLOPT_SSH_PUBLIC_KEYFILE", "CURLOPT_SSL_CIPHER_LIST", "CURLOPT_SSLCERT", "CURLOPT_SSLCERTPASSWD", "CURLOPT_SSLCERTTYPE", "CURLOPT_SSLENGINE", "CURLOPT_SSLENGINE_DEFAULT", "CURLOPT_SSLKEY", "CURLOPT_SSLKEYPASSWD", "CURLOPT_SSLKEYTYPE", "CURLOPT_URL", "CURLOPT_USERAGENT", "CURLOPT_USERPWD");
	
	$curlopt_arrays = array("CURLOPT_HTTP200ALIASES", "CURLOPT_HTTPHEADER", "CURLOPT_POSTQUOTE", "CURLOPT_QUOTE");
	
	$curlopt_streams = array("CURLOPT_FILE", "CURLOPT_INFILE", "CURLOPT_STDERR", "CURLOPT_WRITEHEADER");
	
	$curlopt_functions = array("CURLOPT_HEADERFUNCTION", "CURLOPT_PASSWDFUNCTION", "CURLOPT_PROGRESSFUNCTION", "CURLOPT_READFUNCTION", "CURLOPT_WRITEFUNCTION");
	
	$curlopt_others = array("CURLOPT_SHARE");
	

	if ( !empty( $options ) ) {
		if ( !empty( $options['curl_rules'] ) ) {
			
			$cr_url = curl_getinfo( $cr, CURLINFO_EFFECTIVE_URL );
			$cr_url_parts = parse_url( $cr_url );
			
			$curl_opts = array();
			foreach ( $options['curl_rules'] as $curl_rule ) {
				if ( $cr_url_parts['host'] == $curl_rule['host'] && $cr_url_parts['scheme'] == $curl_rule['protocol'] ) {
				
					$opt_val = $curl_rule['value'];
					
					// check for boolean value
					if ( in_array( $curl_rule['option'], $curlopt_booleans ) ) {
						if ( strtolower( $opt_val ) == "false" || $opt_val == "0" ) {
							$opt_val = false;
						} else {
							$opt_val = true;
						}
					}
					
					// check for integer/contstant value
					if ( in_array( $curl_rule['option'], $curlopt_integers ) ) {
						if ( is_numeric( $opt_val ) ) {
							$opt_val = intval( $opt_val );
						} else {
							$opt_val = constant( $opt_val );
						}
					}
					
					// check for array value
					if ( in_array( $curl_rule['option'], $curlopt_arrays ) ) {
						$opt_val = explode( ",", $opt_val );
					}
					
					// check for stream resource or curl share and skip
					if ( in_array( $curl_rule['option'], $curlopt_streams ) || in_array( $curl_rule['option'], $curlopt_others ) ) {
						continue;
					}
					
					// add option to options array
					$curl_opts[constant($curl_rule['option'])] = $opt_val;
				
				}
			}
			
			if ( !empty( $curl_opts ) ) {
				// apply options
				curl_setopt_array($cr, $curl_opts);
			}
			
			//echo '<pre>' . print_r( $cr, true ) . '</pre>';
			
		}
	}

}


function rpco_ajax_test_options() {
	
	if ( !current_user_can( 'manage_options' ) ) {
		echo 'Insufficient permissions to perform this action.';
		exit;
	}
	
	if ( !empty( $_POST['rpco-test-url'] ) ) {
	
		$url = esc_url_raw( trim( $_POST['rpco-test-url'] ) );
		if ( !empty( $_POST['rpco-test-post'] ) ) {
			parse_str( trim( $_POST['rpco-test-post'] ), $post_arr );
			if ( !empty( $post_arr ) ) {
				$post = $post_arr;
			}
		}
		
		if ( !empty( $post ) ) {
			$response = wp_safe_remote_post( $url, array( 'body' => $post ) );
		} else {
			$response = wp_safe_remote_post( $url );
		}

		if ( !is_wp_error( $response ) ) {
			echo '<span style="color: #009933; font-weight: bold;">SUCCESS</span> - Response: ';
			echo '<pre>' . print_r( $response, true ) . '</pre>';
			exit;
		} else {
			echo '<span style="color: #993300; font-weight: bold;">FAIL</span> - ' . $response->get_error_message();
			exit;
		}
		
	} else {
		
		echo 'Please provide a valid url.';
		exit;
		
	}

}

?>