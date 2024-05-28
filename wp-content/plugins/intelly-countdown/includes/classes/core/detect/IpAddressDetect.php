<?php
class ICP_IpAddressDetect extends ICP_AbstractDetect {
	public function getFirstSeen( ICP_Countdown $v ) {
		$result = $this->getIpAddressFirstSeen( $v );
		return $result;
	}
}
