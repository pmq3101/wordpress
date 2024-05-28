<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//@iwp
class ICP_PluginSettings {
	//@type=text
	//@ui-type=text
	var $licenseKey;
	//@type=int
	//@ui-type=number @ui-readonly
	var $licenseSiteCount;
	//@type=int
	//@ui-type=number @ui-readonly
	var $licensePlan;

	//@type=int
	//@ui-type=toggle
	var $allowUsageTracking;

	//@type=int
	//@ui-type=toggle
	var $allowPoweredBy;

	//@type=int
	//@ui-type=toggle
	var $debugMode;

	public function __costruct() {

	}
}
