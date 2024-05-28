<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ICP_Cron {
	public function __construct() {

	}
	public function init() {
		add_filter( 'cron_schedules', array( $this, 'addSchedules' ) );
		$this->scheduleEvents();
	}

	public function addSchedules( $schedules = array() ) {
		$schedules[ ICP_PLUGIN_PREFIX . 'daily' ]  = array(
			'interval' => 86400,
			'display'  => '{' . ICP_PLUGIN_NAME . '} Daily',
		);
		$schedules[ ICP_PLUGIN_PREFIX . 'weekly' ] = array(
			'interval' => 604800,
			'display'  => '{' . ICP_PLUGIN_NAME . '} Weekly',
		);
		/*$schedules[ICP_PLUGIN_PREFIX.'each1hour']=array(
			'interval'=> 60*60
			, 'display'=>'{'.ICP_PLUGIN_NAME.'} Each 1 hour'
		);
		$schedules[ICP_PLUGIN_PREFIX.'each1minute']=array(
			'interval'=> 10
			, 'display'=>'{'.ICP_PLUGIN_NAME.'} Each 1 minute'
		);*/
		return $schedules;
	}
	public function scheduleEvents() {
		$this->wpScheduleEvent( 'daily', array( $this, 'scheduleEvents_Daily' ) );
		$this->wpScheduleEvent( 'weekly', array( $this, 'scheduleEvents_Weekly' ) );
	}
	public function scheduleEvents_Daily() {
		do_action( 'icp_daily_scheduled_events' );
	}
	public function scheduleEvents_Weekly() {
		do_action( 'icp_weekly_scheduled_events' );
	}
	private function wpScheduleEvent( $recurrence, $function ) {
		global $icp;
		if ( ! $icp->Utils->functionExists( $function ) && ! is_callable( $function ) ) {
			return;
		}

		//icp_scheduler_daily|icp_scheduler_weekly
		/*$crons=_get_cron_array();
		foreach($crons as $time=>$jobs) {
			foreach($jobs as $k=>$v) {
				switch (strtolower($k)) {
					case 'icp_scheduler_daily':
					case 'icp_scheduler_weekly':
						unset($jobs[$k]);
						break;
				}
				if(count($jobs)==0) {
					unset($crons[$time]);
				}
			}
		}
		_set_cron_array($crons);*/

		$hook = 'cron_' . ICP_PLUGIN_PREFIX . $recurrence . '_' . $icp->Utils->getFunctionName( $function );
		if ( ! wp_next_scheduled( $hook ) ) {
			wp_schedule_event( time(), $recurrence, $hook );
		}
		add_action( $hook, $function );
	}
}
