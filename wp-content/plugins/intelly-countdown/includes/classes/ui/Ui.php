<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ICP_Ui {
	var $Countdown;

	public function __construct() {
		$this->Countdown = new ICP_CountdownUi();
	}
	function getFieldOptions( $class, $name, &$options ) {
		global $icp;
		$readonly = false;
		$required = false;

		$i     = 0;
		$chars = str_split( $name );
		foreach ( $chars as $c ) {
			$exit = true;
			switch ( $c ) {
				case '@':
					$options['check'] = true;
					$exit             = false;
					break;
				case '!':
					$options['hidden'] = true;
					$exit              = false;
					break;
				case '*':
					$required = true;
					$exit     = false;
					break;
				case '^':
					$readonly = true;
					$exit     = false;
					break;
				case '#':
					$options['ui-link'] = ICP_TAB_EDITOR_URI . '&id=';
					$exit               = false;
					break;
				case '_':
					$options['ui-target'] = '_blank';
					$exit                 = false;
					break;
				case '?':
					$options['row-hidden'] = true;
					$exit                  = false;
					break;
			}

			if ( $exit ) {
				break;
			}
			++$i;
		}
		if ( $i > 0 ) {
			$name = substr( $name, $i );
		}

		if ( $icp->Form->readonly ) {
			$readonly = true;
		}

		$column = array();
		if ( is_object( $class ) ) {
			$column = $icp->Dao->Utils->getColumn( $class, $name );
		}
		if ( ! $readonly ) {
			$readonly = $icp->Utils->get( $column, 'ui-readonly', false );
			$readonly = $icp->Utils->isTrue( $readonly );
		}
		if ( ! $required ) {
			$required = $icp->Utils->get( $column, 'ui-required', false );
			$required = $icp->Utils->isTrue( $required );
		}
		$visible = $icp->Utils->get( $column, 'ui-visible', '' );
		if ( '' != $visible ) {
			$options['ui-visible'] = $visible;
		}

		if ( $readonly ) {
			$options['readonly'] = 'readonly';
		}
		if ( $required ) {
			$options['ui-required'] = 'required';
		}
		//$column=$ec->Dao->Utils->getColumn($class, $name);
		if ( isset( $column['alias'] ) ) {
			$name = $column['alias'];
		}
		return $name;
	}

	function validateDomain( $instance, $fields, $all = false ) {
		global $icp;
		$fields = $icp->Utils->toArray( $fields );
		if ( is_null( $fields ) || false === $fields ) {
			return true;
		}

		$result = true;
		foreach ( $fields as $f ) {
			if ( trim( $f ) == '' ) {
				continue;
			}

			$options = array();
			$k       = $icp->Ui->getFieldOptions( $instance, $f, $options );
			if ( isset( $options['readonly'] ) && $options['readonly'] ) {
				continue;
			}

			$p1 = '';
			$p2 = '';
			$p3 = '';

			$v      = $icp->Utils->get( $instance, $k );
			$column = $icp->Dao->Utils->getColumn( $instance, $k );
			if ( ! $icp->Dao->Utils->isColumnVisible( $instance, $k ) ) {
				continue;
			}

			if ( ! isset( $options['lb-required'] ) && ! $all ) {
				//in ogni caso i campi vengono validati il che significa che p.e. i campi
				//che non trasferiscono il valore vengono cmq modificati (altrimenti succede
				//p.e. che si ha una combo che non viene selezionata e quindi non verrebbe
				//più aggiornata mantenendo sempre il veccho valore
				if ( is_null( $v ) ) {
					if ( $icp->Dao->Utils->isColumnNumeric( $instance, $k ) ) {
						if ( 'toggle' == $column['ui-type'] || 'tick' == $column['ui-type'] ) {
							$v = 0;
						}
					} elseif ( $icp->Dao->Utils->isColumnDate( $instance, $k ) ) {
						$v = 0;
					} elseif ( $icp->Dao->Utils->isColumnArray( $instance, $k ) ) {
						$v = array();
					}
				}
				$icp->Utils->set( $instance, $k, $v );
			} elseif ( isset( $options['lb-required'] ) || $all ) {
				$message = 'Error.Store[' . get_class( $instance ) . '].' . $k;
				$message = str_replace( ICP_PLUGIN_PREFIX, '', $message );
				$e       = false;
				if ( 0 !== $v && is_null( $v ) ) {
					$e = true;
				} else {
					if ( $icp->Dao->Utils->isColumnDate( $instance, $k ) ) {
						if ( 0 == $v ) {
							$e = true;
						}
					} elseif ( $icp->Dao->Utils->isColumnNumeric( $instance, $k ) ) {
						if ( is_null( $v ) && 'toggle' == $column['ui-type'] ) {
							$v = 0;
						}

						if ( '' === $v || false === $v ) {
							//if is a foreign key must be >0
							$e = true;
						} else {
							$min = $icp->Utils->get( $column, 'ui-min', false );
							$max = $icp->Utils->get( $column, 'ui-max', false );

							if ( ! $e && '' != $min ) {
								$min = doubleval( $min );
								if ( $v < $min ) {
									$message .= '.Min';
									$e        = true;
								}
							}
							if ( ! $e && '' != $max ) {
								$max = doubleval( $max );
								if ( $v > $max ) {
									$message .= '.Max';
									$e        = true;
								}
							}
						}
					} elseif ( $icp->Dao->Utils->isColumnArray( $instance, $k ) ) {
						//nocheck
						if ( is_array( $v ) && 0 == count( $v ) ) {
							$e = true;
						}
					} else {
						if ( is_array( $v ) && 0 == count( $v ) ) {
							$e = true;
						} elseif ( trim( $v ) === '' ) {
							$e = true;
						} else {
							$len = strlen( trim( $v ) );
							if ( isset( $column['ui-len'] ) && intval( $column['ui-len'] ) > 0 ) {
								$compare = intval( $column['ui-len'] );
								if ( $len != $compare ) {
									$message .= '.Len';
									$p1       = $compare;
									$p2       = $len;
									$e        = true;
								}
							}

							if ( ! $e ) {
								$min = $icp->Utils->iget( $column, 'ui-min', -1 );
								$max = $icp->Utils->iget( $column, 'ui-max', -1 );

								if ( $min > -1 && $len < $min ) {
									$message .= '.Min';
									$p1       = $min;
									$p2       = $len;
									$e        = true;
								} elseif ( $max > -1 && $len > $max ) {
									$message .= '.Max';
									$p1       = $max;
									$p2       = $len;
									$e        = true;
								}
							}
						}
					}
				}

				if ( $e ) {
					$icp->Options->pushErrorMessage( $message, $p1, $p2, $p3 );
					$result = false;
				}
			}
		}
		return $result;
	}

	public function getText( $text, $args, $options = array() ) {
		global $icp;

		$defaults = array( 'striptags' => false );
		$options  = $icp->Utils->parseArgs( $options, $defaults );
		if ( false !== $args && count( $args ) > 0 ) {
			$patterns = array();
			$starts   = strpos( $text, '{' );
			while ( false !== $starts ) {
				$ends = strpos( $text, '}', $starts + 1 );
				if ( false !== $ends ) {
					$patterns[] = $icp->Utils->substr( $text, $starts + 1, $ends );
				}
				$starts = strpos( $text, '{', $ends + 1 );
			}
			foreach ( $patterns as $k ) {
				$v = '#' . $k . '??#';
				if ( strpos( $k, '.' ) !== false ) {
					$k        = explode( '.', $k );
					$instance = false;
					if ( isset( $args[ $k[0] ] ) ) {
						$instance = $args[ $k[0] ];
					} elseif ( isset( $args[ $k[0] . '.' ] ) ) {
						$instance = $args[ $k[0] . '.' ];
					}
					if ( is_object( $instance ) ) {
						$property = $k[1];
						$v        = $this->FF->inputGet( $instance, $property, false, false );
					}
					$k = implode( '.', $k );
				} elseif ( isset( $args[ $k ] ) ) {
					$v = $args[ $k ];
				}
				$text = str_replace( '{' . $k . '}', $v, $text );
			}
		}
		if ( $options['striptags'] ) {
			$text = strip_tags( $text );
		}
		return $text;
	}

	//some alerts
	public function alertSuccessError( $success, $message, $v1 = null, $v2 = null, $v3 = null, $v4 = null, $v5 = null ) {
		global $icp;
		if ( $icp->Utils->isTrue( $success ) ) {
			$this->alertSuccess( $message, $v1, $v2, $v3, $v4, $v5 );
		} else {
			$this->alertError( $message, $v1, $v2, $v3, $v4, $v5 );
		}
	}
	public function alertSuccess( $message, $v1 = null, $v2 = null, $v3 = null, $v4 = null, $v5 = null ) {
		$this->alert( 'success', $message, $v1, $v2, $v3, $v4, $v5 );
	}
	public function alertInfo( $message, $v1 = null, $v2 = null, $v3 = null, $v4 = null, $v5 = null ) {
		$this->alert( 'info', $message, $v1, $v2, $v3, $v4, $v5 );
	}
	public function alertWarning( $message, $v1 = null, $v2 = null, $v3 = null, $v4 = null, $v5 = null ) {
		$this->alert( 'warning', $message, $v1, $v2, $v3, $v4, $v5 );
	}
	public function alertError( $message, $v1 = null, $v2 = null, $v3 = null, $v4 = null, $v5 = null ) {
		$this->alert( 'error', $message, $v1, $v2, $v3, $v4, $v5 );
	}
	public function alert( $type, $message, $v1 = null, $v2 = null, $v3 = null, $v4 = null, $v5 = null ) {
		global $icp;
		$color = '';
		$icon  = '';
		if ( $icp->Lang->H( $message ) ) {
			$message = $icp->Lang->L( $message, $v1, $v2, $v3, $v4, $v5 );
		}
		switch ( strtolower( $type ) ) {
			case 'success':
				$color = 'success';
				$icon  = 'check';
				break;
			case 'info':
				$color = 'primary';
				$icon  = 'info';
				break;
			case 'warning';
				$color = 'warning';
				$icon  = 'warning';
				break;
			case 'error':
				$color = 'danger';
				$icon  = 'remove';
				break;
		}
		?>
		<div class="bs-component mw1000 left-block mb10">
			<div class="alert alert-<?php echo esc_attr( $color ); ?> alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<i class="fa fa-<?php echo esc_attr( $icon ); ?> pr10"></i>
				<?php echo wp_kses_post( $message ); ?>
				<div style="clear:both"></div>
			</div>
		</div>
		<?php
	}
	public function redirectEdit( $id = false ) {
		global $icp;
		$uri = ICP_TAB_EDITOR_URI;
		if ( false !== $id ) {
			$uri .= '&id=' . $id;
		}
		$icp->Utils->redirect( $uri );
	}
	public function redirectManager( $id = false ) {
		global $icp;
		$uri = ICP_TAB_MANAGER_URI;
		if ( false !== $id ) {
			$uri .= '&id=' . $id;
		}
		$icp->Utils->redirect( $uri );
	}
}
