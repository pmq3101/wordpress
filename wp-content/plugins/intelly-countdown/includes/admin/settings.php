<?php
function icp_ui_track( $always = false ) {
	global $icp;
	$track = $icp->Utils->qs( 'track', '' );
	if ( '' != $track ) {
		$settings                     = $icp->Options->getPluginSettings();
		$settings->allowUsageTracking = intval( $track );
		$icp->Options->setPluginSettings( $settings );
	}

	if ( ! $always && $icp->Options->isTrackingEnable() ) {
		return;
	}

	if ( $icp->Options->isTrackingEnable() ) {
		$arg = array( 'track' => 0 );
		$uri = $icp->Utils->addQueryString( $arg, ICP_TAB_SETTINGS_URI );
		$icp->Options->pushSuccessMessage( 'Tracking.Enabled', $uri );
	} else {
		$arg = array( 'track' => 1 );
		$uri = $icp->Utils->addQueryString( $arg, ICP_TAB_SETTINGS_URI );
		$icp->Options->pushWarningMessage( 'Tracking.Disabled', $uri );
	}
	$icp->Options->writeMessages();
}
function icp_ui_settings() {
	global $icp;

	?>
	<h2><?php $icp->Lang->P( 'Title.Settings' ); ?></h2>
	<?php

	icp_ui_track( true );

	$icp->Form->prefix = 'Settings';
	if ( $icp->Check->nonce( 'icp_settings' ) ) {
		if ( $icp->Check->is( '_action', 'Save' ) ) {
			$settings = $icp->Options->getPluginSettings();
			if ( isset( $_POST['supportData443'] ) ) {
				if ( sanitize_text_field( wp_unslash( $_POST['supportData443'] ) ) ) {
					$settings->allowPoweredBy = true;
				}
			} else {
				$settings->allowPoweredBy = false;
			}
			$icp->Options->setPluginSettings( $settings );
		}
	}

	$icp->Form->formStarts();
	{
		$icp->Form->divStarts( array( 'class' => 'admin-form' ) );
		$icp->Form->br();
		$options  = array();
		$settings = $icp->Options->getPluginSettings();
		$icp->Form->toggle( 'supportData443', $settings->allowPoweredBy, 1, $options );
		$icp->Form->divStarts( array( 'class' => 'col-md-3' ) );
		$icp->Form->divEnds();
		$icp->Form->divStarts( array( 'class' => 'col-md-9' ) );
		echo wp_kses_post( $icp->Lang->L( 'Settings.supportNote' ) );
		$icp->Form->divEnds();
		$icp->Form->br();
		$icp->Form->br();
		$icp->Form->br();
		$icp->Form->br();
		$icp->Form->divEnds();

		$icp->Form->nonce( 'icp_settings' );
		$button          = array(
			'submit' => true,
		);
		$buttons['Save'] = $button;
		}
		$icp->Form->formEnds();
}
