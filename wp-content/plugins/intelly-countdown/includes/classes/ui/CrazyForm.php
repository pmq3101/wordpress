<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ICP_CrazyForm {
	var $prefix     = '';
	var $namePrefix = '';
	var $readonly   = false;

	var $labels = true;
	var $newline;
	var $helps       = false;
	var $textCenter  = false;
	var $tooltips    = false;
	var $blockOpened = false;

	private $search              = false;
	private $noncePresent        = false;
	private $hiddenActionCreated = false;
	private $buttonPresent       = false;
	var $icon                    = false;

	public function __construct() {
	}

	public function newline() { ?>
		<div class="icp-form-newline"></div>
		<?php
	}

	public function formStarts( $options = array() ) {
		global $icp;
		$defaults = array(
			'method'    => 'POST',
			'action'    => '',
			'openBlock' => false,
		);
		$options  = $icp->Utils->parseArgs( $options, $defaults );
		?>
		<form method="<?php echo esc_attr( $options['method'] ); ?>" action="<?php echo esc_url( $options['action'] ); ?>">
		<?php
		if ( $options['openBlock'] ) {
			$this->openBlock();
		}
	}
	public function formEnds( $options = array() ) {
		global $icp;
		$defaults = array(
			'noncePresent'  => $this->noncePresent,
			'buttonPresent' => $this->buttonPresent,
		);
		$options  = $icp->Utils->parseArgs( $options, $defaults );

		if ( ! $options['noncePresent'] ) {
			$this->nonce();
		}
		if ( ! $options['buttonPresent'] ) {
			$this->submit();
		}
		$this->closeBlock();
		?>
		</form>
		<?php /*<div style="clear:both;"></div>*/ ?>
		<?php
		$this->noncePresent = false;
	}
	public function divStarts( $args = array() ) {
		global $icp;

		if ( is_bool( $args ) ) {
			$args = array( 'style' => 'display:' . ( $args ? 'block' : 'none' ) );
		}

		$defaults = array();
		$other    = $icp->Utils->getTextArgs( $args, $defaults );
		?>
		<div <?php echo wp_kses_post( $other ); ?>>
		<?php
	}
	public function divEnds( $clear = false ) {
		?>
		</div>
		<?php if ( $clear ) { ?>
			<div style="clear:both;"></div>
		<?php } ?>
		<?php
	}

	public function i( $message, $v1 = null, $v2 = null, $v3 = null, $v4 = null, $v5 = null ) {
		global $icp;
		?>
		<i><?php $icp->Lang->P( $message, $v1, $v2, $v3, $v4, $v5 ); ?></i>
		<?php
	}
	public function p( $message, $v1 = null, $v2 = null, $v3 = null, $v4 = null, $v5 = null ) {
		global $icp;
		?>
		<p style="font-weight:bold;">
			<?php
			$icp->Lang->P( $message, $v1, $v2, $v3, $v4, $v5 );
			if ( $icp->Lang->H( $message . 'Subtitle' ) ) {
				?>
				<br/>
				<span style="font-weight:normal;">
					<i><?php $icp->Lang->P( $message . 'Subtitle', $v1, $v2, $v3, $v4, $v5 ); ?></i>
				</span>
			<?php } ?>
		</p>
		<?php
	}
	public function br() {
		?>
		<br/>
		<?php
	}
	public function clearBoth() {
		?>
		<div style="clear:both;"></div>
		<?php
	}
	private function getTooltipAttributes( $tooltip, $options = array(), $echo = true ) {
		global $icp;
		if ( false === $tooltip || '' == $tooltip ) {
			return;
		}

		$data = array(
			'data-toggle'    => 'tooltip',
			'data-placement' => 'top',
			'title'          => $icp->Lang->L( $tooltip ),
		);
		$dump = '';
		foreach ( $data as $k => $v ) {
			$dump .= ' ' . $k . '="' . str_replace( '"', '', $v ) . '"';
		}
		if ( $echo ) {
			echo esc_attr( $dump );
		} else {
			return $dump;
		}
	}
	private function openInput( $name, $options = array() ) {
		global $icp;

		$defaults = array(
			'name'             => $name,
			'class'            => ( $this->icon ? 'field prepend-icon' : 'field prepend-noicon' ),
			'label'            => true,
			'textLabel'        => '',
			'md9'              => true,
			'style'            => '',
			'labelPrefix'      => '',
			'col-md'           => 'col-md-3',
			'tooltipPlacement' => 'top',
			'row-hidden'       => false,
		);
		$options  = $icp->Utils->parseArgs( $options, $defaults );
		$k        = $this->prefix;
		if ( '' != $k ) {
			$k .= '.';
		}
		$name  = $options['name'];
		$name  = str_replace( '[]', '', $name );
		$class = $options['class'];
		$k    .= $name;

		$label = $k;
		if ( is_string( $options['label'] ) ) {
			$label = $options['label'];
		}

		$tooltip = ( isset( $options['tooltip'] ) ? $options['tooltip'] : '' );
		if ( ! isset( $options['tooltip'] ) && $this->tooltips ) {
			$tooltip = $label . '.Tooltip';
		}
		//$mb=($this->search ? 'mb15' : 'row mb10');
		$mb = ( $this->search ? 'mb15' : 'row mb0' );
		if ( $this->search ) {
			?>
			<h5><small><?php $icp->Lang->P( $label ); ?></small></h5>
			<?php
		}
		$style = '';
		if ( $options['row-hidden'] ) {
			$style .= '; display:none;';
		}
		?>
		<div class="section <?php echo esc_attr( $mb ); ?>" id="<?php $this->getName( $name ); ?>-row" style="<?php echo esc_attr( $style ); ?>">
			<?php if ( ! $this->search ) { ?>
				<label for="<?php $this->getName( $name ); ?>" class="field-label <?php echo esc_attr( $options['col-md'] ); ?> text-left" style="<?php echo esc_attr( $options['style'] ); ?>" <?php $this->getTooltipAttributes( $tooltip, $options ); ?>>
					<?php
					$l = '';
					if ( isset( $options['textLabel'] ) && '' != $options['textLabel'] ) {
						$l = $options['textLabel'];
					} else {
						if ( isset( $options['labelPrefix'] ) && '' != $options['labelPrefix'] ) {
							$l = $icp->Lang->L( $options['labelPrefix'] );
						}
						$l .= ' ' . $icp->Lang->L( $label );
					}
					$l = trim( $l );
					echo esc_attr( $l );
					?>
				</label>
				<?php if ( $options['md9'] ) { ?>
					<div class="col-md-9">
				<?php } ?>
				<?php
			}
			if ( is_bool( $options['label'] ) && $options['label'] ) {
				?>
				<label for="<?php $this->getName( $name ); ?>" class="<?php echo esc_attr( $class ); ?>">
				<?php
			}
	}
	private function closeInput( $name, $options = array() ) {
		global $icp;

		$defaults = array(
			'name'  => $name,
			'class' => 'field-icon',
			'label' => true,
			'md9'   => true,
		);
		$options  = $icp->Utils->parseArgs( $options, $defaults );
		$name     = $options['name'];
		$icon     = '';
		if ( $this->icon ) {
			if ( ! isset( $options['icon'] ) ||
				( false !== $options['icon'] ) && '' !== $options['icon'] ) {
				$icon = $this->getIcon( $name );
			}
		}
		if ( '' != $icon ) {
			if ( $options['class'] == $defaults['class'] ) {
				?>
				<label for="<?php $this->getName( $name ); ?>" class="field-icon">
					<i class="fa fa-<?php echo esc_attr( $icon ); ?>"></i>
				</label>
			<?php } else { ?>
				<i class="<?php echo esc_attr( $options['class'] ); ?>"></i>
				<?php
			}
		}
		if ( $options['label'] ) {
			?>
			</label>
			<?php
		}
		if ( ! $this->search ) {
			if ( isset( $options['afterLabel'] ) && '' != $options['afterLabel'] ) {
				echo esc_attr( $options['afterLabel'] );
			}
			if ( $options['md9'] ) {
				?>
			</div>
				<?php
			}
		}
		?>
	</div>
		<?php
	}

	public function getIcon( $name ) {
		global $icp;
		$icons  = array(
			'user'               => 'name|surname|username|user',
			'barcode'            => 'taxCode|key',
			'envelope-o'         => 'email|send',
			'at'                 => 'email',
			'phone'              => 'phone',
			'lock'               => 'password',
			'unlock'             => 'confirmPassword|disconnect',
			'mobile'             => 'mobile',
			'fax'                => 'fax',
			'map-marker'         => 'address',
			'certificate'        => 'star|zip',
			'building-o'         => 'region|province|country|city|place',
			'euro'               => 'price|currency|amount|cost|advance',
			'edit'               => 'note|description|body|subject|comment',
			'globe'              => 'website|site',
			'tag'                => 'tag',
			'calendar'           => 'date|dt1|dt2',
			'home'               => 'home|company',
			'clock-o'            => 'time',
			'arrows-v'           => 'scroll',
			'floppy-o'           => 'save',
			'angle-double-right' => 'next',
			'angle-double-left'  => 'previous|back',
			'trash-o'            => 'remove|delete|trash',
			'refresh'            => 'sync|refresh|change',
			'plus-circle'        => 'add|plus',
			'clone'              => 'clone',
			'ban'                => 'ban|cancel|abort',
			'facebook-square'    => 'facebook|fb|fbconnect',
			'plug'               => 'plug|authorize',
			'bug'                => 'bug|error',
			'sign-in'            => 'login',
			'thumbs-o-down'      => 'suspend|stop',
			'thumbs-o-up'        => 'activate',
			'slack'              => 'id|day',
			'undo'               => 'undo',
			'pencil'             => 'edit',
			'check-square-o'     => 'finish',
			'check'              => 'confirm',
			'upload'             => 'import',
		);
		$result = $icp->Utils->match( $name, $icons, 'question' );
		return $result;
	}

	private function timerText( $name, $suffix, $value ) {
		global $icp;
		$name .= $suffix;

		$options = array(
			'noLayout' => true,
			'class'    => 'gui-input col-xs-1 text-center',
			'style'    => 'width:10%',
		);
		$value   = intval( $value );
		$this->number( $name, $value, $options );
		?>
		<label for="<?php $this->getName( $name ); ?>" class="field-label col-xs-1 text-center" style="width:10%">
			<?php $icp->Lang->P( lcfirst( $suffix ) ); ?>
		</label>
		<?php
	}
	public function timer( $name, $value = '', $options = array() ) {
		global $icp;
		if ( ! is_array( $options ) ) {
			$options = array();
		}

		$value  = $icp->Utils->get( $name, $value, $value );
		$value  = $icp->Utils->formatTimer( $value );
		$values = explode( ':', $value );

		if ( $icp->Utils->get( $options, 'noLayout', false ) === false ) {
			$args = array();
			if ( isset( $options['row-hidden'] ) ) {
				$args['row-hidden'] = $options['row-hidden'];
			}
			if ( isset( $options['textLabel'] ) ) {
				$args['textLabel'] = $options['textLabel'];
			}
			$args['label'] = false;
			$this->openInput( $name, $args );
		}

		$this->timerText( $name, 'Days', $values[0] );
		$this->timerText( $name, 'Hours', $values[1] );
		$this->timerText( $name, 'Minutes', $values[2] );
		$this->timerText( $name, 'Seconds', $values[3] );

		$options['class'] = 'icp-timer';
		$this->hidden( $name, $value, $options );

		if ( $icp->Utils->get( $options, 'noLayout', false ) === false ) {
			$args          = array();
			$args['label'] = false;
			$this->closeInput( $name, $args );
		}
	}
	public function text( $name, $value = '', $options = array() ) {
		global $icp;

		$value = $icp->Utils->get( $name, $value, $value );
		$type  = 'text';
		if ( isset( $options['type'] ) ) {
			$type = $options['type'];
		}

		$defaults = array( 'class' => 'gui-input' );
		if ( $this->textCenter ) {
			$defaults['class'] .= ' text-center';
			if ( isset( $options['class'] ) ) {
				$options['class'] .= ' text-center';
			}
		}
		$other   = $icp->Utils->getTextArgs( $options, $defaults, 'type|label|noLayout|textLabel' );
		$options = $icp->Utils->parseArgs( $options, $defaults );

		if ( $icp->Utils->get( $options, 'noLayout', false ) === false ) {
			$args = array();
			if ( isset( $options['row-hidden'] ) ) {
				$args['row-hidden'] = $options['row-hidden'];
			}
			if ( isset( $options['textLabel'] ) ) {
				$args['textLabel'] = $options['textLabel'];
			}
			$this->openInput( $name, $args );
		}
		?>
		<input type="<?php echo esc_attr( $type ); ?>" id="<?php $this->getName( $name ); ?>" name="<?php $this->getName( $name, $options ); ?>" value="<?php echo esc_attr( $value ); ?>" <?php echo wp_kses_post( $other ); ?> />
		<?php
		if ( $icp->Utils->get( $options, 'noLayout', false ) === false ) {
			$this->closeInput( $name, $args );
		}
	}
	private function getName( $name, $options = array(), $echo = true ) {
		$name = $this->namePrefix . $name;
		$name = str_replace( '.', '_', $name );
		if ( false === $options ) {
			$options = array();
			$echo    = false;
		}

		//if(!is_array($options)) {
		//    $options=array();
		//}
		//dopo se lo faccio potrebbe succedere un casino con le validazioni etc
		//inoltre poi un campo senza nome nn può essere READONLY
		//if(count($options)>0 && isset($options['readonly']) && $options['readonly']!='') {
		//    $name='';
		//}

		if ( $echo ) {
			echo esc_attr( $name );
		} else {
			return $name;
		}
	}
	public function hidden( $name, $value = '', $options = null ) {
		global $icp;
		if ( '_action' == $name ) {
			$this->hiddenActionCreated = true;
		}
		$value = $icp->Utils->get( $name, $value, $value );
		if ( is_bool( $value ) ) {
			$value = ( $value ? 1 : 0 );
		}
		$defaults = array();
		$other    = $icp->Utils->getTextArgs( $options, $defaults, 'type|label|noLayout|textLabel' );
		?>
		<input type="hidden" id="<?php $this->getName( $name ); ?>" name="<?php $this->getName( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" <?php echo wp_kses_post( $other ); ?> />
		<?php
	}

	public function nonce( $action = 'nonce', $name = '_wpnonce', $referer = true, $echo = true ) {
		if ( '' == $name ) {
			$name = $action;
		}
		$this->noncePresent = true;
		wp_nonce_field( $action, $name, $referer, $echo );
	}

	public function textarea( $name, $value = '', $options = null ) {
		global $icp;

		$value = $icp->Utils->get( $name, $value, $value );
		//$defaults=array('rows'=>10, 'class'=>'gui-textarea');
		$defaults = array( 'class' => 'gui-textarea' );
		$other    = $icp->Utils->getTextArgs( $options, $defaults, 'noLayout|textLabel' );
		$options  = $icp->Utils->parseArgs( $options, $defaults );

		if ( $icp->Utils->get( $options, 'noLayout', false ) === false ) {
			$args = array();
			if ( isset( $options['row-hidden'] ) ) {
				$args['row-hidden'] = $options['row-hidden'];
			}
			if ( isset( $options['textLabel'] ) ) {
				$args['textLabel'] = $options['textLabel'];
			}
			$this->openInput( $name, $args );
		}
		?>
			<textarea dir="ltr" dirname="ltr" id="<?php $this->getName( $name ); ?>" name="<?php $this->getName( $name, $options ); ?>" <?php echo wp_kses_post( $other ); ?> ><?php echo esc_textarea( $value ); ?></textarea>
		<?php
		if ( $icp->Utils->get( $options, 'noLayout', false ) === false ) {
			$args = array();
			$this->closeInput( $name, $args );
		}
	}
	public function email( $name, $value = '', $options = null ) {
		global $icp;
		$defaults = array( 'type' => 'email' );
		$options  = $icp->Utils->parseArgs( $options, $defaults );
		$this->text( $name, $value, $options );
	}
	public function password( $name, $value = '', $options = null ) {
		global $icp;
		$defaults = array( 'type' => 'password' );
		$options  = $icp->Utils->parseArgs( $options, $defaults );
		$this->text( $name, $value, $options );
	}
	public function currency( $name, $value = '', $options = null ) {
		global $icp;
		//number does not support comma
		//$defaults=array('type'=>'number');
		//$options=$ec->Utils->parseArgs($options, $defaults);
		$this->text( $name, $value, $options );
	}
	public function number( $name, $value = '', $options = null ) {
		global $icp;
		$defaults = array( 'type' => 'number' );
		$options  = $icp->Utils->parseArgs( $options, $defaults );
		$this->text( $name, $value, $options );
	}
	public function tags( $name, $value, $values, $options = null ) {
		global $icp;
		if ( ! is_array( $options ) ) {
			$options = array();
		}
		$options['type'] = 'tags';
		$value           = $icp->Utils->toArray( $value );
		foreach ( $value as $k ) {
			$exists = false;
			foreach ( $values as $v ) {
				if ( $v['id'] == $k ) {
					$exists = true;
					break;
				}
			}

			if ( ! $exists ) {
				$values[] = array(
					'id'   => $k,
					'text' => $k,
				);
			}
		}
		$this->dropdown( $name, $value, $values, true, $options );
	}
	public function multiselect( $name, $value, $values, $options = array() ) {
		if ( ! is_array( $options ) ) {
			$options = array();
		}
		$options['type']     = 'multiselect';
		$options['optgroup'] = true;
		$this->dropdown( $name, $value, $values, true, $options );
	}
	public function dropdown( $name, $value, $values, $multiple = false, $options = null ) {
		global $icp;
		$value = $icp->Utils->get( $name, $value, $value );

		if ( ! is_array( $options ) ) {
			$options = array();
		}
		if ( isset( $options['readonly'] ) ) {
			$options['disabled'] = 'disabled';
			unset( $options['readonly'] );
		}
		if ( isset( $options['multiple'] ) ) {
			$multiple = $options['multiple'];
			unset( $options['multiple'] );
		}
		if ( ! isset( $options['type'] ) ) {
			$options['type'] = 'dropdown';
		}
		if ( ! isset( $options['class'] ) ) {
			$options['class'] = '';
		}
		$options['class'] .= ' icp-' . $options['type'];

		$help = $this->prefix;
		if ( '' != $help ) {
			$help .= '.';
		}
		$help .= $name . '.Help';
		if ( $icp->Lang->H( $help ) ) {
			$help = $icp->Lang->L( $help );
		} else {
			$help = 'Dropdown.' . ( $multiple ? 'SelectAtLeastOneValue' : 'SelectOneValue' );
			if ( 'tags' == $options['type'] ) {
				$help = 'Dropdown.SelectTagValue';
			}
			$help = $icp->Lang->L( $help );
		}

		$defaults = array(
			'class'      => $options['class'],
			'icp-ajax'   => '',
			'icp-lazy'   => '',
			'icp-domain' => '',
			'icp-class'  => '',
			'icp-help'   => $help,
			'optgroup'   => false,
		);
		$other    = $icp->Utils->getTextArgs( $options, $defaults, 'title|noLayout|type|optgroup|textLabel' );
		$options  = $icp->Utils->parseArgs( $options, $defaults );

		if ( ! is_array( $value ) ) {
			$value = array( $value );
		}
		if ( is_string( $values ) ) {
			$values = explode( ',', $values );
		}
		if ( is_array( $values ) && count( $values ) > 0 ) {
			if ( ! isset( $values[0]['id'] ) && ! isset( $values[0]['text'] ) ) {
				//this is a normal array so I use the values for "id" field and the "name" into the txt file
				$temp = array();
				foreach ( $values as $v ) {
					if ( is_numeric( $v ) || ! is_null( $v ) ) {
						$temp[] = array(
							'id'   => $v,
							'text' => $icp->Lang->L( $this->prefix . '.' . $name . '.' . $v ),
						);
					}
				}
				$values = $temp;
			}
		}

		foreach ( $value as $v ) {
			if ( is_numeric( $v ) && intval( $v ) == -1 ) {
				//[All] option
				$value = array( -1 );
				break;
			}
		}

		//sort array
		$values = $icp->Utils->sortOptions( $values );
		if ( $icp->Utils->get( $options, 'noLayout', false ) === false ) {
			$args = array( 'class' => 'field select' );
			if ( isset( $options['row-hidden'] ) ) {
				$args['row-hidden'] = $options['row-hidden'];
			}
			if ( isset( $options['textLabel'] ) ) {
				$args['textLabel'] = $options['textLabel'];
			}
			$this->openInput( $name, $args );
		}
		?>
		<select id="<?php $this->getName( $name ); ?>" name="<?php $this->getName( $name, $options ); ?><?php echo ( $multiple ? '[]' : '' ); ?>" <?php echo ( $multiple ? 'multiple="multiple"' : '' ); ?> <?php echo wp_kses_post( $other ); ?> style="display:none;">
			<?php
			if ( $options['optgroup'] ) {
				$label = $this->prefix . '.' . $name . '.Optgroup';
				echo '<optgroup label="' . esc_attr( $icp->Lang->L( $label ) . '">' );
			}
			foreach ( $values as $v ) {
				$other = '';
				if ( isset( $v['style'] ) ) {
					$other .= ' style="' . $v['style'] . '"';
				}
				if ( isset( $v['data'] ) ) {
					$other .= ' data="' . $v['data'] . '"';
				}
				if ( isset( $v['show'] ) ) {
					$other .= ' show="' . $v['show'] . '"';
				}

				$selected = '';
				if ( $icp->Utils->inArray( $v['id'], $value ) ) {
					$selected = ' selected="selected"';
				}
				?>
				<option value="<?php echo esc_attr( $v['id'] ); ?>" <?php echo wp_kses_post( $selected ); ?> <?php echo wp_kses_post( $other ); ?>><?php echo wp_kses_post( isset( $v['text'] ) ? $v['text'] : $v['name'] ); ?></option>
				<?php
			}
			if ( $options['optgroup'] ) {
				echo '</optgroup>';
			}
			?>
		</select>
		<?php
		if ( $icp->Utils->get( $options, 'noLayout', false ) === false ) {
			$args = array( 'icon' => false );
			$this->closeInput( $name, $args );
		}
	}

	public function checklist( $name, $value, $values, $options = null ) {
		global $icp;
		$defaults = array( 'type' => 'checkbox' );
		$options  = $icp->Utils->parseArgs( $options, $defaults );

		$selected = $icp->Utils->get( $name, $value, $value );
		$selected = $icp->Utils->toArray( $selected );

		if ( $icp->Utils->get( $options, 'noLayout', false ) === false ) {
			$args = array(
				//switch-round
				'class' => 'switch switch-primary block mt5',
				'icon'  => false,
				'label' => false,
			);
			if ( isset( $options['row-hidden'] ) ) {
				$args['row-hidden'] = $options['row-hidden'];
			}
			if ( isset( $options['textLabel'] ) ) {
				$args['textLabel'] = $options['textLabel'];
			}
			$this->openInput( $name, $args );
		}
		?>
		<div class="option-group field">
			<?php
			foreach ( $values as $v ) {

				$k = $v['id'];
				if ( isset( $v['text'] ) ) {
					$v = $v['text'];
				} else {
					$v = $v['name'];
				}

				$checked = in_array( $k, $selected );
				?>
				<label class="option option-primary block mt10">
					<input type="<?php echo esc_attr( $options['type'] ); ?>" name="<?php $this->getName( $name, $options ); ?>[]" id="<?php $this->getName( $name ); ?>_<?php echo esc_attr( $k ); ?>" value="<?php echo esc_attr( $selected ); ?>" <?php echo ( $checked ? 'checked="checked"' : '' ); ?>>
					<span class="<?php echo esc_attr( $options['type'] ); ?>"></span><?php echo wp_kses_post( $v ); ?>
				</label>
			<?php } ?>
		</div>
		<?php
		if ( $icp->Utils->get( $options, 'noLayout', false ) === false ) {
			$this->closeInput( $name, $args );
		}
	}
	public function radiolist( $name, $value, $values, $options = null ) {
		if ( ! $options || ! is_array( $options ) ) {
			$options = array();
		}
		$options['type'] = 'radio';
		$this->checklist( $name, $value, $values, $options );
	}

	public function checkbox( $name, $value = '', $selected = 1, $options = array() ) {
		if ( ! is_array( $options ) ) {
			$options = array();
		}
		$options['class'] = 'checkbox-custom checkbox-primary block mt5'; //mb5 mt10
		$this->toggle( $name, $value, $selected, $options );
	}
	public function toggle( $name, $value = '', $selected = 1, $options = array() ) {
		global $icp;

		$value = $icp->Utils->get( $name, $value, $value );
		if ( is_bool( $value ) ) {
			$value = ( $value ? 1 : 0 );
		}
		$checked = ( $value == $selected );
		$id      = $name;
		if ( $icp->Utils->endsWith( $id, '[]' ) ) {
			$id  = substr( $id, 0, strlen( $id ) - 2 );
			$id .= '_' . $selected;
		}

		$defaults  = array(
			'data-on'    => $icp->Lang->L( 'Toggle.Yes' ),
			'data-off'   => $icp->Lang->L( 'Toggle.No' ),
			'afterText'  => '',
			'class'      => 'switch switch-round switch-primary block mt5', //mt10

			'ui-visible' => '',
		);
		$options   = $icp->Utils->parseArgs( $options, $defaults );
		$otherText = '';
		if ( $options['ui-visible'] ) {
			$otherText = ' ui-visible="' . $options['ui-visible'] . '" ';
		}
		$disabled = '';
		if ( isset( $options['disabled'] ) || isset( $options['readonly'] ) ) {
			$options['readonly'] = 'readonly';
			$disabled            = 'disabled="disabled"';
		}

		if ( $icp->Utils->get( $options, 'noLayout', false ) === false ) {
			$args = array(
				'class' => $options['class'],
				'icon'  => false,
			);
			if ( isset( $options['row-hidden'] ) ) {
				$args['row-hidden'] = $options['row-hidden'];
			}
			if ( isset( $options['textLabel'] ) ) {
				$args['textLabel'] = $options['textLabel'];
			}
			$this->openInput( $id, $args );
		} else {
			?>
			<label for="<?php $this->getName( $id ); ?>" class="<?php echo esc_attr( $options['class'] ); ?>">
			<?php
		}

		$dataCss = '';
		if ( strpos( $options['class'], 'checkbox' ) !== false ) {
			$dataCss = 'height:21px; ';
		}
		?>
		<input type="checkbox" name="<?php $this->getName( $name, $options ); ?>" id="<?php $this->getName( $id ); ?>" value="<?php echo esc_attr( $selected ); ?>" <?php echo ( $checked ? 'checked="checked"' : '' ); ?> <?php echo wp_kses_post( $disabled ); ?>  <?php echo wp_kses_post( $otherText ); ?>>
		<label for="<?php echo esc_attr( $id ); ?>" data-on="<?php echo esc_attr( $options['data-on'] ); ?>" data-off="<?php echo esc_attr( $options['data-off'] ); ?>" style="<?php echo esc_attr( $dataCss ); ?>"></label>
		<?php if ( '' != $options['afterText'] ) { ?>
			<span><?php echo wp_kses_post( $options['afterText'] ); ?></span>
		<?php } ?>

		<?php
		if ( $icp->Utils->get( $options, 'noLayout', false ) === false ) {
			$this->closeInput( $name, $args );
		} else {
			?>
			</label>
			<?php
		}
	}

	public function color( $name, $value = '', $options = null ) {
		global $icp;

		$value = $icp->Utils->get( $name, $value, $value );
		if ( $icp->Utils->get( $options, 'noLayout', false ) === false ) {
			$args = array();
			if ( isset( $options['row-hidden'] ) ) {
				$args['row-hidden'] = $options['row-hidden'];
			}
			if ( isset( $options['textLabel'] ) ) {
				$args['textLabel'] = $options['textLabel'];
			}
			$this->openInput( $name, $args );
		}
		?>
		<div class="input-group colorpicker-component cursor icp-colorpicker">
			<span class="input-group-addon">
				<i></i>
			</span>
			<input type="text" id="<?php $this->getName( $name ); ?>" name="<?php $this->getName( $name, $options ); ?>" value="<?php echo esc_attr( $value ); ?>" class="gui-input" />
		</div>
		<?php
		if ( $icp->Utils->get( $options, 'noLayout', false ) === false ) {
			$this->closeInput( $name, $args );
		}
	}
	public function date( $name, $value = '', $options = null ) {
		global $icp;

		$value = $icp->Utils->get( $name, $value, $value );
		$value = $icp->Utils->formatDate( $value );

		$defaults = array( 'class' => 'gui-input icp-date' );
		$options  = $icp->Utils->parseArgs( $options, $defaults );
		$this->text( $name, $value, $options );
	}
	public function time( $name, $value = '', $options = null ) {
		global $icp;

		$value = $icp->Utils->get( $name, $value, $value );
		$value = $icp->Utils->formatTime( $value );

		$defaults = array( 'class' => 'gui-input icp-time' );
		$options  = $icp->Utils->parseArgs( $options, $defaults );
		$this->text( $name, $value, $options );
	}
	public function datetime( $name, $value = '', $options = null ) {
		global $icp;

		$value = $icp->Utils->get( $name, $value, $value );
		$value = $icp->Utils->formatDatetime( $value );

		$defaults = array( 'class' => 'gui-input icp-datetime' );
		$options  = $icp->Utils->parseArgs( $options, $defaults );
		$this->text( $name, $value, $options );
	}
	function getMasterAjaxDomain( $instance, $name, $value, &$options, $params = array() ) {
		global $icp;
		$result = false;

		if ( ! is_array( $params ) ) {
			$params = array();
		}
		if ( isset( $params['values'] ) ) {
			$options['values'] = $params['values'];
			return true;
		}

		if ( is_array( $instance ) && true === $name ) {
			//tricks :(
			$column = $instance;
		} else {
			$column = $icp->Dao->Utils->getColumn( $instance, $name );
		}

		$parentId = false;
		$parent   = $icp->Utils->get( $column, 'ui-master', '' );
		if ( '' !== $parent ) {
			$array    = explode( '|', $parent );
			$parentId = array();
			foreach ( $array as $v ) {
				$parentId[] = $icp->Utils->get( $instance, $v );
			}
			$parentId              = implode( '|', $parentId );
			$options['icp-master'] = $parent;
			$result                = true;
		}
		//domain
		$domain = $icp->Utils->get( $column, 'ui-domain', '' );
		if ( '' !== $domain ) {
			$options['icp-domain'] = $domain;
			$_POST['domain']       = $domain;
			$result                = true;
		}

		//ajax
		$action = $icp->Utils->get( $column, 'ui-ajax', '' );
		if ( '' !== $action && method_exists( $icp->Lazy, $action ) ) {
			$options['icp-ajax'] = $action;

			$_POST['parentId'] = $parentId;
			$values            = call_user_func( array( $icp->Lazy, $action ), $params );
			unset( $_POST['parentId'] );

			$options['values'] = $values;
			$result            = true;
		}

		//lazy
		$action = $icp->Utils->get( $column, 'ui-lazy', '' );
		if ( '' !== $action && method_exists( $icp->Lazy, $action ) ) {
			$options['icp-lazy'] = $action;

			$_POST['parentId'] = $parentId;
			$values            = call_user_func( array( $icp->Lazy, $action ), $params );
			unset( $_POST['parentId'] );

			$options['values'] = $values;
			$result            = true;
		}

		if ( isset( $options['values'] ) && isset( $column['ui-all'] ) && $icp->Utils->isTrue( $column['ui-all'] ) ) {
			$first             = array();
			$first[]           = array(
				'id'   => -1,
				'text' => '[' . $icp->Lang->L( 'All' ) . ']',
			);
			$options['values'] = $icp->Utils->arrayPush( $first, $options['values'] );
		}

		unset( $_POST['domain'] );
		return $result;
	}


	public function inputsForm( $fields, $instance, $param = array() ) {
		global $icp;
		$fields = $icp->Utils->toArray( $fields );
		foreach ( $fields as $v ) {
			$this->inputForm( $instance, $v, $param );
		}
	}
	public function inputForm( $instance, $name, $params = array() ) {
		global $icp;
		$options = array();
		if ( isset( $params['noLayout'] ) ) {
			$options['noLayout'] = $params['noLayout'];
			unset( $params['noLayout'] );
		}

		$name   = $icp->Ui->getFieldOptions( $instance, $name, $options );
		$column = $icp->Dao->Utils->getColumn( $instance, $name );
		if ( isset( $column['alias'] ) ) {
			$name = $column['alias'];
		}

		if ( isset( $column['ui-type'] ) ) {
			$value  = $icp->Utils->get( $instance, $name, '' );
			$exists = isset( $column['ui-exists'] );
			if ( $value || ! $exists ) {
				if ( isset( $options['hidden'] ) && $options['hidden'] ) {
					$value = $icp->Dao->Utils->encode( $instance, $name, $value, false );
					$this->hidden( $name, $value );
				} else {
					$multiple     = $icp->Utils->get( $column, 'ui-multiple', false );
					$multiple     = $icp->Utils->isTrue( $multiple );
					$autocomplete = $icp->Utils->get( $column, 'ui-autocomplete', '' );

					if ( '' != $autocomplete ) {
						$options['autocomplete'] = $autocomplete;
					}

					//$prefix=get_class($instance);
					//$prefix=str_replace(ICP_PLUGIN_PREFIX, '', $prefix).'_';
					$type = strtolower( $column['ui-type'] );
					switch ( $type ) {
						case 'dropdown':
						case 'tags':
						case 'multiselect':
							$values              = $this->options( $instance, $name );
							$options['values']   = $values;
							$options['multiple'] = $multiple;
							//this function can override $options['values'] elements
							$this->getMasterAjaxDomain( $instance, $name, $value, $options, $params );
							break;
						case 'radiolist':
						case 'checklist':
							$values            = $this->options( $instance, $name );
							$options['values'] = $values;
							break;
					}
					$this->inputComponent( $type, $name, $value, $options );
				}
			}
		}
	}
	public function inputComponent( $type, $name, $value, $options = array() ) {
		$values   = array();
		$multiple = false;
		$selected = 1;
		$md       = '';
		if ( isset( $options['col-md'] ) ) {
			$md = $options['col-md'];
			unset( $options['col-md'] );
		}
		if ( isset( $options['selected'] ) ) {
			$selected = $options['selected'];
			unset( $options['selected'] );
		}
		if ( isset( $options['values'] ) ) {
			$values = $options['values'];
			unset( $options['values'] );
		}
		if ( isset( $options['multiple'] ) && $options['multiple'] ) {
			$multiple = true;
			unset( $options['multiple'] );
		}

		if ( '' != $md ) {
			echo "\n<div class=\"" . esc_attr( $md ) . "\">\n";
		}
		$type = strtolower( $type );
		switch ( $type ) {
			case 'color':
			case 'colorpicker':
				$this->color( $name, $value, $options );
				break;
			case 'text':
				$this->text( $name, $value, $options );
				break;
			case 'timer':
				$this->timer( $name, $value, $options );
				break;
			case 'textarea':
				$this->textarea( $name, $value, $options );
				break;
			case 'hidden':
				$this->hidden( $name, $value, $options );
				break;
			case 'currency':
				$this->currency( $name, $value, $options );
				break;
			case 'number':
				$this->number( $name, $value, $options );
				break;
			case 'password':
				$this->password( $name, $value, $options );
				break;
			case 'email':
				$this->email( $name, $value, $options );
				break;
			case 'dropdown':
				$this->dropdown( $name, $value, $values, $multiple, $options );
				break;
			case 'multiselect':
				$this->multiselect( $name, $value, $values, $options );
				break;
			case 'tags':
				$this->tags( $name, $value, $values, $options );
				break;
			case 'date':
				$this->date( $name, $value, $options );
				break;
			case 'time':
				$this->time( $name, $value, $options );
				break;
			case 'datetime':
				$this->datetime( $name, $value, $options );
				break;
			case 'toggle':
				$this->toggle( $name, $value, 1, $options );
				break;
			case 'check':
				$this->checkbox( $name, $value, $selected, $options );
				break;
			case 'checklist':
				$this->checklist( $name, $value, $values, $options );
				break;
			case 'radiolist':
				$this->radiolist( $name, $value, $values, $options );
				break;
		}
		if ( '' != $md ) {
			echo "\n</div>\n";
		}
	}
	public function options( $class, $name ) {
		global $icp;

		$values         = array();
		$column         = $icp->Dao->Utils->getColumn( $class, $name );
		$dropdownPrefix = $icp->Utils->upperUnderscoreCase( $name ) . '_';
		$dropdownPrefix = str_replace( '_IDS_', '_', $dropdownPrefix );
		$dropdownPrefix = str_replace( '_ID_', '_', $dropdownPrefix );
		if ( isset( $column['ui-prefix'] ) && '' != $column['ui-prefix'] ) {
			$dropdownPrefix = $column['ui-prefix'];
		}
		if ( strpos( $dropdownPrefix, '::' ) == false ) {
			$v = $class;
			if ( is_object( $class ) ) {
				$v = get_class( $class );
			}
			$dropdownPrefix = $v . 'Constants::' . $dropdownPrefix;
		}

		$dropdownPrefix    = explode( '::', $dropdownPrefix );
		$dropdownPrefix[0] = str_replace( 'Search', '', $dropdownPrefix[0] );
		if ( ! class_exists( $dropdownPrefix[0] ) ) {
			$dropdownPrefix[0] = ICP_PLUGIN_PREFIX . $dropdownPrefix[0];
		}
		if ( ! class_exists( $dropdownPrefix[0] ) ) {
			$result = array();
			return $result;
		}

		$reflection = new ReflectionClass( $dropdownPrefix[0] );
		$constants  = $reflection->getConstants();
		foreach ( $constants as $k => $v ) {
			if ( $icp->Utils->startsWith( $k, $dropdownPrefix[1] ) ) {
				$id           = $v;
				$k            = 'Dropdown.' . $dropdownPrefix[0] . '.' . $k;
				$v            = $icp->Lang->L( $k );
				$values[ $v ] = $id;
			}
		}

		$inverseKeys = true;
		$result      = array();
		if ( is_array( $values ) && count( $values ) > 0 ) {
			ksort( $values );
			$i = 0;
			foreach ( $values as $k => $v ) {
				$colors = $icp->Utils->get( $column, 'ui-style', '', $i );
				if ( strpos( $colors, ':' ) === false ) {
					$colors .= ':';
				}
				$colors = explode( ':', $colors );
				$style  = '';
				if ( '' != $colors[0] ) {
					$style .= 'color:' . $colors[0] . '; ';
				}
				if ( '' != $colors[1] ) {
					$style .= 'font-weight:' . $colors[1] . '; ';
				}
				if ( $inverseKeys ) {
					$result[] = array(
						'id'    => $v,
						'text'  => $k,
						'style' => $style,
					);
				} else {
					$result[] = array(
						'id'    => $k,
						'text'  => $v,
						'style' => $style,
					);
				}
				++$i;
			}
		}
		return $result;
	}

	private function getOpenTag( $instance, $name, $options ) {
		global $icp;
		if ( false === $options || '' === $options ) {
			return '';
		}
		if ( is_string( $options ) ) {
			$options = array( 'tag' => $options );
		}
		$defaults = array(
			'tag'   => '',
			'style' => '',
		);
		$options  = $icp->Utils->parseArgs( $options, $defaults );
		if ( '' == $options['tag'] ) {
			return '';
		}

		if ( is_object( $instance ) ) {
			$instance = get_class( $instance );
		}
		foreach ( $instance as &$value ) {
			$value = print_r( $value, true );
		}
		$instance = str_replace( ICP_PLUGIN_PREFIX, '', $instance );
		$instance = str_replace( 'Search', '', $instance );
		$column   = $icp->Dao->Utils->getColumn( $instance, $name );
		if ( ! isset( $column['ui-align'] ) || '' == $column['ui-align'] ) {
			$column['ui-align'] = 'center';
		}
		$class  = ' class="text-' . $column['ui-align'] . '" ';
		$result = '<' . $options['tag'] . $class . ' style="' . $options['style'] . '">';
		return $result;
	}
	private function getCloseTag( $tag ) {
		if ( false === $tag || '' == $tag ) {
			return '';
		}
		$result = '</' . $tag . '>';
		return $result;
	}
	public function inputHeader( $instance, $name, $options ) {
		global $icp;
		$defaults = array(
			'tag'           => false,
			'echo'          => true,
			'style'         => '',
			'header'        => '',
			'rawColumnName' => false,
		);
		$options  = $icp->Utils->parseArgs( $options, $defaults );
		$args     = array();
		$name     = $icp->Ui->getFieldOptions( $instance, $name, $args );
		$buffer   = $this->getOpenTag( $instance, $name, $options );
		$column   = $icp->Dao->Utils->getColumn( $instance, $name );
		if ( '' != $options['header'] ) {
			$header = $icp->Lang->L( $options['header'] );
		} else {
			$header = '';
			if ( '' != $this->prefix ) {
				$header = $this->prefix . '.';
			}
			$header .= $name . '.Header';
			if ( $icp->Lang->H( $header ) || ! $options['rawColumnName'] ) {
				$header = $icp->Lang->L( $header );
			} else {
				$header = $name;
			}
		}
		$buffer .= $header;
		if ( isset( $column['ui-type'] ) ) {
			$suffix = '';
			switch ( strtolower( $column['ui-type'] ) ) {
				case 'percentage':
					$symbol = true;
					if ( isset( $column['ui-symbol'] ) ) {
						$symbol = $icp->Utils->isTrue( $column['ui-symbol'] );
					}
					if ( $symbol ) {
						$suffix = ' %';
					}
					break;
				case 'currency':
					$symbol = $icp->Utils->getDefaultCurrencySymbol();
					$symbol = $icp->Utils->getCurrencySymbol( $symbol );
					$suffix = ' ' . $symbol;
					break;
			}
			$buffer .= $suffix;
		}
		$buffer .= $this->getCloseTag( $options['tag'] );
		if ( $options['echo'] ) {
			echo wp_kses_post( $buffer );
		}
	}
	private function getUiStyle( $column, $i ) {
		global $icp;
		if ( is_bool( $i ) ) {
			$i = ( $i ? 1 : 0 );
		}
		$colors = $icp->Utils->get( $column, 'ui-style', '', $i );
		if ( strpos( $colors, ':' ) === false ) {
			$colors .= ':';
		}
		$colors = explode( ':', $colors );
		$style  = '';
		if ( '' != $colors[0] ) {
			$style .= 'color:' . $colors[0] . '; ';
		}
		if ( '' != $colors[1] ) {
			$style .= 'font-weight:' . $colors[1] . '; ';
		}
		return $style;
	}
	public function inputGet( $instance, $name, $tag = false, $echo = true ) {
		global $icp;
		$options = array();
		if ( is_array( $echo ) ) {
			$options = $echo;
			$echo    = true;
		}
		$name  = $icp->Ui->getFieldOptions( $instance, $name, $options );
		$value = '#' . $name . '#??';
		if ( is_array( $instance ) ) {
			if ( isset( $instance[ $name ] ) ) {
				$value = $instance[ $name ];
			} else {
				$value = '';
			}
			if ( isset( $options[ 'format_' . $name ] ) ) {
				switch ( strtolower( $options[ 'format_' . $name ] ) ) {
					case 'datetime':
						$value = $icp->Utils->formatDatetime( $value );
						break;
					case 'time':
						$value = $icp->Utils->formatTime( $value );
						break;
					case 'date':
						$value = $icp->Utils->formatDate( $value );
						break;
					case 'gravatar':
						$value = $icp->Utils->getGravatarImage( $value );
						break;
					case 'currency':
						$value = $icp->Utils->formatCurrencyMoney( $value );
						break;
					case 'percentage':
						$value = $icp->Utils->formatPercentage( $value );
						break;
				}
			}
		} else {
			$column = $icp->Dao->Utils->getColumn( $instance, $name );
			if ( isset( $column['alias'] ) ) {
				$name = $column['alias'];
			}

			if ( false === $column ) {
				$value = '#' . $name . '#??';
			} elseif ( ! isset( $column['ui-type'] ) ) {
				$value = 'ui-type #' . $name . '#??';
			} elseif ( isset( $options['check'] ) && $options['check'] ) {
				$ids                 = $icp->Utils->qs( 'ids', array() );
				$value               = $icp->Utils->get( $instance, $name, '' );
				$options['selected'] = $value;
				$options['noLayout'] = true;
				if ( ! $icp->Utils->inArray( $value, $ids ) ) {
					$value = false;
				}

				ob_start();
				unset( $options['readonly'] );
				unset( $options['disabled'] );
				$this->inputComponent( 'check', 'ids[]', $value, $options );
				$value = ob_get_clean();
			} else {
				$value  = $icp->Utils->get( $instance, $name, '' );
				$source = $value;

				$type = strtolower( $column['ui-type'] );
				switch ( $type ) {
					case 'currency':
						$value = round( floatval( $value ), 4 );
						if ( 0 != $value ) {
							$value = $icp->Utils->formatCurrencyMoney( $value );
						} else {
							$value = '';
						}
						break;
					case 'number':
						$value = intval( $value );
						break;
					case 'percentage':
						$symbol = true;
						if ( isset( $column['ui-symbol'] ) ) {
							$symbol = $icp->Utils->isTrue( $column['ui-symbol'] );
						}
						$value = $icp->Utils->formatPercentage( $value, $symbol );
						break;
					case 'checklist':
					case 'radiolist':
						$values = $this->options( $instance, $name );
						$value  = $this->optionsText( $values, $value );
						break;
					case 'dropdown':
					case 'tags':
					case 'select':
						$values            = $this->options( $instance, $name );
						$_GET['_inputGet'] = $value;
						if ( $this->getMasterAjaxDomain( $instance, $name, $value, $options ) ) {
							if ( isset( $options['values'] ) ) {
								$values = $options['values'];
								unset( $options['values'] );
							}
						}
						unset( $_GET['_inputGet'] );
						$value = $this->optionsText( $values, $value );
						break;
					case 'date':
						$value = $icp->Utils->formatDate( $value );
						break;
					case 'time':
						$value = $icp->Utils->formatTime( $value );
						break;
					case 'datetime':
						$value = $icp->Utils->formatDatetime( $value );
						break;
					case 'toggle':
						$value = $icp->Utils->isTrue( $value );
						$style = $this->getUiStyle( $column, $value );
						$value = $icp->Lang->L( $value ? 'Toggle.Yes' : 'Toggle.No' );
						if ( '' != $style ) {
							$value = '<span style="' . $style . '">' . $value . '</span>';
						}
						break;
				}
			}
		}

		$buffer = $this->getOpenTag( $instance, $name, $tag );
		if ( isset( $options['ui-link'] ) && $options['ui-link'] ) {
			$target = '_self';
			if ( isset( $options['ui-target'] ) && $options['ui-target'] ) {
				$target = $options['ui-target'];
			}
			if ( '' != $value ) {
				$buffer .= '<a href="' . $options['ui-link'] . $instance->id . '" target="' . $target . '">';
				$buffer .= $value;
				$buffer .= '</a>';
			}
		} else {
			$buffer .= $value;
		}
		$buffer .= $this->getCloseTag( $tag );
		if ( $echo ) {
			global $icp_allowed_html_tags;
			echo wp_kses( $buffer, $icp_allowed_html_tags );
		} else {
			return $buffer;
		}
	}
	public function inputSearch( $instance, $name ) {
		$prev         = $this->search;
		$this->search = true;
		$this->inputForm( $instance, $name );
		$this->search = $prev;
	}
	public function optionsText( $options, $value ) {
		global $icp;
		$value = $icp->Utils->toArray( $value );
		if ( false === $options || 0 == count( $options ) || 0 == count( $value ) ) {
			return '';
		}

		$buffer = '';
		foreach ( $options as $v ) {
			if ( isset( $v['id'] ) && in_array( $v['id'], $value ) ) {
				if ( '' != $buffer ) {
					$buffer .= ', ';
				}
				if ( ! isset( $v['text'] ) && isset( $v['name'] ) ) {
					$v['text'] = $v['name'];
				}

				if ( isset( $v['style'] ) && '' != $v['style'] ) {
					$buffer .= '<span style="' . $v['style'] . '">' . $v['text'] . '</span>';
				} else {
					$buffer .= $v['text'];
				}
			}
		}
		return $buffer;
	}

	public function submit( $name = '', $options = array() ) {
		global $icp;
		$defaults = array(
			'name'   => 'btnSubmit',
			'prompt' => false,
			'submit' => true,
		);
		$options  = $icp->Utils->parseArgs( $options, $defaults );
		if ( '' == $name ) {
			$name = 'Save';
		}
		$this->button( $name, $options );
	}
	public function buttonset( $name, $buttons, $options = array() ) {
		global $icp;
		if ( 0 == count( $buttons ) ) {
			return;
		}

		$defaults = array(
			'theme'       => '',
			'icon'        => '',
			'class'       => '',
			'rowClass'    => '',
			'buttonClass' => '',
			'clearBoth'   => false,
			'br'          => false,
			'noLayout'    => false,
		);
		$options  = $icp->Utils->parseArgs( $options, $defaults );

		$inputArgs = array(
			'md9'      => false,
			'label'    => $this->prefix . '.' . $name,
			//, 'style'=>'font-size:11px;'

			'col-md'   => 'col-md-3',
			'rowClass' => $options['rowClass'],
		);
		$class     = 'bs-component';
		if ( ! $options['noLayout'] ) {
			$class = 'col-md-9 bs-component';
			$this->openInput( $name, $inputArgs );
		}

		$args = array( 'class' => $class );
		$this->divStarts( $args );
		{
			$args = array( 'class' => 'btn-group' );
			$this->divStarts( $args );
			{
		foreach ( $buttons as $v ) {
			if ( is_string( $v ) ) {
				$v = array( 'value' => $v );
			} elseif ( ! is_array( $v ) ) {
				throw new Exception( 'buttonset: VALUE MUST BE STRING OR ARRAY' );
			}
			$name     = $v['value'];
			$defaults = array(
				'theme'       => $options['theme'],
				'icon'        => $options['icon'],
				'class'       => $options['buttonClass'],
				'data-filter' => '',
				'data-id'     => '',
				//, 'class'=>'light mr5'

				'script'      => false,
			);
			$v        = $icp->Utils->parseArgs( $v, $defaults );
			$this->button( $name, $v );
		}
			}
			$this->divEnds();
		if ( $options['br'] ) {
			$this->br();
		}
		if ( $options['clearBoth'] ) {
			$this->clearBoth();
		}
		}
		$this->divEnds();

		if ( ! $options['noLayout'] ) {
			$this->closeInput( $name, $inputArgs );
		}
	}
	public function button( $value, $options = null ) {
		global $icp;
		if ( ! $this->hiddenActionCreated ) {
			$this->hidden( '_action', '' );
		}

		$this->buttonPresent = true;
		$defaults            = array(
			'theme'      => 'primary',
			'icon'       => $this->getIcon( $value, 'cog' ),
			'id'         => 'btn' . $value,
			'name'       => 'btn' . $value,
			'uri'        => false,
			'type'       => 'button',
			'prompt'     => false,
			'rightSpace' => true,
			'leftSpace'  => false,
			'submit'     => false,
			'class'      => '',
			'style'      => '',
		);
		$options             = $icp->Utils->parseArgs( $options, $defaults );
		$uri                 = $options['uri'];
		$onclick             = ( false === $uri || '' === $uri ? '' : 'onclick="window.location=\'' . $uri . '\';"' );

		$icon      = $options['icon'];
		$leftIcon  = true;
		$nextWords = $icp->Utils->toArray( 'next|finish|save' );
		foreach ( $nextWords as $w ) {
			if ( stripos( $value, $w ) !== false ) {
				$leftIcon = false;
				break;
			}
		}
		//btn-block means 100% width
		if ( $options['leftSpace'] ) {
			echo '&nbsp;';
		}
		?>
		<button type="<?php echo esc_attr( $options['type'] ); ?>" id="<?php $this->getName( $options['id'] ); ?>" name="<?php $this->getName( $options['name'] ); ?>" class="btn <?php echo esc_attr( $options['class'] ); ?> btn-<?php echo esc_attr( $options['theme'] ); ?>" <?php esc_url_raw( $onclick ); ?> value="<?php echo esc_attr( $value ); ?>">
			<?php if ( $leftIcon ) { ?>
				<i class="fa fa-<?php echo esc_attr( $icon ); ?>"></i>&nbsp;
			<?php } ?>
			<?php $icp->Lang->P( $this->prefix . '.Button.' . $value ); ?>
			<?php if ( ! $leftIcon ) { ?>
				&nbsp;<i class="fa fa-<?php echo esc_attr( $icon ); ?>"></i>
			<?php } ?>
		</button>
		<?php
		if ( $options['rightSpace'] ) {
			echo '&nbsp;';
		}

		if ( false !== $options['prompt'] ) {
			$args = $options['prompt'];
			if ( ! is_array( $args ) ) {
				$args = array();
			}
			if ( ! isset( $args['submit'] ) ) {
				$args['submit'] = $options['submit'];
			}
			if ( ! isset( $args['btnConfirmTheme'] ) && isset( $options['theme'] ) ) {
				$args['btnConfirmTheme'] = $options['theme'];
			}
			$this->prompt( $options['id'], $args );
		}
		if ( false == $options['prompt'] ) {
			?>
			<script>
				jQuery(function() {
					<?php $this->jQueryBtnConfirm( $options['id'], $options['id'], $options ); ?>
				});
			</script>
			<?php
		}
	}

	public function openBlock() {
		$this->blockOpened = true;
		?>
		<div class="mw1000 left-block">
		<?php
	}
	public function closeBlock() {
		if ( ! $this->blockOpened ) {
			return;
		}
		?>
		</div>
		<?php
	}
	public function panel( $title, $callback = false, $options = array() ) {
		if ( is_callable( $title ) && false == $callback ) {
			$callback = $title;
			$title    = '';
		}
		$style = ( isset( $options['style'] ) ? $options['style'] : 'primary' );
		$this->openPanel( $title, $style );
		$callback();
		$this->closePanel();
	}
	public function openPanel( $options = array() ) {
		global $icp;
		if ( is_string( $options ) ) {
			$options = array( 'title' => $options );
		}
		$defaults = array(
			'title'      => '',
			'titleText'  => '',
			'subtitle'   => true,
			'style'      => '',
			'panelTop'   => false,
			'panelColor' => '',
			'icon'       => '',
			'class'      => '',
			'body'       => true,
			'buttons'    => false,
			'id'         => '',
		);
		$options  = $icp->Utils->parseArgs( $options, $defaults );
		if ( '' == $options['title'] ) {
			$options['title'] = 'Name';
		}
		$options['title'] = ucfirst( $options['title'] );

		$title = 'Panel.' . $this->prefix . '.' . $options['title'];
		$title = $icp->Lang->L( $title );
		if ( '' != $options['titleText'] ) {
			$title = $options['titleText'];
		}

		$style = $options['style'];
		$panel = '';
		if ( '' != $style ) {
			//list($style, $color)=$ec->Utils->pickColor();
			$panel = 'panel-' . $style;
		}
		if ( $options['panelTop'] ) {
			$panel .= ' panel-border top';
		}

		$subtitle = '';
		if ( is_bool( $options['subtitle'] ) && $options['subtitle'] ) {
			$subtitle = $icp->Lang->L( 'Panel.' . $this->prefix . '.' . $options['title'] . 'Subtitle' );
		} elseif ( is_string( $options['subtitle'] ) && '' != $options['subtitle'] ) {
			$subtitle = $icp->Lang->L( $options['subtitle'] );
		}
		?>
		<div class="panel <?php echo esc_attr( $panel ); ?> mt20 mb20 <?php echo esc_attr( $options['class'] ); ?>" id="<?php echo esc_attr( $options['id'] ); ?>">
			<?php if ( is_array( $options['buttons'] ) && count( $options['buttons'] ) > 0 ) { ?>
				<div class="panel-header-buttons text-left">
					<?php $icp->Form->buttons( $options['buttons'] ); ?>
				</div>
			<?php } else { ?>
				<div class="panel-heading">
					<?php if ( '' != $options['icon'] ) { ?>
						<span class="panel-icon">
							<i class="fa fa-<?php echo esc_attr( $options['icon'] ); ?>"></i>
						</span>
					<?php } ?>
					<span class="panel-title">
						<?php echo wp_kses_post( $title ); ?>
					</span>
				</div>
			<?php } ?>

			<?php if ( $options['body'] ) { ?>
				<div class="panel-body bg-light dark">
					<?php if ( '' != $subtitle ) { ?>
						<div class="panel-subtitle">
							<?php echo wp_kses_post( $subtitle ); ?>
						</div>
					<?php } ?>
					<div class="admin-form">
				<?php
			}
	}
	public function closePanel( $options = array() ) {
		global $icp;
		$defaults = array(
			'body'    => true,
			'buttons' => false,
		);
		$options  = $icp->Utils->parseArgs( $options, $defaults );

		if ( $options['body'] ) {
			?>
				</div>
			</div>
			<?php
			if ( is_array( $options['buttons'] ) && count( $options['buttons'] ) > 0 ) {
				?>
				<div class="panel-footer-buttons text-right">
					<?php $icp->Form->buttons( $options['buttons'] ); ?>
				</div>
			<?php } ?>
		</div>
			<?php
		}
	}

	//popup
	public function prompt( $buttonId, $options = array() ) {
		global $icp;
		$p                         = $this->prefix . '.Prompt.' . $buttonId . '.';
		$defaults                  = array(
			'btnAbort'        => $buttonId . 'Abort',
			'btnAbortTheme'   => '',
			'btnAbortText'    => $p . 'ButtonAbort',
			'btnConfirm'      => $buttonId . 'Confirm',
			'btnConfirmTheme' => 'primary',
			'btnConfirmText'  => $p . 'ButtonConfirm',
			'uri'             => '',
			'submit'          => true,
			'effect'          => 'newspaper',
		);
		$options                   = $icp->Utils->parseArgs( $options, $defaults );
		$options['btnAbortText']   = $icp->Lang->L( $options['btnAbortText'] );
		$options['btnConfirmText'] = $icp->Lang->L( $options['btnConfirmText'] );
		if ( ! isset( $options['btnAbortIcon'] ) ) {
			$options['btnAbortIcon'] = $this->getIcon( $options['btnAbortText'] );
		}
		if ( ! isset( $options['btnConfirmIcon'] ) ) {
			$options['btnConfirmIcon'] = $this->getIcon( $options['btnConfirmText'] );
		}
		$modalId = 'modal-prompt-' . $buttonId;
		?>
		<!-- Panel popup -->
		<div id="<?php echo esc_attr( $modalId ); ?>" class="popup-basic bg-none mfp-with-anim mfp-hide">
			<div class="panel panel-<?php echo esc_attr( $options['btnConfirmTheme'] ); ?>">
				<div class="panel-heading">
					<span class="panel-icon">
						<i class="fa fa-question-circle"></i>
					</span>
					<span class="panel-title"><?php $icp->Lang->P( $p . 'Title' ); ?></span>
				</div>
				<div class="panel-body">
					<p><?php $icp->Lang->P( $p . 'Text' ); ?></p>
				</div>
				<div class="panel-footer text-right">
					<button id="<?php echo esc_attr( $options['btnAbort'] ); ?>" class="btn btn-<?php echo esc_attr( $options['btnAbortTheme'] ); ?>" type="button">
						<?php if ( false !== $options['btnAbortIcon'] ) { ?>
							<i class="fa fa-<?php echo esc_attr( $options['btnAbortIcon'] ); ?>"></i>
							&nbsp;
						<?php } ?>
						<?php echo esc_attr( $options['btnAbortText'] ); ?>
					</button>
					<button id="<?php echo esc_attr( $options['btnConfirm'] ); ?>" class="btn btn-<?php echo esc_attr( $options['btnConfirmTheme'] ); ?>" type="button">
						<?php if ( false !== $options['btnConfirmIcon'] ) { ?>
							<i class="fa fa-<?php echo esc_attr( $options['btnConfirmIcon'] ); ?>"></i>
							&nbsp;
						<?php } ?>
						<?php echo esc_attr( $options['btnConfirmText'] ); ?>
					</button>
				</div>
			</div>
		</div>
		<script>
			jQuery(function() {
				jQuery('#<?php echo esc_attr( $buttonId ); ?>').on('click', function() {
					jQuery.magnificPopup.open({
						removalDelay: 0
						, items: {
							src: '#<?php echo esc_attr( $modalId ); ?>'
						}
						, midClick: true
					});
				});
				<?php $this->jQueryBtnConfirm( $buttonId, $options['btnConfirm'], $options ); ?>
				jQuery('#<?php echo esc_attr( $options['btnAbort'] ); ?>').on('click', function(e) {
					e.preventDefault();
					jQuery.magnificPopup.close();
				});
			});
		</script>
		<?php
	}
	private function jQueryBtnConfirm( $btnButtonId, $btnConfirmId, $options ) {
		?>
		jQuery('#<?php echo esc_attr( $btnConfirmId ); ?>').on('click', function(e) {
			e.preventDefault();
		<?php if ( false !== $options['uri'] && '' != $options['uri'] ) { ?>
			location.href="<?php echo esc_url_raw( $options['uri'] ); ?>";
		<?php } elseif ( $options['submit'] ) { ?>
			var $btn=jQuery('#<?php echo esc_attr( $btnButtonId ); ?>');
			var $form=$btn.closest('form');
			var $action=jQuery('[name=_action]');
			if($action.length>0) {
				$action.val($btn.val());
			}

			if($form.length>0) {
				jQuery('input, select').prop('disabled', false);
				$form[0].submit();
			}
		<?php } ?>
		});
		<?php
	}
	private function getColumnDetails( $options, $name ) {
		global $icp;
		$defaults = array(
			'style'    => '',
			'header'   => '',
			'function' => false,
			'align'    => 'center',
			'class'    => '',
		);
		$result   = ( isset( $options[ $name ] ) ? $options[ $name ] : array() );
		$result   = $icp->Utils->parseArgs( $result, $defaults );
		return $result;
	}
	public function inputTable( $fields, $items, $options = array() ) {
		global $icp;
		$defaults = array(
			'class'         => '',
			'style'         => '',
			'data-filter'   => '',
			'rowOptions'    => array(),
			'bgClass'       => false,
			'rawColumnName' => false,
		);
		$options  = $icp->Utils->parseArgs( $options, $defaults );
		$fields   = $icp->Utils->toArray( $fields );
		?>
		<div class="table-responsive">
			<table class="table bg-white tc-checkbox-1 <?php echo esc_attr( $options['class'] ); ?>" style="<?php echo esc_attr( $options['style'] ); ?>" data-filter="<?php echo esc_attr( $options['data-filter'] ); ?>">
			<thead>
					<tr class="bg-light">
					<?php
					foreach ( $fields as $name ) {
						$details = $this->getColumnDetails( $options, $name );
						$args    = array(
							'tag'           => 'th',
							'rawColumnName' => $options['rawColumnName'],
							'style'         => $details['style'],
							'header'        => $details['header'],
						);
						$this->inputHeader( $items, $name, $args );
					}
					?>
				</tr>
			</thead>
			<tbody>
				<?php
				$i = 0;
				foreach ( $items as $instance ) {
					$i++;
					$bgClass = '';
					if ( false !== $options['bgClass'] ) {
						$bgClass = $options['bgClass']( $instance );
					} else {
						$bgClass = ( 0 == $i % 2 ? 'even' : 'odd' );
					}
					?>
					<tr class="<?php echo esc_attr( $bgClass ); ?>">
						<?php
						foreach ( $fields as $name ) {
							$details    = $this->getColumnDetails( $options, $name );
							$rowOptions = $options['rowOptions'];
							if ( ! is_array( $rowOptions ) ) {
								$rowOptions = array();
							}
							$args       = array();
							$columnName = $icp->Ui->getFieldOptions( $instance, $name, $args );
							$column     = $icp->Dao->Utils->getColumn( $instance, $columnName );
							$align      = $icp->Utils->get( $column, 'ui-align', '' );
							if ( '' == $align ) {
								$alignKey = 'align_' . $name;
								$align    = ( isset( $rowOptions[ $alignKey ] ) ? $rowOptions[ $alignKey ] : '' );
							}
							if ( '' == $align ) {
								$align = $details['align'];
							}

							$columnKey = 'column_' . $columnName;
							$alignKey  = 'class_' . $name;
							$class     = ( isset( $rowOptions[ $alignKey ] ) ? $rowOptions[ $alignKey ] : '' );
							if ( '' == $class ) {
								$class = $details['class'];
							}

							echo '<td class="' . esc_attr( $class ) . ' text-' . esc_attr( $align ) . '" style="' . esc_attr( $details['style'] ) . '">';
							if ( isset( $options[ $columnKey ] ) ) {
								$icp->Utils->functionCall( $options[ $columnKey ], $instance );
							} elseif ( false !== $details['function'] ) {
								$icp->Utils->functionCall( $details['function'], $instance );
							} else {
								$this->inputGet( $instance, $name, '', $rowOptions );
							}
							echo '</td>';
						}
						?>
					</tr>
				<?php } ?>
			</tbody>
		</table>
		</div>
		<?php
	}
	public function submits( $buttons ) {
		if ( false === $buttons || 0 == count( $buttons ) ) {
			return;
		}
		foreach ( $buttons as $k => $v ) {
			$v['submit'] = true;
			$this->button( $k, $v );
		}
	}
	public function buttons( $buttons ) {
		if ( false === $buttons || 0 == count( $buttons ) ) {
			return;
		}
		foreach ( $buttons as $k => $v ) {
			$this->button( $k, $v );
		}
	}
}
