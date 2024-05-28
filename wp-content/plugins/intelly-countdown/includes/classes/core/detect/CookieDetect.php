<?php
class ICP_CookieDetect extends ICP_AbstractDetect {
	public function getFirstSeen( ICP_Countdown $v ) {
		$result = $this->getCookieFirstSeen( $v );
		return $result;
	}
}
