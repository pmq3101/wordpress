<?php
/*
 * Plugin Name: Evergreen Countdown Timer
 * Plugin URI: https://intellywp.com/evergreen-countdown-timer/
 * Description: Evergreen Countdown is a plugin built for marketers that need a reliable solution to use scarcity on their websites and landing pages.
 * Author: Data443
 * Text Domain: intelly-countdown
 * Author URI: https://data443.com/
 * Email: support@data443.com
 * Version: 2.0.8
 * Requires at least: 2.7
 * Requires PHP: 5.6
 */
register_activation_hook(__FILE__, function () {
    if (in_array('intelly-countdown-pro/index.php', apply_filters('active_plugins', get_option('active_plugins')))) {
        die;
    }
});
if (defined('ICP_PLUGIN_NAME')) {
    die('This plugin could not be activated because the PRO version of this plugin is active. Deactivate the PRO version before activating this one. No data will be lost.');
}

define( 'ICP_PLUGIN_PREFIX', 'ICP_' );
define( 'ICP_PLUGIN_FILE', __FILE__ );
define( 'ICP_PLUGIN_SLUG', 'intelly-countdown' );
define( 'ICP_PLUGIN_NAME', 'Evergreen Countdown' );
define( 'ICP_PLUGIN_VERSION', '2.0.8' );
define( 'ICP_PLUGIN_AUTHOR', 'IntellyWP' );
define( 'ICP_PLUGIN_DIR', dirname( __FILE__ ) . '/' );

define( 'ICP_PLUGIN_URI', plugins_url( '/', __FILE__ ) );
define( 'ICP_PLUGIN_ASSETS_URI', ICP_PLUGIN_URI . 'assets/' );
define( 'ICP_PLUGIN_IMAGES_URI', ICP_PLUGIN_ASSETS_URI . 'images/' );

//define('ICP_LOGGER', FALSE);
//define( 'ICP_AUTOSAVE_LANG', false );

define( 'ICP_QUERY_POSTS_OF_TYPE', 1 );
define( 'ICP_QUERY_POST_TYPES', 2 );
define( 'ICP_QUERY_CATEGORIES', 3 );
define( 'ICP_QUERY_TAGS', 4 );

define( 'ICP_INTELLYWP_SITE', 'https://intellywp.com/' );
define( 'ICP_INTELLYWP_ENDPOINT', ICP_INTELLYWP_SITE . 'wp-content/plugins/intellywp-manager/data.php' );
define( 'ICP_PAGE_FAQ', ICP_INTELLYWP_SITE . ICP_PLUGIN_SLUG );
define( 'ICP_PAGE_PREMIUM', ICP_INTELLYWP_SITE . ICP_PLUGIN_SLUG );
define( 'ICP_PAGE_HOME', admin_url() . 'options-general.php?page=' . ICP_PLUGIN_SLUG );

define( 'ICP_TAB_PLUGINS', 'plugins' );
define( 'ICP_TAB_PLUGINS_URI', 'https://intellywp.com/plugins/' );
define( 'ICP_TAB_DOCS', 'docs' );
define( 'ICP_TAB_DOCS_URI', 'https://intellywp.com/docs/evergreen-countdown-timer' );
define( 'ICP_TAB_SUPPORT', 'support' );
define( 'ICP_TAB_SUPPORT_URI', 'https://intellywp.com/contact/' );
define( 'ICP_TAB_PREMIUM_URI', 'https://intellywp.com/evergreen-countdown-timer/' );

define( 'ICP_TAB_SETTINGS', 'settings' );
define( 'ICP_TAB_SETTINGS_URI', ICP_PAGE_HOME . '&tab=' . ICP_TAB_SETTINGS );
define( 'ICP_TAB_EDITOR', 'editor' );
define( 'ICP_TAB_EDITOR_URI', ICP_PAGE_HOME . '&tab=' . ICP_TAB_EDITOR );
define( 'ICP_TAB_MANAGER', 'manager' );
define( 'ICP_TAB_MANAGER_URI', ICP_PAGE_HOME . '&tab=' . ICP_TAB_MANAGER );
define( 'ICP_TAB_WHATS_NEW', 'whatsnew' );
define( 'ICP_TAB_WHATS_NEW_URI', ICP_PAGE_HOME . '&tab=' . ICP_TAB_WHATS_NEW );

define( 'ICP_BLOG_URL', get_bloginfo( 'wpurl' ) );
define( 'ICP_BLOG_EMAIL', get_bloginfo( 'admin_email' ) );

function icp_load_textdomain() {
	load_plugin_textdomain( 'intelly-countdown', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'init', 'icp_load_textdomain' );

include_once( dirname( __FILE__ ) . '/autoload.php' );
icp_include_php( dirname( __FILE__ ) . '/includes/' );

include_once( dirname(__FILE__) . '/init_wp_kses.php');

global $icp;
$icp = new ICP_Singleton();
$icp->init();
