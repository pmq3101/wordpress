<?php
abstract class ICP_AbstractDetect {
	public function getFirstSeen( ICP_Countdown $v ) {
		return false;
	}
	public function getCookieFirstSeen( ICP_Countdown $v ) {
		global $icp;
		$result = false;
		if ( isset( $_COOKIE[ 'ICP_' . $v->id . '_FirstSeen' ] ) ) {
			$dt     = sanitize_text_field( wp_unslash( $_COOKIE[ 'ICP_' . $v->id . '_FirstSeen' ] ) );
			$result = DateTime::createFromFormat( DateTime::ISO8601, $dt );
			if ( false === $result ) {
				if ( $icp->Utils->contains( $dt, ' ' ) && ! $icp->Utils->contains( $dt, '+' ) ) {
					$dt = str_replace( ' ', '+', $dt );
				}
				$result = DateTime::createFromFormat( DateTime::ISO8601, $dt );
			}
			if ( false !== $result ) {
				$result = $result->getTimestamp();
			}
			/*
			$offset=$_COOKIE['ICP_GTM'];
			$dt->getTimestamp();
			$now=new DateTime($dt, new DateTimeZone('GMT'));
			$now->add(DateInterval::createFromDateString($offset.' minutes'));*/
		}
		return $result;
	}
	public function getIpAddressFirstSeen( ICP_Countdown $v ) {
		global $icp;
		$ipAddress = $icp->Utils->getVisitorIpAddress();
		if ( '' == $ipAddress ) {
			$ipAddress = $icp->Utils->getClientIpAddress();
		}
		if ( '' == $ipAddress ) {
			return false;
		}

		$result = $icp->Options->getIpAddressFirstSeen( $v->id, $ipAddress );
		if ( false === $result ) {
			$result = time();
			$icp->Options->setIpAddressFirstSeen( $v->id, $ipAddress, $result );
		}
		return $result;
	}
}
