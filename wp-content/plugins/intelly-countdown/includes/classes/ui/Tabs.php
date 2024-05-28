<?php
class ICP_Tabs {
	private $tabs = array();

	function init() {
		global $icp;
		add_filter( 'wp_enqueue_scripts', array( &$this, 'siteEnqueueScripts' ) );
		if ( $icp->Utils->isAdminUser() ) {
			add_action( 'admin_menu', array( &$this, 'attachMenu' ) );
			add_filter( 'plugin_action_links', array( &$this, 'pluginActions' ), 10, 2 );
			if ( $icp->Utils->isPluginPage() ) {
				add_action( 'admin_enqueue_scripts', array( &$this, 'adminEnqueueScripts' ), 9999 );
			}
		}
	}

	function attachMenu() {
		global $icp;
		if ( $icp->Utils->isAdminUser() ) {
			add_submenu_page(
				'options-general.php',
				ICP_PLUGIN_NAME,
				ICP_PLUGIN_NAME,
				'manage_options',
				ICP_PLUGIN_SLUG,
				array( &$this, 'showTabPage' )
			);
		}
	}
	function pluginActions( $links, $file ) {
		global $icp;
		if ( ICP_PLUGIN_SLUG . '/index.php' == $file ) {
			$settings   = array();
			$settings[] = "<a href='" . ICP_TAB_MANAGER_URI . "'>" . $icp->Lang->L( 'Settings' ) . '</a>';
			$settings[] = "<a href='" . ICP_TAB_PREMIUM_URI . "'>" . $icp->Lang->L( 'PREMIUM' ) . '</a>';
			$links      = array_merge( $settings, $links );
		}
		return $links;
	}
	function siteEnqueueScripts() {
		wp_enqueue_script( 'jquery' );
		$this->wpEnqueueScript( 'assets/deps/moment/moment.js' );
		$this->wpEnqueueScript( 'assets/js/icp.library.js' );
		return 1;
	}
	function adminEnqueueScripts() {
		global $icp;
		$icp->Utils->dequeueScripts( 'select2|woocommerce|page-expiration-robot' );
		$icp->Utils->dequeueStyles( 'select2|woocommerce|page-expiration-robot' );

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'jquery-ui-autocomplete' );
		wp_enqueue_script( 'suggest' );

		$this->wpEnqueueStyle( 'assets/css/font-awesome.min.css' );

		$this->wpEnqueueStyle( 'assets/css/theme.css' );
		$this->wpEnqueueStyle( 'assets/css/admin-forms.css' );
		$this->wpEnqueueStyle( 'assets/css/all-themes.css' );
		$this->wpEnqueueStyle( 'assets/css/style.css' );
		$this->wpEnqueueScript( 'assets/deps/starrr/starrr.js' );

		$this->wpEnqueueStyle( 'assets/deps/select2-4.0.13/select2.css' );
		$this->wpEnqueueScript( 'assets/deps/select2-4.0.13/select2.full.js' );

		$this->wpEnqueueScript( 'assets/deps/qtip/jquery.qtip.min.js' );
		$this->wpEnqueueStyle( 'assets/deps/magnific/magnific-popup.css' );
		$this->wpEnqueueScript( 'assets/deps/magnific/jquery.magnific-popup.js' );

		$this->wpEnqueueScript( 'assets/deps/moment/moment.js' );

		$this->wpEnqueueStyle( 'assets/deps/datepicker/css/bootstrap-datetimepicker.css' );
		$this->wpEnqueueScript( 'assets/deps/datepicker/js/bootstrap-datetimepicker.js' );

		$this->wpEnqueueStyle( 'assets/deps/colorpicker/css/bootstrap-colorpicker.min.css' );
		$this->wpEnqueueScript( 'assets/deps/colorpicker/js/bootstrap-colorpicker.min.js' );

		$this->wpEnqueueScript( 'assets/js/utility.js' );
		$this->wpEnqueueScript( 'assets/js/icp.library.js' );
		$this->wpEnqueueScript( 'assets/js/icp.plugin.js' );
	}
	function wpEnqueueStyle( $uri, $name = '' ) {
		if ( '' == $name ) {
			$name = explode( '/', $uri );
			$name = $name[ count( $name ) - 1 ];
			$dot  = strrpos( $name, '.' );
			if ( false !== $dot ) {
				$name = substr( $name, 0, $dot );
			}
			$name = ICP_PLUGIN_PREFIX . '_' . $name;
		}

		$v = '?v=' . ICP_PLUGIN_VERSION;
		wp_enqueue_style( $name, ICP_PLUGIN_URI . $uri . $v );
	}
	function wpEnqueueScript( $uri, $name = '', $version = false ) {
		if ( '' == $name ) {
			$name = explode( '/', $uri );
			$name = $name[ count( $name ) - 1 ];
			$dot  = strrpos( $name, '.' );
			if ( false !== $dot ) {
				$name = substr( $name, 0, $dot );
			}
			$name = ICP_PLUGIN_PREFIX . '_' . $name;
		}

		$v    = '?v=' . ICP_PLUGIN_VERSION;
		$deps = array();
		wp_enqueue_script( $name, ICP_PLUGIN_URI . $uri . $v, $deps, $version, false );
	}

	function showTabPage() {
		global $icp;
		$page = $icp->Utils->qs( 'page' );

		if ( $icp->Utils->startsWith( $page, ICP_PLUGIN_SLUG ) && ICP_PLUGIN_SLUG != $page ) {
			$_POST['page'] = ICP_PLUGIN_SLUG;
			$_GET['page']  = ICP_PLUGIN_SLUG;
			$tab           = substr( $page, strlen( ICP_PLUGIN_SLUG ) + 1 );
			$_POST['tab']  = $tab;
			$_GET['tab']   = $tab;
		}

		$id         = $icp->Utils->iqs( 'id', 0 );
		$defaultTab = ICP_TAB_MANAGER;

		if ( $icp->Options->isShowWhatsNew() ) {
			$tab                             = ICP_TAB_WHATS_NEW;
			$defaultTab                      = $tab;
			$this->tabs[ ICP_TAB_WHATS_NEW ] = $icp->Lang->L( 'What\'s New' );
		} else {
			$tab = $icp->Utils->qs( 'tab', $defaultTab );
			$uri = '';
			switch ( $tab ) {
				case ICP_TAB_DOCS:
					$uri = ICP_TAB_DOCS_URI;
					break;
				case ICP_TAB_PLUGINS:
					$uri = ICP_TAB_PLUGINS_URI;
					break;
				case ICP_TAB_SUPPORT:
					$uri = ICP_TAB_SUPPORT_URI;
					break;
			}
			if ( '' != $uri ) {
				$icp->Utils->redirect( $uri );
			}

			if ( ICP_TAB_DOCS == $tab ) {
				$icp->Utils->redirect( ICP_TAB_DOCS );
			}

			$this->tabs[ ICP_TAB_EDITOR ]   = $icp->Lang->L( $id > 0 && ICP_TAB_EDITOR == $tab ? 'Edit Countdown' : 'New Countdown' );
			$this->tabs[ ICP_TAB_MANAGER ]  = $icp->Lang->L( 'Manager' );
			$this->tabs[ ICP_TAB_SETTINGS ] = $icp->Lang->L( 'Settings' );
			$this->tabs[ ICP_TAB_DOCS ]     = $icp->Lang->L( 'FAQ & Docs' );
		}

		?>
		<div class="wrap" style="margin:5px;">
			<?php
			$this->showTabs( $defaultTab );
			$header = '';
			switch ( $tab ) {
				case ICP_TAB_EDITOR:
					$header = ( $id > 0 ? 'Edit' : 'Add' );
					break;
				case ICP_TAB_MANAGER:
					$header = 'Manager';
					break;
				case ICP_TAB_SETTINGS:
					$header = 'Settings';
					break;
				case ICP_TAB_WHATS_NEW:
					$header = '';
					break;
			}
			?>
			
			<?php
			if ( $icp->Lang->H( $header . 'Title' ) ) {
				?>
				<h2><?php $icp->Lang->P( $header . 'Title', ICP_PLUGIN_VERSION ); ?></h2>
				<?php if ( $icp->Lang->H( $header . 'Subtitle' ) ) { ?>
					<div><?php $icp->Lang->P( $header . 'Subtitle' ); ?></div>
				<?php } ?>
				<br/>
				<div style="clear:both;"></div>
				<?php
			}

			if ( ICP_TAB_WHATS_NEW != $tab ) {
				icp_ui_first_time();
			}
			?>
			<div style="float:left; margin:5px;">
				<?php
				$styles   = array();
				$styles[] = 'float:left';
				$styles[] = 'margin-right:20px';
				if ( ICP_TAB_WHATS_NEW != $tab ) {
					$styles[] = 'max-width:750px';
				}
				$styles = implode( '; ', $styles );
				?>
				<div id="icp-page" style="<?php echo esc_attr( $styles ); ?>">
					<?php
					switch ( $tab ) {
						case ICP_TAB_WHATS_NEW:
							icp_ui_whats_new();
							break;
						case ICP_TAB_EDITOR:
							icp_ui_editor();
							break;
						case ICP_TAB_MANAGER:
							icp_ui_manager();
							break;
						case ICP_TAB_SETTINGS:
							icp_ui_settings();
							break;
					}
					?>
				</div>

				<?php
				if ( $icp->Options->isShowWhatsNew() ) {
					$icp->Options->setShowWhatsNew( false );
				}
				?>
				<?php if ( ICP_TAB_WHATS_NEW != $tab ) { ?>
					<div id="icp-sidebar" style="float:left; max-width: 250px;">
						<?php
						$count   = $this->getPluginsCount();
						$plugins = array();
						while ( count( $plugins ) < 2 ) {
							$id = rand( 1, $count );
							if ( ! isset( $plugins[ $id ] ) ) {
								$plugins[ $id ] = $id;
							}
						}

						$this->drawContactUsWidget();
						foreach ( $plugins as $id ) {
							$this->drawPluginWidget( $id );
						}
						?>
					</div>
				<?php } ?>
			</div>
		</div>
		<?php
	}

	function showTabs( $defaultTab ) {
		global $icp;
		$tab = $icp->Check->of( 'tab', $defaultTab );
		if ( ICP_TAB_DOCS == $tab ) {
			$icp->Utils->redirect( ICP_TAB_DOCS_URI );
		}
		?>
		<h2 class="nav-tab-wrapper" style="float:left; width:97%;">
			<?php
			foreach ( $this->tabs as $k => $v ) {
				$active = ( $tab == $k ? 'nav-tab-active' : '' );
				$target = '_self';

				$styles   = array();
				$styles[] = 'float:left';
				$styles[] = 'margin-left:10px';
				if ( ICP_TAB_DOCS == $k ) {
					$target   = '_blank';
					$styles[] = 'background-color:#F2E49B';
				}
				$styles = implode( ';', $styles );
				?>
				<a target="<?php echo esc_attr( $target ); ?>"  style="<?php echo esc_attr( $styles ); ?>"  class="nav-tab <?php echo esc_attr( $active ); ?>" href="?page=<?php echo esc_attr( ICP_PLUGIN_SLUG ); ?>&tab=<?php echo esc_attr( $k ); ?>"><?php echo wp_kses_post( $v ); ?></a>
				<?php
			}
			?>
			<style>
				.starrr {display:inline-block}
				.starrr i{font-size:16px;padding:0 1px;cursor:pointer;color:#2ea2cc;}
			</style>
			<div style="float:right; display:none;" id="rate-box">
				<span style="font-weight:700; font-size:13px; color:#555;"><?php $icp->Lang->P( 'Rate us' ); ?></span>
				<div id="icp-rate" class="starrr" data-connected-input="icp-rate-rank"></div>
				<input type="hidden" id="icp-rate-rank" name="icp-rate-rank" value="5" />
				<?php $icp->Utils->twitter( 'data443risk' ); ?>
			</div>
		</h2>
		<div style="clear:both;"></div>
		<?php
	}
	function getPluginsCount() {
		global $icp;
		$index = 1;
		while ( $icp->Lang->H( 'Plugin' . $index . '.Name' ) ) {
			$index++;
		}
		return $index - 1;
	}
	function drawPluginWidget( $id ) {
		global $icp;
		?>
		<div class="icp-plugin-widget">
			<b><?php $icp->Lang->P( 'Plugin' . $id . '.Name' ); ?></b>
			<br>
			<i><?php $icp->Lang->P( 'Plugin' . $id . '.Subtitle' ); ?></i>
			<br>
			<ul style="list-style: circle;">
				<?php
				$index = 1;
				while ( $icp->Lang->H( 'Plugin' . $id . '.Feature' . $index ) ) {
					?>
					<li><?php $icp->Lang->P( 'Plugin' . $id . '.Feature' . $index ); ?></li>
					<?php
					$index++;
				}
				?>
			</ul>
			<a style="float:right;" class="button-primary" href="<?php $icp->Lang->P( 'Plugin' . $id . '.Permalink' ); ?>" target="_blank">
				<?php $icp->Lang->P( 'PluginCTA' ); ?>
			</a>
			<div style="clear:both"></div>
		</div>
		<br>
		<?php
	}
	function drawContactUsWidget() {
		global $icp;
		?>
		<b><?php $icp->Lang->P( 'Sidebar.Title' ); ?></b>
		<ul style="list-style: circle;">
			<?php
			$index = 1;
			while ( $icp->Lang->H( 'Sidebar' . $index . '.Name' ) ) {
				?>
				<li>
					<a href="<?php $icp->Lang->P( 'Sidebar' . $index . '.Url' ); ?>" target="_blank">
						<?php $icp->Lang->P( 'Sidebar' . $index . '.Name' ); ?>
					</a>
				</li>
				<?php
				$index++;
			}
			?>
		</ul>
		<?php
	}
}
