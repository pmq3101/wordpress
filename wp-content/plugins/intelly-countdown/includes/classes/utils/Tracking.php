<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ICP_Tracking {
	public function __construct() {
		add_action( 'icp_weekly_scheduled_events', array( $this, 'sendTracking' ) );
	}

	private function getThemeData() {
		$theme_data     = wp_get_theme();
		$theme          = array(
			'name'       => $theme_data->display( 'Name', false, false ),
			'theme_uri'  => $theme_data->display( 'ThemeURI', false, false ),
			'version'    => $theme_data->display( 'Version', false, false ),
			'author'     => $theme_data->display( 'Author', false, false ),
			'author_uri' => $theme_data->display( 'AuthorURI', false, false ),
		);
		$theme_template = $theme_data->get_template();
		if ( '' !== $theme_template && $theme_data->parent() ) {
			$theme['template'] = array(
				'version'    => $theme_data->parent()->display( 'Version', false, false ),
				'name'       => $theme_data->parent()->display( 'Name', false, false ),
				'theme_uri'  => $theme_data->parent()->display( 'ThemeURI', false, false ),
				'author'     => $theme_data->parent()->display( 'Author', false, false ),
				'author_uri' => $theme_data->parent()->display( 'AuthorURI', false, false ),
			);
		} else {
			$theme['template'] = '';
		}
		unset( $theme_template );
		return $theme;
	}
	private function getPluginData() {
		if ( ! function_exists( 'get_plugins' ) ) {
			include ABSPATH . '/wp-admin/includes/plugin.php';
		}

		$plugins       = array();
		$active_plugin = get_option( 'active_plugins' );
		foreach ( $active_plugin as $plugin_path ) {
			if ( ! function_exists( 'get_plugin_data' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}
			$plugin_info      = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin_path );
			$slug             = str_replace( '/' . basename( $plugin_path ), '', $plugin_path );
			$plugins[ $slug ] = array(
				'version'    => $plugin_info['Version'],
				'name'       => $plugin_info['Name'],
				'plugin_uri' => $plugin_info['PluginURI'],
				'author'     => $plugin_info['AuthorName'],
				'author_uri' => $plugin_info['AuthorURI'],
			);
		}
		unset( $active_plugins, $plugin_path );
		return $plugins;
	}
	//obtain tracking data into an associative array
	public function getData() {
		global $icp;

		//retrieve blog info
		$result['wp_url']         = home_url();
		$result['wp_version']     = get_bloginfo( 'version' );
		$result['wp_language']    = get_bloginfo( 'language' );
		$result['wp_wpurl']       = get_bloginfo( 'wpurl' );
		$result['wp_admin_email'] = get_bloginfo( 'admin_email' );

		$result['plugins'] = $this->getPluginData();
		$result['theme']   = $this->getThemeData();

		//to obtain for each post type its count
		$post_types = $icp->Utils->query( ICP_QUERY_POST_TYPES );
		$data       = array();
		foreach ( $post_types as $v ) {
			$v          = $v['name'];
			$data[ $v ] = intval( wp_count_posts( $v )->publish );
		}
		$result['post_types'] = $data;

		$data                               = array();
		$result['iwpm_plugin_name']         = ICP_PLUGIN_SLUG;
		$result['iwpm_plugin_version']      = ICP_PLUGIN_VERSION;
		$result['iwpm_plugin_data']         = $data;
		$result['iwpm_plugin_install_date'] = $icp->Options->getPluginInstallDate();
		$result['iwpm_plugin_update_date']  = $icp->Options->getPluginUpdateDate();

		$result['iwpm_tracking_enable'] = $icp->Options->isTrackingEnable();
		$result['iwpm_logger_enable']   = $icp->Options->isLoggerEnable();
		$result['iwpm_feedback_email']  = $icp->Options->getFeedbackEmail();
		return $result;
	}

	public function sendTracking( $override = false ) {
		global $icp;

		$result = -1;
		if ( ! $override && ! $icp->Options->isTrackingEnable() ) {
			return $result;
		}

		// Send a maximum of once per week
		$last_send = $icp->Options->getTrackingLastSend();
		if ( ! $override && $last_send > strtotime( '-1 week' ) ) {
			return $result;
		}

		//add_filter('https_local_ssl_verify', '__return_false');
		//add_filter('https_ssl_verify', '__return_false');
		//add_filter('block_local_requests', '__return_false');
		$data = $icp->Utils->remotePost( 'usage', $this->getData() );
		if ( $data ) {
			$result = intval( $data['id'] );
			$icp->Options->setTrackingLastSend( time() );
		}
		return $result;
	}
}
