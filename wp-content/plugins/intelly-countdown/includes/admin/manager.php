<?php
function icp_ui_manager_clone( $ids ) {
	global $icp;
	$success = $icp->Manager->copy( $ids );
	$icp->Options->pushMessage( $success, 'CloneCountdown' );
	$icp->Ui->redirectManager();
}
function icp_ui_manager_delete( $ids ) {
	global $icp;
	$success = $icp->Manager->delete( $ids );
	$icp->Options->pushMessage( $success, 'DeleteCountdown' );
	$icp->Ui->redirectManager();
}
function icp_ui_check_incompatible_plugins() {
	global $icp;
	if ( class_exists( 'PageExpirationRobot' ) ) {
		$icp->Options->pushWarningMessage( 'PleaseDeactivatePageExpirationRobot' );
	}
}
function icp_ui_manager() {
	global $icp;
	icp_ui_check_incompatible_plugins();
	icp_ui_track( false );
	?>
	<h2 class="mb10"><?php $icp->Lang->P( 'Title.Manager', ICP_PLUGIN_NAME, ICP_PLUGIN_VERSION ); ?></h2>
	<?php

	$icp->Form->prefix = 'Manager';
	$action            = $icp->Utils->qs( '_action', '' );
	$function          = false;
	if ( $icp->Check->nonce() && '' !== $action ) {
		$action     = strtolower( $action );
		$ids        = $icp->Utils->toArray( $icp->Utils->qs( 'ids', array() ) );
		$onlyOne    = false;
		$allowEmpty = false;
		switch ( $action ) {
			case 'clone':
				$onlyOne = true;
				break;
		}
		if ( ! $allowEmpty && ( $icp->Utils->isEmpty( $ids ) ) ) {
			$icp->Options->pushWarningMessage( 'SelectCountdownToAction' );
		} elseif ( ! $icp->Utils->isEmpty( $ids ) && count( $ids ) > 1 && $onlyOne ) {
			$icp->Options->pushWarningMessage( 'SelectOnlyOneCountdownToAction' );
		} else {
			$function = 'icp_ui_manager_' . $action;
			$icp->Utils->functionCall( $function, $ids );
			$function = true;
		}
	}

	$id = $icp->Utils->iqs( 'id', 0 );
	if ( $id > 0 ) {
		$instance = $icp->Manager->get( $id );
		if ( false !== $instance && ! $function ) {
			$icp->Options->pushSuccessMessage( 'CountdownUpdated' );
		}
	}

	$icp->Options->writeMessages();

	$items = $icp->Manager->query();
	if ( count( $items ) > 0 ) {
		$icp->Form->formStarts();
		$icp->Form->hidden( '_action', '' );
		?>
		<div style="float:left;">
			<?php
			$options = array(
				'theme' => 'primary',
				'uri'   => ICP_TAB_EDITOR_URI,
			);
			$icp->Form->button( 'Add', $options );

			$options = array(
				'theme' => 'success',
			);
			$icp->Form->submit( 'Clone', $options );

			$options = array(
				'theme'  => 'danger',
				'prompt' => true,
			);
			$icp->Form->submit( 'Delete', $options );
			?>
		</div>
		<div style="clear:both;"></div>
		<br/>

		<?php
			$fields = '@id|#name|evergreen|type|expire|detect|shortcode';
			$args   = array(
				'expire'    => array(
					'function' => 'icp_column_expire',
				),
				'detect'    => array(
					'function' => 'icp_column_detect',
				),
				'shortcode' => array(
					'function' => 'icp_column_shortcode',
				),
			);
			$icp->Form->inputTable( $fields, $items, $args );
			?>
		<?php
		$icp->Form->formEnds();
		icp_notice_pro_features();
	} else {
		?>
		<h2><?php $icp->Lang->P( 'EmptyCountdownList', ICP_TAB_EDITOR_URI ); ?></h2>
		<?php
	}
}

function icp_column_expire( $v ) {
	global $icp;
	/* @var $v ICP_Countdown */
	$text = ICP_CountdownConstants::TYPE_DATE == $v->type ? $v->expireDateIn : $v->expireSlotsIn;
	if ( ! $v->evergreen ) {
		$text = $icp->Utils->formatSmartDatetime( $v->expirationDate );
	}
	echo esc_html( $text );
}
function icp_column_detect( $v ) {
	/* @var $v ICP_Countdown */
	global $icp;
	$values = $icp->Form->options( $v, 'detect' );
	$text   = $icp->Form->optionsText( $values, $v->detect );
	echo esc_html( $v->evergreen ? $text : 'None' );
}
function icp_column_shortcode( $v ) {
	/* @var $v ICP_Countdown */
	?>
	<pre>[ec id="<?php echo esc_attr( $v->id ); ?>"]</pre>
	<?php
}
