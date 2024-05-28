<?php
function icp_notice_pro_features() {
	global $icp;

	?>
	<br/>
	<div class="message updated below-h2 iwp" style="width: 100%">
		<div style="height:10px;"></div>
		<?php
		$i = 1;
		while ( $icp->Lang->H( 'Notice.ProHeader' . $i ) ) {
			$icp->Lang->P( 'Notice.ProHeader' . $i );
			echo '<br/>';
			++$i;
		}
		$i = 1;
		?>
		<br/>
		<?php

		/*$options = array('public' => TRUE, '_builtin' => FALSE);
		$q=get_post_types($options, 'names');
		if(is_array($q) && count($q)>0) {
			sort($q);
			$q=implode(', ', $q);
			$q='(<b>'.$q.'</b>)';
		} else {
			$q='';
		}*/
		$q = '';
		while ( $icp->Lang->H( 'Notice.ProFeature' . $i ) ) {
			?>
			<div style="clear:both; margin-top: 2px;"></div>
			<div style="float:left; vertical-align:middle; height:24px; margin-right:5px; margin-top:-5px;">
				<img src="<?php echo esc_url( ICP_PLUGIN_IMAGES_URI ); ?>tick.png" />
			</div>
			<div style="float:left; vertical-align:middle; height:24px;">
				<?php $icp->Lang->P( 'Notice.ProFeature' . $i, $q ); ?>
			</div>
			<?php
			++$i;
		}
		?>
		<div style="clear:both;"></div>
		<div style="height:10px;"></div>
		<div style="float:right;">
			<?php
			$url = ICP_TAB_PREMIUM_URI . '?utm_source=free-users&utm_medium=wp-cta&utm_campaign=wp-plugin';
			?>
			<a href="<?php echo esc_url_raw( $url ); ?>" target="_blank">
				<b><?php $icp->Lang->P( 'Notice.ProCTA' ); ?></b>
			</a>
		</div>
		<div style="height:10px; clear:both;"></div>
	</div>
	<br/>
	<?php
}

function icp_ui_editor() {
	global $icp;

	?>
	<h2><?php esc_html( $icp->Lang->P( 'Title.Editor' ) ); ?></h2>
	<?php

	$id                = $icp->Utils->iqs( 'id' );
	$instance          = $icp->Manager->get( $id, true );
	$icp->Form->prefix = 'Editor';
	if ( $icp->Check->is( '_action', 'Save' ) ) {
		/* @var $instance ICP_Countdown */
		$instance = $icp->Dao->Utils->qs( 'Countdown' );

		$fields = 'name|type|evergreen|expirationDate|detect|expireDateIn|color|digitsFontSize|labelsFontSize';
		$all    = true;
		$icp->Ui->validateDomain( $instance, $fields, $all );
		if ( ! $icp->Options->hasErrorMessages() ) {
			$icp->Manager->store( $instance );
			$icp->Ui->redirectManager( $instance->id );
		}
	}
	$icp->Options->writeMessages();

	$icp->Form->formStarts();
	{
		$icp->Form->hidden( 'id', $instance->id );
		$title = ( $instance->id > 0 ? 'Edit' : 'Add' );
		$icp->Form->openPanel( $title );
		{
			$fields = 'name|^type|evergreen|?expirationDate|?detect|?expireDateIn|redirectUri';
			$icp->Form->inputsForm( $fields, $instance );
		}
		$icp->Form->closePanel();

		$title = 'Aspect';
		$icp->Form->openPanel( $title );
		{
			$fields = 'color|?labelsDays|?labelsHours|?labelsMinutes|?labelsSeconds|digitsFontSize|labelsFontSize';
			$icp->Form->inputsForm( $fields, $instance );

			$buttons         = array();
			$button          = array(
				'submit' => true,
			);
			$buttons['Save'] = $button;
			$options         = array( 'buttons' => $buttons );

			icp_notice_pro_features();
			}
			$icp->Form->closePanel( $options );
			}
			$icp->Form->formEnds();
}
