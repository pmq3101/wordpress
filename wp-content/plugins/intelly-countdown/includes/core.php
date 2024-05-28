<?php

function ICP_wp_footer() {
	global $icp;
	if ( '' != $icp->Ui->Countdown->classCountdown ) {
		$icp->Ui->Countdown->style();
		$icp->Ui->Countdown->script();
	}
	return 1;
}
add_filter( 'wp_footer', 'ICP_wp_footer' );

function ICP_admin_footer() {
	global $icp;
	if ( '' != $icp->Ui->Countdown->classCountdown ) {
		$icp->Ui->Countdown->style();
		$icp->Ui->Countdown->script();
	}
	//if ( $icp->Lang->bundle->autoPush && ICP_AUTOSAVE_LANG ) {
	//	$icp->Lang->bundle->store( ICP_PLUGIN_DIR . 'languages/Lang.txt' );
	//}
	return 1;
}
add_filter( 'admin_footer', 'ICP_admin_footer' );

function icp_ui_first_time() {
	global $icp;
	if ( $icp->Options->isShowActivationNotice() ) {
		//$tcmp->Options->pushSuccessMessage('FirstTimeActivation');
		//$tcmp->Options->writeMessages();
		$icp->Options->setShowActivationNotice( false );
	}
}

if ( shortcode_exists( 'ec' ) ) {
	remove_shortcode( 'ec' );
}

function ICP_ec( $atts, $content = '' ) {
	global $icp;
	$default   = array(
		'id' => 0,
	);
	$options   = shortcode_atts( $default, $atts );
	$countdown = $icp->Manager->get( $options['id'] );
	if ( false === $countdown ) {
		return;
	}

	ob_start();
	$icp->Ui->Countdown->draw( $countdown );
	$result = ob_get_contents();
	ob_end_clean();
	return $result;
}
add_shortcode( 'ec', 'ICP_ec' );


