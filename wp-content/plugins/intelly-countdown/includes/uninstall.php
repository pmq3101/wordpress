<?php
register_deactivation_hook( ICP_PLUGIN_FILE, 'icp_uninstall' );
function icp_uninstall( $networkwide = null ) {
	global $wpdb, $icp;
	$icp->Options->setActive( false );
}

