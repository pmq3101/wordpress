<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ICP_Manager {
	public function __construct() {

	}
	public function init() {

	}

	public function store( ICP_Countdown $countdown ) {
		global $icp;
		$countdown->id = intval( $countdown->id );
		$array         = $icp->Options->getArrayCountdowns();
		if ( $countdown->id <= 0 ) {
			$max = 0;
			foreach ( $array as $k => $v ) {
				if ( $k > $max ) {
					$max = $k;
				}
			}
			$countdown->id = ( $max + 1 );
		}
		$array[ $countdown->id ] = $countdown;
		$icp->Options->setArrayCountdowns( $array );
	}
	public function query( $id = false ) {
		global $icp;
		$result = $icp->Options->getArrayCountdowns();
		if ( false !== $id ) {
			$result = ( isset( $result[ $id ] ) ? $result[ $id ] : false );
		}
		return $result;
	}
	public function get( $id, $new = false ) {
		global $icp;
		$result = $this->query( $id );
		/* @var $result ICP_Countdown */
		if ( false === $result && $new ) {
			$result                     = $icp->Dao->Utils->newDomainClass( 'Countdown' );
			$result->evergreen          = 1;
			$result->expireSlotsIn      = $icp->Utils->formatTimer( 3 * 86400 );
			$result->expireDateIn       = $icp->Utils->formatTimer( 3 * 86400 );
			$result->expirationDate     = time() + ( 7 * 86400 );
			$result->availableSlots     = 50;
			$result->automaticResetDays = 90;
			$result->labelsSlots        = 'spots,spot';

			$result->color  = ICP_CountdownConstants::COLOR_BLACK;
			$result->type   = ICP_CountdownConstants::TYPE_DATE;
			$result->detect = ICP_CountdownConstants::DETECT_COOKIE;

			$result->digitsFontSize = 80;
			$result->labelsFontSize = 20;

			$result->labelsDays    = 'days,day';
			$result->labelsHours   = 'hours,hour';
			$result->labelsMinutes = 'minutes,minute';
			$result->labelsSeconds = 'seconds,second';
		}
		return $result;
	}
	public function delete( $ids ) {
		global $icp;
		$ids   = $icp->Utils->toArray( $ids );
		$array = $icp->Options->getArrayCountdowns();
		foreach ( $ids as $id ) {
			unset( $array[ $id ] );
			$icp->Options->removeIpAddressFirstSeen( $id );
		}
		$icp->Options->setArrayCountdowns( $array );
		return true;
	}
	public function copy( $ids ) {
		global $icp;
		$ids = $icp->Utils->toArray( $ids );
		if ( 0 == count( $ids ) ) {
			return false;
		}

		$array  = $icp->Options->getArrayCountdowns();
		$result = false;
		$e      = false;
		foreach ( $ids as $id ) {
			$result = true;
			if ( isset( $array[ $id ] ) ) {
				$e = clone($array[ $id ]);
				/* @var $e ICP_Countdown */
				$e->id   = 0;
				$e->name = 'Copy of ' . $e->name;
				$this->store( $e );
			}
		}
		if ( false !== $e ) {
			$icp->Ui->redirectEdit( $e->id );
		}
		return $result;
	}
	public function getDetectStrategy( $detect ) {
		$result = false;
		switch ( $detect ) {
			case ICP_CountdownConstants::DETECT_COOKIE:
				$result = new ICP_CookieDetect();
				break;
			case ICP_CountdownConstants::DETECT_IP_ADDRESS:
				$result = new ICP_IpAddressDetect();
				break;
		}
		return $result;
	}
}
