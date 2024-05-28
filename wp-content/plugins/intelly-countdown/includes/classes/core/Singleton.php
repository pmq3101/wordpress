<?php
class ICP_Singleton {
	var $Lang;
	var $Utils;
	var $Options;
	var $Log;
	var $Cron;
	var $Tracking;
	var $Tabs;
	var $Lazy;
	var $Ui;
	var $Manager;
	var $Dao;

	var $Form;
	var $Check;

	function __construct() {
		$this->Lang     = new ICP_Language();
		$this->Utils    = new ICP_Utils();
		$this->Options  = new ICP_PluginOptions();
		$this->Log      = new ICP_Logger();
		$this->Cron     = new ICP_Cron();
		$this->Tracking = new ICP_Tracking();
		$this->Tabs     = new ICP_Tabs();
		$this->Lazy     = new ICP_LazyLoader();
		$this->Dao      = new ICP_Dao();
		$this->Ui       = new ICP_Ui();
		$this->Manager  = new ICP_Manager();
		$this->Form     = new ICP_CrazyForm();
		$this->Check    = new ICP_Check();
	}
	function init() {
		$this->Lang->load( 'icp', ICP_PLUGIN_DIR . 'languages/Lang.txt' );
		$this->Lang->bundle->autoPush = true;
		$this->Dao->Utils->load( ICP_PLUGIN_PREFIX, ICP_PLUGIN_DIR . 'includes/classes/domain/' );
		$this->Tabs->init();
		$this->Manager->init();
		$this->Cron->init();
	}
}
