<?php
/**
 * HybridAuth
 * http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
 * (c) 2009-2015, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
 */
// ----------------------------------------------------------------------------------------
//	HybridAuth Config file: http://hybridauth.sourceforge.net/userguide/Configuration.html
// ----------------------------------------------------------------------------------------


	$LoginWithConfig = array(
			"callback" => $pt->config->site_url . '/social-login.php?provider=' . $provider,
			"providers" => array(
				// openid providers
				"OpenID" => array(
					"enabled" => true
				),
				"Yahoo" => array(
					"enabled" => true,
					"keys" => array("key" => "", "secret" => ""),
				),
				"AOL" => array(
					"enabled" => true
				),
				"Google" => array(
					"enabled" => true,
					"keys" => array("id" =>  $pt->config->google_app_ID, "secret" => $pt->config->google_app_key),
				),
				"Facebook" => array(
					"enabled" => true,
					"keys" => array("id" => $pt->config->facebook_app_ID, "secret" => $pt->config->facebook_app_key),
					"scope" => "email",
					"trustForwarded" => false
				),
				// "Instagram" => array(
				// 	"enabled" => true,
				// 	"keys" => array("id" => '528191331648375', "secret" => '7f6d768b3d090783268aee8e7c6ac3fb'),
				// 	"scope" => "basic",
				// 	"trustForwarded" => false
				// ),
				// "Instagram" => array(
				// 	"enabled" => true,
				// 	"keys" => array(
				// 	  "id" => '528191331648375',
				// 	  "secret" => '7f6d768b3d090783268aee8e7c6ac3fb'
				// 	),
				//   ),
				"Reddit" => array(
					"enabled" => true,
					"keys" => array("id" => $pt->config->reddit_app_ID, "secret" => $pt->config->instagram_app_key),
				),
				"Instagram" => array(
					"enabled" => true,
					"keys" => array("id" => $pt->config->instagram_app_ID, "secret" => $pt->config->instagram_app_key),
				),
				"Twitter" => array(
					"enabled" => true,
					"keys" => array("key" => $pt->config->twitter_app_ID, "secret" => $pt->config->twitter_app_key),
					"includeEmail" => true
				),
				// windows live
				"Live" => array(
					"enabled" => true,
					"keys" => array("id" => "", "secret" => "")
				),
				"Foursquare" => array(
					"enabled" => true,
					"keys" => array("id" => "", "secret" => "")
				),
			),
			// If you want to enable logging, set 'debug_mode' to true.
			// You can also set it to
			// - "error" To log only error messages. Useful in production
			// - "info" To log info and error messages (ignore debug messages)
			"debug_mode" => false,
			"debug_file" => "hybridauth.log"
);
