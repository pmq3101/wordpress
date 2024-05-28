<?php

class ICP_LazyLoader {
	//Facebook
	public function Facebook_getAdAccounts( $accountId = 0 ) {
		global $icp;
		$result = $this->Facebook_getName();
		if ( false !== $result ) {
			return $result;
		}

		if ( 0 === $accountId || ( is_array( $accountId ) && 0 == count( $accountId ) ) ) {
			$accountId = $icp->Utils->qs( 'parentId', '' );
		}

		$result  = array();
		$profile = $icp->Options->getFacebookProfiles( $accountId );
		if ( false !== $profile && isset( $profile['adAccounts'] ) ) {
			$accounts = $profile['adAccounts'];
			foreach ( $accounts as $k => $v ) {
				if ( ! is_array( $v ) || ! isset( $v['name'] ) ) {
					$v = $k;
				} else {
					$v = $v['name'] . ' (' . $k . ')';
				}
				$result[] = array(
					'id'   => $k,
					'text' => $v,
				);
			}
		}
		return $result;
	}

	public function execute( $action ) {
		global $icp;
		$result   = array();
		$function = array( $this, $action );
		if ( $icp->Utils->functionExists( $function ) ) {
			$result = $icp->Utils->functionCall( $function );
		} else {
			$result['error'] = 'NO FUNCTION ' . $action . ' DEFINED';
		}
		return $result;
	}
	public function executeJson( $action ) {
		$json = $this->execute( $action );
		echo json_encode( $json );
		return ( count( $json ) > 0 && ! isset( $json['error'] ) );
	}

	private function getArgs( $parents, $size ) {
		global $icp;
		if ( 0 === $parents || '' == $parents || ( is_array( $parents ) && 0 == count( $parents ) ) ) {
			$parents = $icp->Utils->qs( 'parentId', 0 );
		}

		$result  = false;
		$parents = $icp->Utils->toArray( $parents );
		if ( count( $parents ) == $size ) {
			$result = $parents;
		}
		return $result;
	}
}
