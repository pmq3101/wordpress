<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ICP_Utils {
	const FORMAT_DATETIME         = 'd/m/Y H:i';
	const FORMAT_COMPACT_DATETIME = 'd/m H:i';
	const FORMAT_DATE             = 'd/m/Y';
	const FORMAT_TIME             = 'H:i';

	const FORMAT_SQL_DATETIME = 'Y-m-d H:i:s';
	const FORMAT_SQL_DATE     = 'Y-m-d';
	const FORMAT_SQL_TIME     = 'H:i:s';

	private $colorIndex;
	private $defaultCurrencySymbol;

	public function __construct() {
		$this->colorIndex = 0;
	}

	public function setDefaultCurrencySymbol( $value ) {
		$this->defaultCurrencySymbol = $value;
	}
	public function getDefaultCurrencySymbol() {
		return ( '' == $this->defaultCurrencySymbol ? 'USD' : $this->defaultCurrencySymbol );
	}
	function format( $message, $v1 = null, $v2 = null, $v3 = null, $v4 = null, $v5 = null ) {
		if ( $v1 || $v2 || $v3 || $v4 || $v5 ) {
			$message = sprintf( $message, $v1, $v2, $v3, $v4, $v5 );
		}
		return $message;
	}
	function startsWith( $where, $search ) {
		$length = strlen( $search );
		return ( substr( $where, 0, $length ) === $search );
	}
	function endsWith( $where, $search ) {
		$length = strlen( $search );
		$start  = $length * -1; //negative
		return ( substr( $where, $start ) === $search );
	}
	function substr( $text, $start = 0, $end = -1 ) {
		if ( $end < 0 ) {
			$end = strlen( $text );
		}
		$length = $end - $start;
		return substr( $text, $start, $length );
	}

	function shortcodeArgs( $args, $defaults ) {
		$args     = $this->sanitizeShortcodeKeys( $args );
		$defaults = $this->sanitizeShortcodeKeys( $defaults );
		$args     = shortcode_atts( $defaults, $args );
		return $args;
	}
	function sanitizeShortcodeKeys( $array ) {
		$result = array();
		foreach ( $array as $k => $v ) {
			if ( is_string( $k ) ) {
				$k = strtolower( $k );
			}
			$result[ $k ] = $v;
		}
		return $result;
	}

	//WOW! $end is passed as reference due to we can change it if we found \n character after
	//substring to avoid having these characters after or before
	function substrln( $text, $start = 0, &$end = -1 ) {
		if ( $end < 0 ) {
			$end = strlen( $text );
		}

		do {
			$loop = false;
			$c    = substr( $text, $end, 1 );
			if ( "\n" == $c || "\r" == $c || '.' == $c ) {
				$end += 1;
				$loop = true;
			}
		} while ( $loop );

		$length = $end - $start;
		return substr( $text, $start, $length );
	}

	function toCommaArray( $array, $isNumeric = true, $isTrim = true ) {
		if ( is_string( $array ) ) {
			if ( trim( $array ) == '' ) {
				$array = array();
			} else {
				$array = explode( ',', $array );
			}
		} elseif ( is_numeric( $array ) ) {
			$array = array( $array );
		}
		if ( ! is_array( $array ) ) {
			$array = array();
		}
		for ( $i = 0; $i < count( $array ); $i++ ) {
			if ( $isTrim ) {
				$array[ $i ] = trim( $array[ $i ] );
			}
			if ( $isNumeric ) {
				$array[ $i ] = floatval( $array[ $i ] );
			}
		}
		return $array;
	}
	//verifica se il parametro needle è un elemento dell'array haystack
	//se il parametro needle è a sua volta un array verifica che almeno un elemento
	//sia contenuto all'interno dell'array haystack
	function inArray( $needle, $haystack ) {
		$result   = false;
		$haystack = $this->toArray( $haystack );
		$needle   = $this->toArray( $needle );
		if ( 0 == count( $haystack ) || 0 == count( $needle ) ) {
			return false;
		}

		foreach ( $haystack as $v ) {
			$v .= '';
			foreach ( $needle as $c ) {
				$c .= '';
				if ( $v === $c ) {
					$result = true;
					break;
				}
			}
			/*$v=intval($v);
			if ($v<0) {
				//if one element of the array have -1 value means i select "all" option
				$result=TRUE;
				break;
			}*/

			if ( $result ) {
				break;
			}
		}
		return $result;
	}

	function is( $name, $compare, $default = '', $ignoreCase = true ) {
		$what   = $this->qs( $name, $default );
		$result = false;
		if ( is_string( $compare ) ) {
			$compare = explode( ',', $compare );
		}
		if ( $ignoreCase ) {
			$what = strtolower( $what );
		}

		foreach ( $compare as $v ) {
			if ( $ignoreCase ) {
				$v = strtolower( $v );
			}
			if ( $what == $v ) {
				$result = true;
				break;
			}
		}
		return $result;
	}

	public function twitter( $name ) {
		?>
		<a href="https://twitter.com/<?php echo esc_attr( $name ); ?>" class="twitter-follow-button" data-show-count="false" data-dnt="true">Follow @<?php echo esc_attr( $name ); ?></a>
		<?php
	}

	public function sort( $isAssociative, $a1, $a2 = null, $a3 = null, $a4 = null, $a5 = null ) {
		$array = $this->merge( $isAssociative, $a1, $a2, $a3, $a4, $a5 );
		ksort( $array );
		return $array;
	}
	public function merge( $isAssociative, $a1, $a2 = null, $a3 = null, $a4 = null, $a5 = null ) {
		$result = array();
		if ( $isAssociative ) {
			$array = array( $a1, $a2, $a3, $a4, $a5 );
			foreach ( $array as $a ) {
				if ( ! is_array( $a ) ) {
					continue;
				}

				foreach ( $a as $k => $v ) {
					if ( ! isset( $result[ $k ] ) ) {
						$result[ $k ] = $v;
					}
				}
			}
		} else {
			$result = array_merge( $a1, $a2, $a3, $a4, $a5 );
		}
		return $result;
	}
	function bget( $instance, $name, $index = -1 ) {
		$v = $this->get( $instance, $name, false, $index );
		$v = $this->isTrue( $v );
		return $v;
	}
	function dget( $instance, $name, $index = -1 ) {
		$v = $this->get( $instance, $name, false, $index );
		$v = $this->parseDateToTime( $v );
		return $v;
	}
	function aget( $instance, $name, $index = -1 ) {
		$v = $this->get( $instance, $name, false, $index );
		$v = $this->toArray( $v );
		return $v;
	}
	function get( $instance, $name, $default = '', $index = -1 ) {
		if ( $this->isEmpty( $instance ) ) {
			return $default;
		}
		$options = array();
		//assolutamente da non fare altrimenti succede un disastro in quanto i metodi del inputComponent
		//gli passano come name il valore...insomma un disastro!
		//$name=$this->toArray($name);
		//$name=implode('.', $name);

		$result = $default;
		if ( is_array( $instance ) || is_object( $instance ) ) {
			if ( $this->propertyReflect( $instance, $name, $options ) ) {
				$result = $options['get'];
			}
		}
		if ( $index > -1 ) {
			$result = $this->toArray( $result );
			if ( isset( $result[ $index ] ) ) {
				$result = $result[ $index ];
			} else {
				$result = $default;
			}
		}
		return $result;
	}
	function has( $instance, $name ) {
		return $this->propertyReflect( $instance, $name );
	}
	function set( &$instance, $name, $value ) {
		global $icp;
		$options = array( 'set' => $value );
		$result  = $this->propertyReflect( $instance, $name, $options );
		if ( ! $result ) {
			//$ec->Log->error('UNABLE TO SET PROPERTY [%s] OF [%s]', $name, get_class($instance));
		}
		return $result;
	}
	function iget( $array, $name, $default = '' ) {
		return floatval( $this->get( $array, $name, $default ) );
	}

	private function propertyReflect( &$instance, $name, &$options = array() ) {
		if ( ! is_object( $instance ) && ! is_array( $instance ) ) {
			return false;
		}

		if ( false === $options || ! is_array( $options ) ) {
			$options = array();
		}
		$options['has'] = false;
		$options['get'] = false;

		$current = $instance;
		$names   = explode( '.', $name );
		$value   = false;
		$result  = true;
		for ( $i = 0; $i < count( $names ); $i++ ) {
			$name = $names[ $i ];
			if ( ! is_object( $current ) && ! is_array( $current ) ) {
				return false;
			}
			if ( is_null( $current ) ) {
				return false;
			}

			if ( is_object( $current ) ) {
				if ( get_class( $current ) == 'stdClass' ) {
					if ( isset( $current->$name ) ) {
						$value = $current->$name;
					} else {
						$result = false;
					}
				} else {
					$r = new ReflectionClass( $current );
					try {
						if ( $r->getProperty( $name ) !== false ) {
							$value = $current->$name;
						} else {
							$result = false;
						}
					} catch ( Exception $ex ) {
						if ( isset( $current->$name ) ) {
							$value = $current->$name;
						} else {
							$result = false;
						}
					}
				}
			} elseif ( is_array( $current ) ) {
				if ( isset( $current[ $name ] ) ) {
					$value = $current[ $name ];
				} else {
					$result = false;
				}
			}

			if ( ! $result ) {
				break;
			} elseif ( $i < ( count( $names ) - 1 ) ) {
				$current = $value;
			} else {
				$options['get'] = $value;
				if ( isset( $options['set'] ) ) {
					if ( is_object( $current ) ) {
						$current->$name = $options['set'];
					} elseif ( is_array( $current ) ) {
						$current[ $name ] = $options['set'];
					}
				}
			}
		}
		return $result;
	}

	function isTrue( $value ) {
		$result = false;
		if ( is_bool( $value ) ) {
			$result = (bool) $value;
		} elseif ( is_numeric( $value ) ) {
			$result = floatval( $value ) > 0;
		} else {
			$result = strtolower( $value );
			if ( 'ok' == $result || 'yes' == $result || 'true' == $result || 'on' == $result ) {
				$result = true;
			} else {
				$result = false;
			}
		}
		return $result;
	}

	function aqs( $prefix, $removePrefix = true ) {
		$result = array();
		$array  = $this->sanitize_post_or_get( $this->merge( true, $_POST, $_GET ) );
		foreach ( $array as $k => $v ) {
			if ( $this->startsWith( $k, $prefix ) ) {
				if ( $removePrefix ) {
					$k = substr( $k, strlen( $prefix ) );
				}
				$result[ $k ] = $v;
			}
		}
		return $result;
	}
	function iqs( $name, $default = 0, $min = 0, $max = 0 ) {
		$result = floatval( $this->qs( $name, $default ) );
		if ( $min != $max ) {
			if ( $result < $min ) {
				$result = $min;
			} elseif ( $result > $max ) {
				$result = $max;
			}
		}
		return $result;
	}
	function dqs( $name, $default = 0 ) {
		$result = ( $this->qs( $name, $default ) );
		$result = $this->parseDateToTime( $result );
		if ( 0 == $result ) {
			$result = $default;
		}
		return $result;
	}

	function qs( $name, $default = '' ) {
		$result = $default;
		if ( isset( $_POST[ $name ] ) ) {
			$result = $this->sanitize_post_or_get( $_POST[ $name ] );
		} elseif ( isset( $_GET[ $name ] ) ) {
			$result = $this->sanitize_post_or_get( $_GET[ $name ] );
		}

		if ( is_string( $result ) ) {
			$result = trim( $result );
		}
		return $result;
	}

	public function sanitize_post_or_get( $array ) {
		// ignore objects
		if ( is_array( $array ) ) {
			foreach ( $array as $k => &$v ) {
				if ( is_array( $v ) ) {
					$this->sanitize_post_or_get( $v );
				} elseif ( is_string( $v ) ) {
					$v = sanitize_text_field( wp_unslash( $v ) );
				}
			}
		} elseif ( is_string( $array ) ) {
			$array = sanitize_text_field( wp_unslash( $array ) );
		}
		return $array;
	}

	function query( $query, $args = null ) {
		global $icp;

		$defaults = array(
			'post_type' => '',
			'all'       => false,
			'select'    => false,
		);
		$args     = wp_parse_args( $args, $defaults );

		$key    = array( 'Query', $query . '_' . $args['post_type'] );
		$result = $icp->Options->getCache( $key );
		if ( ! is_array( $result ) || 0 == count( $result ) ) {
			$q        = null;
			$id       = 'ID';
			$name     = 'post_title';
			$function = '';
			switch ( $query ) {
				case ICP_QUERY_POSTS_OF_TYPE:
					$options  = array(
						'posts_per_page' => -1,
						'post_type'      => $args['post_type'],
					);
					$q        = get_posts( $options );
					$function = 'get_permalink';
					break;
				case ICP_QUERY_CATEGORIES:
					$options  = array( 'posts_per_page' => -1 );
					$q        = get_categories( $options );
					$id       = 'cat_ID';
					$name     = 'cat_name';
					$function = 'get_category_link';
					break;
				case ICP_QUERY_TAGS:
					$q        = get_tags();
					$id       = 'term_id';
					$name     = 'name';
					$function = 'get_tag_link';
					break;
			}

			$result = array();
			if ( $q ) {
				foreach ( $q as $v ) {
					$result[] = array(
						'id'   => $v->$id,
						'text' => $v->$name,
					);
				}
			} elseif ( ICP_QUERY_POST_TYPES == $query ) {
				//$options=array('public'=>TRUE, '_builtin'=>FALSE);
				//$q=get_post_types($options, 'names');
				$q = array();
				$q = array_merge( $q, array( 'post', 'page' ) );
				sort( $q );
				foreach ( $q as $v ) {
					$result[] = array(
						'id'   => $v,
						'name' => $v,
					);
				}
			}

			if ( '' != $function && function_exists( $function ) ) {
				for ( $i = 0; $i < count( $result ); $i++ ) {
					$v            = $result[ $i ];
					$v['url']     = call_user_func_array( $function, array( $v['id'] ) );
					$result[ $i ] = $v;
				}
			}
			$icp->Options->setCache( $key, $result );
		}

		if ( $args['all'] ) {
			$first   = array();
			$first[] = array(
				'id'   => -1,
				'name' => '[' . $icp->Lang->L( 'All' ) . ']',
				'url'  => '',
			);
			$result  = array_merge( $first, $result );
		}
		if ( $args['select'] ) {
			$first   = array();
			$first[] = array(
				'id'   => 0,
				'name' => '[' . $icp->Lang->L( 'Select' ) . ']',
				'url'  => '',
			);
			$result  = array_merge( $first, $result );
		}

		return $result;
	}

	//wp_parse_args with null correction
	function parseArgs( $options, $defaults ) {
		if ( is_null( $options ) ) {
			$options = array();
		} elseif ( is_object( $options ) ) {
			$options = (array) $options;
		} elseif ( ! is_array( $options ) ) {
			$options = array();
		}
		if ( is_null( $defaults ) ) {
			$defaults = array();
		} elseif ( is_object( $defaults ) ) {
			$defaults = (array) $defaults;
		} elseif ( ! is_array( $defaults ) ) {
			$defaults = array();
		}

		foreach ( $defaults as $k => $v ) {
			if ( is_null( $v ) ) {
				unset( $defaults[ $k ] );
			}
		}

		foreach ( $options as $k => $v ) {
			if ( isset( $defaults[ $k ] ) ) {
				if ( is_null( $v ) ) {
					//so can take the default value
					unset( $options[ $k ] );
				} elseif ( is_string( $v ) && ( '' === $v ) && isset( $defaults[ $k ] ) && is_array( $defaults[ $k ] ) ) {
					//a very strange case, i have a blank string for rappresenting an empty array
					unset( $options[ $k ] );
				} else {
					unset( $defaults[ $k ] );
				}
			}
		}
		foreach ( $defaults as $k => $v ) {
			$options[ $k ] = $v;
		}
		return $options;
	}

	function redirect( $location ) {
		if ( '' == $location ) {
			return;
		}
		?>
		<div id="icpRedirect" href="<?php echo esc_url( $location ); ?>"></div>
		<?php
		die();
	}

	//return the element inside array with the specified key
	function getArrayValue( $key, $array, $value = '' ) {
		$result = false;
		if ( isset( $array[ $key ] ) ) {
			$result         = $array[ $key ];
			$result['name'] = $key;
		}
		if ( false !== $result && '' != $value ) {
			if ( isset( $result[ $value ] ) ) {
				$result = $result[ $value ];
			}
		}
		return $result;
	}

	var $_sortField;
	var $_ignoreCase;
	function aksort( &$array, $sortField = 'name', $ignoreCase = true ) {
		$this->_sortField  = $sortField;
		$this->_ignoreCase = $ignoreCase;
		usort( $array, array( $this, 'aksortCompare' ) );
	}
	//not thread-safe!
	private function aksortCompare( $a, $b ) {
		if ( $a === $b || $a == $b ) {
			return 0;
		}

		$result = 0;
		$a      = $a[ $this->_sortField ];
		$b      = $b[ $this->_sortField ];
		if ( is_numeric( $a ) && is_numeric( $b ) ) {
			$result = ( $a < $b ) ? -1 : 1;
		} else {
			$a .= '';
			$b .= '';
			if ( $this->_ignoreCase ) {
				$result = strcasecmp( $a, $b );
			} else {
				$result = strcmp( $a . '', $b );
			}
		}
		return $result;
	}

	function printScriptCss() {
		global $icp;
		$uri = get_bloginfo( 'wpurl' );
		$icp->Tabs->enqueueScripts();
		$styles = 'dashicons,admin-bar,buttons,media-views,wp-admin,wp-auth-check,wp-color-picker';
		$styles = explode( ',', $styles );
		foreach ( $styles as $v ) {
			wp_enqueue_style( $v );
		}

		remove_all_actions( 'wp_print_scripts' );
		print_head_scripts();
		print_admin_styles();
	}

	public function formatCustomDate( $time, $format ) {
		$time = $this->parseDateToTime( $time );
		if ( $time > 0 ) {
			$time = gmdate( $format, $time );
		} else {
			$time = '';
		}
		return $time;
	}

	public function formatDatetime( $time = 'now' ) {
		return $this->formatCustomDate( $time, ICP_Utils::FORMAT_DATETIME );
	}
	public function formatCompactDatetime( $time = 'now' ) {
		return $this->formatCustomDate( $time, ICP_Utils::FORMAT_COMPACT_DATETIME );
	}
	public function formatDate( $time = 'date' ) {
		return $this->formatCustomDate( $time, ICP_Utils::FORMAT_DATE );
	}
	public function formatSmartDatetime( $time = 'now' ) {
		$time   = $this->parseDateToTime( $time );
		$result = '';
		if ( $time > 0 ) {
			$h = intval( gmdate( 'H', $time ) );
			$i = intval( gmdate( 'i', $time ) );
			$s = intval( gmdate( 's', $time ) );
			if ( 0 == $h && 0 == $i && 0 == $s ) {
				$result = $this->formatDate( $time );
			} else {
				$result = $this->formatDatetime( $time );
			}
		}
		return $result;
	}
	public function formatTime( $time = 'now' ) {
		return $this->formatCustomTime( $time, ICP_Utils::FORMAT_TIME );
	}
	public function formatSqlDatetime( $time = 'now' ) {
		return $this->formatCustomDate( $time, ICP_Utils::FORMAT_SQL_DATETIME );
	}
	public function formatSqlDate( $time = 'date' ) {
		return $this->formatCustomDate( $time, ICP_Utils::FORMAT_SQL_DATE );
	}
	public function formatSqlTime( $time = 'now' ) {
		return $this->formatCustomTime( $time, ICP_Utils::FORMAT_SQL_TIME );
	}

	private function formatCustomTime( $time, $format ) {
		$time = $this->parseDateToTime( $time );
		if ( $time > 86400 ) {
			$h    = gmdate( 'H', $time );
			$i    = gmdate( 'i', $time );
			$s    = gmdate( 's', $time );
			$time = $h * 3600 + $i * 60 + $s;
		}

		$s      = $time % 60;
		$time   = ( $time - $s ) / 60;
		$i      = $time % 60;
		$h      = ( $time - $i ) / 60;
		$s      = str_pad( $s, 2, '0', STR_PAD_LEFT );
		$i      = str_pad( $i, 2, '0', STR_PAD_LEFT );
		$h      = str_pad( $h, 2, '0', STR_PAD_LEFT );
		$format = str_replace( 'H', $h, $format );
		$format = str_replace( 'i', $i, $format );
		$format = str_replace( 's', $s, $format );
		return $format;
	}

	public function parseNumber( $what, $default = 0 ) {
		$result = $default;
		if ( is_array( $what ) ) {
			if ( count( $what ) > 0 ) {
				$result = doubleval( $what[0] );
			}
		} elseif ( is_numeric( $what ) ) {
			$result = doubleval( $what );
		} elseif ( is_string( $what ) || is_bool( $what ) ) {
			$result = ( $this->isTrue( $what ) ? 1 : 0 );
		}
		return $result;
	}
	public function parseDateToArray( $date ) {
		global $icp;

		$pm   = false;
		$date = strtoupper( trim( $date ) );
		if ( $icp->Utils->endsWith( $date, 'AM' ) ) {
			$date = substr( $date, 0, strlen( $date ) - 2 );
			$date = trim( $date );
		} elseif ( $icp->Utils->endsWith( $date, 'PM' ) ) {
			$date = substr( $date, 0, strlen( $date ) - 2 );
			$date = trim( $date );
			$pm   = true;
		}

		$date = explode( ' ', $date );
		if ( 1 == count( $date ) ) {
			$result = array();
			$date   = $date[0];
			$date   = str_replace( '/', '-', $date );
			if ( strpos( $date, '-' ) !== false ) {
				$date = explode( '-', $date );
				if ( count( $date ) >= 3 ) {
					$d = intval( $date[0] );
					$m = intval( $date[1] );
					$y = intval( $date[2] );
					if ( $d > 1900 ) {
						$t = $d;
						$d = $y;
						$y = $t;
					}
					if ( $y > 0 && $m > 0 && $d > 0 ) {
						$result['y'] = $y;
						$result['m'] = $m;
						$result['d'] = $d;
					}
				}
			} elseif ( strpos( $date, ':' ) !== false ) {
				$date = explode( ':', $date );
				if ( 2 == count( $date ) ) {
					$date[] = 0;
				}
				if ( count( $date ) >= 3 ) {
					$h = intval( $date[0] );
					$i = intval( $date[1] );
					$s = intval( $date[2] );
					if ( $h >= 0 && $i >= 0 && $s >= 0 ) {
						$result['h'] = $h;
						$result['i'] = $i;
						$result['s'] = $s;
					}
				}
			}
		} else {
			$a1     = $this->parseDateToArray( $date[0] );
			$a2     = $this->parseDateToArray( $date[1] );
			$result = $icp->Utils->parseArgs( $a1, $a2 );
		}

		if ( $pm && isset( $result['h'] ) ) {
			$result['h'] = intval( $result['h'] ) + 12;
		}
		return $result;
	}
	public function parseDateToTime( $date ) {
		global $icp;
		if ( is_numeric( $date ) || trim( $date ) == '' ) {
			$date = intval( $date );
			return $date;
		}

		$date = strtolower( $date );
		if ( 'now' == $date ) {
			$date = time();
			return $date;
		} elseif ( 'date' == $date ) {
			$date = strtotime( gmdate( 'Y-m-d', time() ) );
			return $date;
		} elseif ( 'time' == $date ) {
			$date = gmdate( 'H:i:s', time() );
		}
		$result   = $this->parseDateToArray( $date );
		$defaults = array(
			'y' => 0,
			'm' => 0,
			'd' => 0,
			'h' => 0,
			'i' => 0,
			's' => 0,
		);
		$a        = $icp->Utils->parseArgs( $result, $defaults );
		if ( 0 == $a['y'] && 0 == $a['m'] && 0 == $a['d'] ) {
			$result = $a['h'] * 3600 + $a['i'] * 60 + $a['s'];
		} else {
			$result = mktime( $a['h'], $a['i'], $a['s'], $a['m'], $a['d'], $a['y'] );
		}
		if ( $result < 0 ) {
			$result = 0;
		}
		return $result;
	}
	public function getIntDate( $time, $separator = '' ) {
		$time = $this->parseDateToTime( $time );
		if ( $time > 0 ) {
			if ( '' == $separator ) {
				$time = gmdate( 'Ymd', $time );
				$time = intval( $time );
			} else {
				$time = gmdate( 'Y', $time ) . $separator . gmdate( 'm', $time ) . $separator . gmdate( 'd', $time );
			}
		}

		return $time;
	}
	public function getIntMinute( $h, $m, $separator = '' ) {
		$h = intval( $h );
		$m = intval( $m );
		if ( $m < 10 ) {
			$m = '0' . $m;
		}
		$result = $h . $separator . $m;
		if ( '' == $separator ) {
			$result = intval( $result );
		}
		return $result;
	}

	//args can be a string or an associative array if you want
	public function getTextArgs( $args, $defaults = array(), $excludes = array() ) {
		$result   = $args;
		$excludes = $this->toArray( $excludes );
		if ( is_array( $result ) && count( $result ) > 0 ) {
			$result = '';
			foreach ( $args as $k => $v ) {
				if ( 0 == count( $excludes ) || ! in_array( $k, $excludes ) ) {
					$v       = trim( $v );
					$result .= ' ' . $k . '="' . $v . '"';
				}
			}
		} elseif ( ! $args ) {
			$result = '';
		}
		if ( is_array( $defaults ) && count( $defaults ) > 0 ) {
			foreach ( $defaults as $k => $v ) {
				if ( 0 == count( $excludes ) || ! in_array( $k, $excludes ) ) {
					if ( ! isset( $args[ $k ] ) ) {
						$v       = trim( $v );
						$result .= ' ' . $k . '="' . $v . '"';
					}
				}
			}
		}
		return $result;
	}
	public function queryString( $uri, $options = array() ) {
		if ( is_string( $options ) ) {
			$options = explode( '&', $options );
			$array   = array();
			foreach ( $options as $v ) {
				$v = explode( '=', $v );
				if ( count( $v ) > 1 ) {
					$array[ trim( $v[0] ) ] = trim( $v[1] );
				}
			}
			$options = $array;
		}
		if ( ! isset( $options['root'] ) || $this->isTrue( $options['root'] ) ) {
			$uri = ICP_SITE . $uri;
		}
		unset( $options['root'] );
		$uri = $this->addQueryString( $options, $uri );
		return $uri;
	}
	public function iuarray( $ids, $positive = false ) {
		$array = $this->iarray( $ids, $positive );
		$array = array_unique( $array );
		sort( $array );
		return $array;
	}
	public function iarray( $ids, $positive = false ) {
		if ( is_string( $ids ) ) {
			$ids = explode( ',', $ids );
		} elseif ( is_numeric( $ids ) ) {
			$ids = array( $ids );
		} elseif ( ! is_array( $ids ) ) {
			$ids = array();
		}

		$array = array();
		foreach ( $ids as $v ) {
			$v = trim( $v );
			if ( '' != $v ) {
				$v = intval( $v );
				if ( ! $positive || $v > 0 ) {
					$array[] = $v;
				}
			}
		}
		return $array;
	}
	public function dbarray( $ids ) {
		if ( is_string( $ids ) ) {
			$ids = explode( ',', $ids );
		} elseif ( is_numeric( $ids ) ) {
			$ids = array( $ids );
		} elseif ( ! is_array( $ids ) ) {
			$ids = array();
		}

		$array = array();
		foreach ( $ids as $v ) {
			$v = trim( $v );
			if ( '' != $v ) {
				if ( is_numeric( $v ) ) {
					$v = intval( $v );
				}
				$array[] = $v;
			}
		}
		return $array;
	}

	function isAssociativeArray( $array ) {
		if ( ! is_array( $array ) ) {
			return false;
		}

		$isArray = true;
		$i       = 0;
		foreach ( $array as $k => $v ) {
			if ( $k !== $i ) {
				$isArray = false;
				break;
			}
			++$i;
		}
		return ! $isArray;
	}
	function trim( $value ) {
		if ( is_null( $value ) ) {

		} elseif ( is_string( $value ) ) {
			$value = trim( $value );
		} elseif ( is_numeric( $value ) ) {

		} elseif ( $this->isAssociativeArray( $value ) ) {
			foreach ( $value as $k => $v ) {
				$value[ $k ] = $this->trim( $v );
			}
		} elseif ( is_object( $value ) ) {
			foreach ( $value as $k => $v ) {
				$value->$k = $this->trim( $v );
			}
		} elseif ( is_array( $value ) ) {
			for ( $i = 0; $i < count( $value ); $i++ ) {
				$v = $value[ $i ];
				$this->trim( $v );
				$value[ $i ] = $v;
			}
		}
		return $value;
	}
	function implode( $open, $close, $join, $array ) {
		$result = '';
		foreach ( $array as $v ) {
			if ( '' != $result ) {
				$result .= $join;
			}
			$result .= $open . $v . $close;
		}
		return $result;
	}
	function toArray( $text, $index = -1, $default = '' ) {
		if ( is_array( $text ) ) {
			if ( is_string( $index ) ) {
				$array = array();
				foreach ( $text as $v ) {
					$v = $this->get( $v, $index, false );
					if ( false !== $v ) {
						$array[] = $v;
					}
				}
			} else {
				$array = $text;
			}
			return $array;
		} elseif ( is_numeric( $text ) ) {
			return array( $text );
		} elseif ( is_bool( $text ) || '' === $text ) {
			return array();
		}

		if ( ( $this->startsWith( $text, '[' ) && $this->endsWith( $text, ']' ) )
			|| ( $this->startsWith( $text, '{' ) && $this->endsWith( $text, '}' ) ) ) {
			$text = substr( $text, 1, strlen( $text ) - 2 );
		}
		$text = str_replace( '|', ',', $text );
		$text = explode( ',', $text );

		//exclude empty string
		$array = array();
		foreach ( $text as $t ) {
			if ( '' !== $t ) {
				$array[] = $t;
			}
		}
		$text = $array;
		if ( $index > -1 ) {
			$result = $default;
			if ( isset( $text[ $index ] ) ) {
				$result = $text[ $index ];
			}
			$text = $result;
		}
		return $text;
	}

	function getFileTextSize( $size ) {
		$units = array( 'B', 'KB', 'MB', 'GB' );
		for ( $i = 0; $i < count( $units ); $i++ ) {
			if ( $size < 1024 ) {
				break;
			} else {
				$size /= 1024;
			}
		}
		return intval( $size ) . ' ' . $units[ $i ];
	}
	function getFileTextExt( $source ) {
		$ext = strrpos( $source, '.' );
		if ( false !== $ext ) {
			$ext = strtolower( substr( $source, $ext + 1 ) );
		} else {
			$ext = $source;
		}
		$ext  = strtolower( $ext );
		$text = 'text';
		switch ( $ext ) {
			case 'doc':
			case 'docx':
			case 'odt':
				$text = 'word';
				break;
			case 'xls':
			case 'xlsx':
			case 'ods':
				$text = 'excel';
				break;
			case 'ppt':
			case 'pptx':
			case 'odp':
				$text = 'powerpoint';
				break;
			case 'zip':
			case 'tar':
			case 'gzip':
			case 'rar':
			case '7z':
				$text = 'archive';
				break;
			case 'mp3':
			case 'wav':
				$text = 'audio';
				break;
			case 'mpeg':
			case 'mpg':
			case 'avi':
			case 'mp4':
				$text = 'video';
				break;
			case 'gif':
			case 'jpg':
			case 'jpeg':
			case 'png':
			case 'bmp':
				$text = 'image';
				break;
			case 'pdf':
				$text = 'pdf';
				break;
		}
		return $text;
	}
	function match( $value, $array, $default = '', $ignoreCase = true ) {
		$result = $default;
		if ( $ignoreCase ) {
			$value = strtolower( $value );
		}
		foreach ( $array as $k => $v ) {
			$v = $this->toArray( $v );
			foreach ( $v as $c ) {
				if ( $ignoreCase ) {
					$c = strtolower( $c );
				}
				if ( $value == $c || strpos( $value, $c ) !== false ) {
					$result = $k;
					break;
				}
			}

			if ( $result !== $default ) {
				break;
			}
		}
		return $result;
	}

	function pickColor() {
		$names  = explode( '|', 'primary|success|warning|danger|info|alert|system|dark' );
		$colors = explode( '|', '3498db|70ca63|f6bb42|df5640|3bafda|967adc|37bc9b|666' );

		$i      = ( $this->colorIndex % count( $colors ) );
		$names  = $names[ $i ];
		$colors = $colors[ $i ];
		++$this->colorIndex;
		return array( $names, '#' . $colors );
	}
	function upperUnderscoreCase( $text ) {
		$text = $this->arrayCase( $text );
		$text = implode( '_', $text );
		$text = strtoupper( $text );
		return $text;
	}
	function lowerUnderscoreCase( $text ) {
		$text = $this->upperUnderscoreCase( $text );
		$text = strtolower( $text );
		return $text;
	}

	function toListArrayFromClass( $array, $id = false, $value = false ) {
		global $icp;
		$result = array();
		if ( false !== $array && count( $array ) > 0 ) {
			foreach ( $array as $k => $v ) {
				if ( false !== $id ) {
					$k = $icp->Utils->get( $v, $id );
				}
				if ( false !== $value ) {
					$v = $icp->Utils->get( $v, $value );
				}

				if ( '' != $k && '' != $v ) {
					$result[] = array(
						'id'   => $k,
						'text' => $v,
					);
				}
			}
		}
		return $result;
	}
	function toFormatListArrayFromListObjects( $array, $idField, $textFormat ) {
		global $icp;
		$result = array();
		if ( false !== $array && count( $array ) > 0 ) {
			foreach ( $array as $item ) {
				break;
			}
			$columns = array();
			if ( is_object( $item ) ) {
				$columns = $icp->Dao->Utils->getColumns( $item );
			} elseif ( is_array( $item ) ) {
				foreach ( $item as $k => $v ) {
					$columns[ $k ] = $v;
				}
			}
			foreach ( $array as $i => $e ) {
				$text = $textFormat;
				foreach ( $columns as $k => $v ) {
					$v = $icp->Utils->get( $e, $k, '' );
					if ( is_array( $v ) ) {
						$v = implode( ', ', $v );
					}
					$text = str_replace( '{' . $k . '}', $v, $text );
				}

				$id = $i;
				if ( false !== $idField && '' !== $idField ) {
					$id = $e->$idField;
				}
				if ( '' != $id ) {
					$result[] = array(
						'id'   => $id,
						'text' => $text,
					);
				}
			}
		}
		return $result;
	}
	function toListArrayFromListObjects( $array, $idFrom = false, $textFrom = 'name', $idTo = 'id', $textTo = 'text' ) {

		$result = array();
		foreach ( $array as $v ) {
			$sId   = $v;
			$sText = $v;
			if ( false !== $idFrom ) {
				$sId   = $this->get( $v, $idFrom, false );
				$sText = $this->get( $v, $textFrom, false );
			}
			if ( false !== $sId && '' != $sText ) {
				if ( '' != $sId ) {
					$result[] = array(
						$idTo   => $sId,
						$textTo => $sText,
					);
				}
			}
		}
		return $result;
	}
	function toColorListArrayFromListObjects( $array, $colors, $id = 'id', $text = 'name' ) {
		global $icp;
		$result = array();
		foreach ( $array as $instance ) {
			$sId   = $this->get( $instance, $id, false );
			$sText = $this->get( $instance, $text, false );
			foreach ( $colors as $color => $when ) {
				$success = false;
				foreach ( $when['conditions'] as $conditionKey => $conditionValue ) {
					$conditionValue = $icp->Utils->toArray( $conditionValue );
					$c              = $this->get( $instance, $conditionKey, false );
					if ( false !== $c ) {
						$c .= '';
						foreach ( $conditionValue as $v ) {
							$v .= '';
							if ( $c === $v ) {
								$success = true;
								break;
							}
						}
					}
					if ( $success ) {
						break;
					}
				}

				if ( $success ) {
					$style = 'color:' . $color . '; ';
					if ( isset( $when['bold'] ) && $when['bold'] ) {
						$style .= 'font-weight:bold; ';
					}
					$sText = '<span style="' . $style . '">' . $sText . '</span>';
				}
			}
			if ( '' != $sId && false !== $sText ) {
				$result[] = array(
					'id'   => $sId,
					'text' => $sText,
				);
			}
		}
		return $result;
	}
	function md5() {
		$array  = func_get_args();
		$buffer = '';
		foreach ( $array as $v ) {
			$buffer .= ':)' . $v;
		}
		$buffer = md5( $buffer );
		return $buffer;
	}
	function arrayCase( $text ) {
		$buffer    = '';
		$array     = array();
		$text      = str_split( $text );
		$prevUpper = false;
		$nextUpper = false;
		foreach ( $text as $c ) {
			if ( $c >= 'a' && $c <= 'z' ) {
				if ( $nextUpper ) {
					if ( '' != $buffer ) {
						$array[] = $buffer;
						$buffer  = '';
					}
					$c = strtoupper( $c );
				}
				$buffer   .= $c;
				$nextUpper = false;
				$prevUpper = false;
			} elseif ( $c >= '0' && $c <= '9' ) {
				$buffer   .= $c;
				$nextUpper = true;
			} elseif ( $c >= 'A' && $c <= 'Z' ) {
				if ( ! $prevUpper ) {
					if ( '' != $buffer ) {
						$array[] = $buffer;
						$buffer  = '';
					}
				}
				$buffer   .= $c;
				$nextUpper = false;
				$prevUpper = true;
			} else {
				if ( '' != $buffer ) {
					$array[] = $buffer;
					$buffer  = '';
				}
				$nextUpper = true;
				$prevUpper = false;
			}
		}
		if ( '' != $buffer ) {
			$array[] = $buffer;
		}
		return $array;
	}
	function lowerCamelCase( $text ) {
		$buffer = '';
		if ( strpos( $text, '_' ) !== false || strpos( $text, '-' ) !== false ) {
			$text = strtolower( $text );
		}

		$text      = str_split( $text );
		$allUpper  = true;
		$nextUpper = false;
		foreach ( $text as $c ) {
			if ( $c >= 'a' && $c <= 'z' ) {
				$allUpper = false;
				if ( $nextUpper ) {
					$c = strtoupper( $c );
				}
				$buffer   .= $c;
				$nextUpper = false;
			} elseif ( $c >= '0' && $c <= '9' ) {
				$buffer   .= $c;
				$nextUpper = true;
			} elseif ( $c >= 'A' && $c <= 'Z' ) {
				$buffer   .= $c;
				$nextUpper = false;
			} else {
				$nextUpper = true;
			}
		}
		if ( $allUpper ) {
			$buffer = strtolower( $buffer );
		} else {
			$buffer = lcfirst( $buffer );
		}
		return $buffer;
	}
	function upperCamelCase( $text ) {
		$text = $this->lowerCamelCase( $text );
		$text = ucfirst( $text );
		return $text;
	}

	function castStdClass( $a ) {
		$a = (array) $a;
		$r = new stdClass();
		foreach ( $a as $k => $v ) {
			$r->$k = $v;
		}
		return $r;
	}
	function castArray( $a ) {
		$r = $a;
		if ( is_object( $a ) ) {
			$r = (array) $a;
		}

		if ( ! is_array( $r ) ) {
			$r = array();
		}
		return $r;
	}
	public function copyArray( $array ) {
		$temp = array();
		foreach ( $array as $k => $v ) {
			$temp[ $k ] = $v;
		}
		return $temp;
	}
	public function isObject( $v ) {
		return ( false !== $v && ! is_null( $v ) && is_object( $v ) );
	}
	public function isArray( $v ) {
		return ( false !== $v && ! is_null( $v ) && is_array( $v ) );
	}
	public function getConstants( $class, $prefix, $reverse = false ) {
		global $icp;
		if ( is_object( $class ) ) {
			$class = get_class( $class );
		}
		$class  = str_replace( 'Search', '', $class );
		$class  = str_replace( 'Constants', '', $class );
		$class .= 'Constants';
		if ( ! class_exists( $class ) ) {
			$class = ICP_PLUGIN_PREFIX . $class;
		}

		$result = array();
		if ( class_exists( $class ) ) {
			$reflection = new ReflectionClass( $class );
			$array      = $reflection->getConstants();
			foreach ( $array as $k => $v ) {
				$pos = 0;
				if ( '' != $prefix ) {
					$pos = stripos( $k, $prefix );
				}
				if ( 0 === $pos ) {
					if ( $reverse ) {
						$result[ $v ] = $k;
					} else {
						$result[ $k ] = $v;
					}
				}
			}
		}
		return $result;
	}
	public function getConstantValue( $class, $prefix, $name, $default = false ) {
		/* @var $ec ICP_Singleton */
		global $ec;
		$result = $default;
		if ( is_object( $class ) ) {
			$class = get_class( $class );
		}
		$class  = str_replace( 'Search', '', $class );
		$class  = str_replace( 'Constants', '', $class );
		$class .= 'Constants';
		if ( ! class_exists( $class ) ) {
			$class = ICP_PLUGIN_PREFIX . $class;
		}

		if ( class_exists( $class ) ) {
			$name       = $prefix . '_' . $name;
			$name       = $ec->Utils->upperUnderscoreCase( $name );
			$reflection = new ReflectionClass( $class );
			$result     = $reflection->getConstant( $name );
		}
		return $result;
	}
	public function getConstantName( $class, $prefix, $value, $default = false ) {
		/* @var $ec ICP_Singleton */
		$constants = $this->getConstants( $class, $prefix, true );
		$result    = $default;
		if ( isset( $constants[ $value ] ) ) {
			$result = $constants[ $value ];
		}
		return $result;
	}
	public function daysDiff( $dt1, $dt2 ) {
		$dt1    = $this->parseDateToTime( $dt1 );
		$dt2    = $this->parseDateToTime( $dt2 );
		$result = ( $dt2 - $dt1 ) / 86400;
		$result = intval( $result );
		return $result;
	}
	public function getFirstLastDayOfWeek( $dt ) {
		$dt = $this->parseDateToTime( $dt );
		// Get the day of the week: Sunday=0 to Saturday=6
		$dotw = gmdate( 'w', $dt );
		if ( $dotw > 1 ) {
			$dt1 = $dt - ( ( $dotw - 1 ) * 24 * 60 * 60 );
			$dt2 = $dt + ( ( 7 - $dotw ) * 24 * 60 * 60 );
		} elseif ( 1 == $dotw ) {
			$dt1 = $dt;
			$dt2 = $dt + ( ( 7 - $dotw ) * 24 * 60 * 60 );
		} elseif ( 0 == $dotw ) {
			$dt1 = $dt - ( 6 * 24 * 60 * 60 );

			$dt2 = $dt;
		}

		$result = array( $dt1, $dt2 );
		return $result;
	}
	public function toMap( $array, $key = false, $value = false ) {
		$result = array();
		if ( is_string( $array ) ) {
			$array = $this->toArray( $array );
			$key   = false;
			$value = false;
		}
		if ( ! is_array( $array ) ) {
			$array = array();
		}

		foreach ( $array as $v ) {
			$k = $v;
			if ( false !== $key ) {
				$k = $this->get( $v, $key, false );
				if ( false !== $value ) {
					$v = $this->get( $v, $value, false );
				}
			}

			if ( false !== $k && false !== $v ) {
				$result[ $k ] = $v;
			}
		}
		return $result;
	}
	public function getText( $text, $args ) {
		if ( false === $args || 0 == count( $args ) ) {
			return $text;
		}

		foreach ( $args as $k => $v ) {
			$text = str_replace( '{' . $k . '}', $v, $text );
		}
		return $text;
	}
	public function arrayExtends( $options, $defaults ) {
		global $icp;
		$options = $icp->Utils->parseArgs( $options, $defaults );
		foreach ( $options as $k => $v ) {
			if ( is_bool( $v ) ) {
				$v = ( $v ? 1 : 0 );
			}
			if ( isset( $defaults[ $k ] ) ) {
				if ( $this->isAssociativeArray( $v ) ) {
					$v = $this->arrayExtends( $v, $defaults[ $k ] );
				} else {
					$v   = $icp->Utils->toArray( $v );
					$old = $defaults[ $k ];
					$old = $icp->Utils->toArray( $old );
					if ( ! $this->isAssociativeArray( $old ) ) {
						$v = array_merge( $v, $old );
						$v = array_unique( $v );
					}
				}
			} else {
				$v = $icp->Utils->toArray( $v );
			}
			$options[ $k ] = $v;
		}
		return $options;
	}
	//send remote request to our server to store tracking and feedback
	function remotePost( $action, $data = '' ) {
		global $icp;

		$data['secret'] = 'WYSIWYG';
		$response       = wp_remote_post(
			ICP_INTELLYWP_ENDPOINT . '?iwpm_action=' . $action,
			array(
				'method'      => 'POST',
				'timeout'     => 20,
				'redirection' => 5,
				'httpversion' => '1.1',
				'blocking'    => true,
				'body'        => $data,
				'user-agent'  => ICP_PLUGIN_NAME . '/' . ICP_PLUGIN_VERSION . '; ' . get_bloginfo( 'url' ),
			)
		);
		$data           = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) != 200
			|| ! isset( $data['success'] ) || ! $data['success']
		) {
			$icp->Log->error( 'ERRORS SENDING REMOTE-POST ACTION=%s DUE TO REASON=%s', $action, $response );
			$data = false;
		} else {
			$icp->Log->debug( 'SUCCESSFULLY SENT REMOTE-POST ACTION=%s RESPONSE=%s', $action, $data );
		}
		return $data;
	}

	function isAdminUser() {
		//https://wordpress.org/support/topic/how-to-check-admin-right-without-include-pluggablephp
		return true;
	}
	function isUserLogged() {
		if ( ! function_exists( 'is_user_logged_in' ) ) {
			require_once( ABSPATH . WPINC . '/pluggable.php' );
		}
		$result = is_user_logged_in();
		return $result;
	}
	function isPluginPage() {
		global $icp;
		$page   = $icp->Utils->qs( 'page' );
		$result = ( $this->startsWith( $page, ICP_PLUGIN_SLUG ) );
		return $result;
	}
	function isQsNull( $v ) {
		return ( is_null( $v ) || false === $v || '' === $v );
	}
	function jsonToClass( $json, $class ) {
		global $icp;
		$instance = $icp->Dao->Utils->getClass( $class );
		if ( '' == $instance ) {
			throw new Exception( 'CLASS [' . $class . '] DOES NOT EXIST' );
		}
		$result = false;
		if ( is_bool( $json ) ) {
			return $json;
		}
		if ( is_string( $json ) ) {
			$json = json_decode( $json );
		}
		if ( $icp->Utils->isArray( $json ) && ! $icp->Utils->isAssociativeArray( $json ) ) {
			$match  = false;
			$result = array();
			foreach ( $json as $v ) {
				$v = $this->jsonToInstance( $json, $v );
				if ( false !== $v ) {
					$result[] = $v;
					$match    = true;
				}
			}
			if ( ! $match ) {
				$result = false;
			}
		} elseif ( $icp->Utils->isAssociativeArray( $json ) || is_object( $json ) ) {
			$result = $this->jsonToInstance( $json, $class );
		}
		return $result;
	}
	private function jsonToInstance( $json, $class ) {
		global $icp;

		$match    = false;
		$result   = false;
		$columns  = $icp->Dao->Utils->getAllColumns( $class );
		$instance = $icp->Dao->Utils->getClass( $class );
		$instance = new $instance();
		foreach ( $json as $property => $value ) {
			if ( isset( $columns[ $property ] ) ) {
				$column = $columns[ $property ];
				if ( isset( $column['ui-type'] ) && 'array' == $column['ui-type'] ) {
					$array   = array();
						$rel = $column['rel'];
					foreach ( $value as $k => $v ) {
						$v           = $this->jsonToInstance( $v, $rel );
						$array[ $k ] = $v;
					}
						$value = $array;
				} else {
					$value = $icp->Dao->Utils->decode( $class, $property, $value );
				}
			}
			if ( $icp->Utils->set( $instance, $property, $value ) ) {
				$match = true;
			}
		}
		if ( $match ) {
			$result = $instance;
		}
		return $result;
	}
	public function classToJson( $instance ) {
		if ( ! is_object( $instance ) ) {
			$instance = (array) $instance;
		}
		$result = wp_json_encode( $instance );
			return $result;
	}

	function dateGt( $dt1, $dt2 ) {
		$dt1 = $this->parseDateToTime( $dt1 );
		$dt2 = $this->parseDateToTime( $dt2 );
		return ( $dt1 > $dt2 );
	}
	function dateGtEq( $dt1, $dt2 ) {
		$dt1 = $this->parseDateToTime( $dt1 );
		$dt2 = $this->parseDateToTime( $dt2 );
		return ( $dt1 >= $dt2 );
	}
	function dateEq( $dt1, $dt2 ) {
		$dt1 = $this->parseDateToTime( $dt1 );
		$dt2 = $this->parseDateToTime( $dt2 );
		return ( $dt1 == $dt2 );
	}
	function dateLt( $dt1, $dt2 ) {
		$dt1 = $this->parseDateToTime( $dt1 );
		$dt2 = $this->parseDateToTime( $dt2 );
		return ( $dt1 < $dt2 );
	}
	function dateLtEq( $dt1, $dt2 ) {
		$dt1 = $this->parseDateToTime( $dt1 );
		$dt2 = $this->parseDateToTime( $dt2 );
		return ( $dt1 <= $dt2 );
	}
	function absDateDiff( $dt1, $dt2, $unit = 'd' ) {
		$diff = $this->dateDiff( $dt1, $dt2, $unit );
		$diff = abs( $diff );
		return $diff;
	}
	function dateDiff( $dt1, $dt2, $unit = 'd' ) {
		$dt1  = $this->formatSqlDatetime( $dt1 );
		$dt2  = $this->formatSqlDatetime( $dt2 );
		$dt1  = new DateTime( $dt1 );
		$dt2  = new DateTime( $dt2 );
		$diff = $dt1->diff( $dt2 );

		$result = 0;
		switch ( $unit ) {
			case 'Y':
			case 'y':
				$result = $diff->y;
				break;
			case 'm':
				$result = $diff->m;
				break;
			case 'd':
				$result = $diff->days;
				break;
			case 'H':
			case 'h':
				$result = $diff->h;
				break;
			case 'n':
			case 'i':
				$result = $diff->i;
				break;
			case 's':
				$result = $diff->s;
				break;
		}
		return $result;
	}
	public function arrayPush( &$array, $another ) {
		if ( ! is_array( $another ) ) {
			array_push( $array, $another );
		} elseif ( is_array( $another ) ) {
			foreach ( $another as $v ) {
				array_push( $array, $v );
			}
		}
		return $array;
	}
	public function encodeData( $data ) {
		$dataType  = '';
		$dataClass = '';
		$text      = false;
		if ( is_object( $data ) ) {
			$dataType  = 'class';
			$dataClass = get_class( $data );
			$text      = $this->classToJson( $data );
		} elseif ( is_array( $data ) ) {
			$dataType = 'array';
			if ( count( $data ) > 0 ) {
				//array of class??
				$associative = $this->isAssociativeArray( $data );
				foreach ( $data as $k => $v ) {
					break;
				}
				if ( is_object( $v ) ) {
					$dataType  = 'class';
					$dataClass = get_class( $v );
					$text      = $this->classToJson( $data );
				} else {
					$text = json_encode( $data );
				}
			} else {
				$text = json_encode( $data );
			}
		} else {
			$dataType = 'primitive';
			$text     = json_encode( $data );
		}

		$result = array(
			'dataType'  => $dataType,
			'dataClass' => $dataClass,
			'data'      => $this->httpEncode( $text ),
		);
		return $result;
	}
	public function decodeData( $data = array() ) {
		$defaults     = array(
			'dataType'  => $this->qs( 'dataType', '' ),
			'dataClass' => $this->qs( 'dataClass', '' ),
			'data'      => $this->qs( 'data', '' ),
		);
		$data         = $this->parseArgs( $data, $defaults );
		$data['data'] = $this->httpDecode( $data['data'] );

		//$data['data']=str_replace("\\\"", "\"", $data['data']);
		//$data['data']=str_replace("\\\\", "\\", $data['data']);

		$result = false;
		switch ( strtolower( $data['dataType'] ) ) {
			case 'array':
				$result = json_decode( $data['data'], true );
				break;
			case 'class':
				if ( class_exists( $data['dataClass'] ) && $this->startsWith( $data['dataClass'], ICP_PLUGIN_PREFIX ) ) {
					$result = $this->jsonToClass( $data['data'], $data['dataClass'] );
				} else {
					$result = json_decode( $data['data'], true );
				}
				break;
			default:
				$result = json_decode( $data['data'] );
				break;
		}
		return $result;
	}
	public function getConstantsValues( $class, $prefix = '', $glue = false ) {
		$array  = $this->getConstants( $class, $prefix );
		$result = array_values( $array );
		if ( false !== $glue ) {
			$result = implode( $glue, $result );
		}
		return $result;

	}
	public function getValue( $array, $index, $default = false ) {
		$result = $this->getIndex( $array, $index, $default );
		if ( $result !== $default ) {
			$result = $result['v'];
		}
		return $result;
	}
	public function getKey( $array, $index, $default = false ) {
		$result = $this->getIndex( $array, $index, $default );
		if ( $result !== $default ) {
			$result = $result['k'];
		}
		return $result;
	}
	public function getIndex( $array, $index, $default = false ) {
		$result = $default;
		if ( is_array( $array ) && count( $array ) > 0 ) {
			if ( $this->isAssociativeArray( $array ) ) {
				$i = 0;
				foreach ( $array as $k => $v ) {
					if ( $index == $i ) {
						$result = array(
							'k' => $k,
							'v' => $v,
						);
						break;
					}
					$i++;
				}
			} else {
				if ( $index < count( $array ) && $index >= 0 ) {
					$result = $array[ $index ];
				}
			}
		}
		return $result;
	}
	public function isEmpty( $v ) {
		if ( ! $v ) {
			return true;
		}

		$result = false;
		if ( is_string( $v ) ) {
			$result = ( '' == $v );
		} elseif ( is_array( $v ) ) {
			$result = 0 == count( $v );
		} elseif ( is_object( $v ) ) {
			$result = true;
			foreach ( $v as $k => $w ) {
				if ( ! is_null( $w ) && '' !== $w ) {
					$result = false;
					break;
				}
			}
		}
		return $result;
	}
	public function httpEncode( $v ) {
		$v = gzcompress( $v );
		$v = bin2hex( $v );
		return $v;
	}
	public function httpDecode( $v ) {
		$v = hex2bin( $v );
		$v = gzuncompress( $v );
		return $v;
	}
	public function trimHttp( $uri ) {
		$uri = str_replace( 'http://', '', $uri );
		$uri = str_replace( 'https://', '', $uri );
		return $uri;
	}

	function getClientIpAddress() {
		$ipaddress = '';
		if ( getenv( 'HTTP_CLIENT_IP' ) ) {
			$ipaddress = getenv( 'HTTP_CLIENT_IP' );
		} elseif ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
			$ipaddress = getenv( 'HTTP_X_FORWARDED_FOR' );
		} elseif ( getenv( 'HTTP_X_FORWARDED' ) ) {
			$ipaddress = getenv( 'HTTP_X_FORWARDED' );
		} elseif ( getenv( 'HTTP_FORWARDED_FOR' ) ) {
			$ipaddress = getenv( 'HTTP_FORWARDED_FOR' );
		} elseif ( getenv( 'HTTP_FORWARDED' ) ) {
			$ipaddress = getenv( 'HTTP_FORWARDED' );
		} elseif ( getenv( 'REMOTE_ADDR' ) ) {
			$ipaddress = getenv( 'REMOTE_ADDR' );
		} else {
			$ipaddress = 'UNKNOWN';
		}

		$ipaddress = ( '::1' == $ipaddress ) ? '192.168.0.1' : $ipaddress;
		return $ipaddress;
	}
	public function isMail( $mail ) {
		$at     = strpos( $mail, '@' );
		$dot    = strrpos( $mail, '.' );
		$result = false;
		if ( false !== $at && false !== $dot && $at < $dot ) {
			$result = true;
		}
		return $result;
	}
	public function getNameFromListArray( $array, $id, $default = false ) {
		$result = $default;
		foreach ( $array as $v ) {
			if ( $v['id'] == $id ) {
				if ( isset( $v['text'] ) ) {
					$result = $v['text'];
					break;
				} elseif ( isset( $v['name'] ) ) {
					$result = $v['name'];
					break;
				}
			}
		}
		return $result;
	}
	function bqs( $name, $default = false ) {
		$v      = $this->qs( $name, '' );
		$result = $default;
		if ( '' != $v ) {
			if ( is_numeric( $v ) ) {
				$v      = intval( $v );
				$result = ( $v > 0 );
			} else {
				$result = $this->isTrue( $v );
			}
		}
		return $result;
	}
	/**
	 * Get either a Gravatar URL or complete image tag for a specified email address.
	 *
	 * @param string $email The email address
	 * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
	 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
	 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
	 * @param boole $img True to return a complete IMG tag False for just the URL
	 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
	 * @return String containing either just a URL or a complete image tag
	 * @source http://gravatar.com/site/implement/images/php/
	 */
	function getGravatarImage( $email, $s = 80, $d = 'mm', $r = 'g', $img = true, $atts = array() ) {
		if ( ! is_array( $atts ) ) {
			$atts = array();
		}
		if ( ! isset( $atts['class'] ) ) {
			$atts['class'] = 'gravatar';
		}
		$url  = '//www.gravatar.com/avatar/';
		$url .= md5( strtolower( trim( $email ) ) );
		$url .= "?s=$s&d=$d&r=$r";
		if ( $img ) {
			$url = '<img src="' . $url . '"';
			foreach ( $atts as $key => $val ) {
				$url .= ' ' . $key . '="' . $val . '"';
			}
			$url .= ' />';
		}
		return $url;
	}
	function getGravatarUri( $email, $s = 80, $d = 'mm', $r = 'g', $atts = array() ) {
		$url = $this->getGravatarImage( $email, $s, $d, $r, false, $atts );
		return $url;
	}
	function getFunctionName( $function ) {
		$result = false;
		if ( is_string( $function ) ) {
			$result = $function;
		} elseif ( is_array( $function ) ) {
			$result = $function[1];
		}
		return $result;
	}
	function functionExists( $function ) {
		$result = false;
		if ( is_string( $function ) ) {
			$result = function_exists( $function );
		} elseif ( is_array( $function ) ) {
			$result = method_exists( $function[0], $function[1] );
		}
		return $result;
	}
	function functionCall() {
		$args = func_get_args();
		if ( false === $args || 0 == count( $args ) ) {
			return;
		}

		$function = array_shift( $args );
		$result   = null;
		if ( $this->functionExists( $function ) ) {
			$result = call_user_func_array( $function, $args );
		}
		return $result;
	}
	function cqs( $class, $prefix = '' ) {
		global $icp;
		$result = $icp->Dao->Utils->qs( $class, $prefix );
		return $result;
	}
	function passwordsEquals( $p1, $p2 ) {
		if ( ! function_exists( 'wp_check_password' ) ) {
			require_once( ABSPATH . WPINC . '/pluggable.php' );
		}
		$result = wp_check_password( $p1, $p2 );
		return $result;
	}
	public function contains( $v1, $v2, $ignoreCase = true ) {
		$result = false;
		if ( $ignoreCase ) {
			$result = stripos( $v1, $v2 ) !== false;
		} else {
			$result = strpos( $v1, $v2 ) !== false;
		}
		return $result;
	}

	public function getMailContentType() {
		return 'text/html';
	}

	private function getHtmlCode( $value ) {
		$value = str_replace( '\"', '', $value );
		$value = str_replace( '"', '', $value );
		return $value;
	}

	public function dequeueScripts( $array ) {
		if ( ! function_exists( 'wp_scripts' ) || function_exists( 'wp_dequeue_script' ) ) {
			return;
		}

		$array   = $this->toArray( $array );
		$scripts = wp_scripts();
		/* @var $v _WP_Dependency */
		foreach ( $scripts->registered as $k => $v ) {
			foreach ( $array as $pattern ) {
				if ( $this->contains( $v->src, $pattern ) || $this->contains( $v->handle, $pattern ) ) {
					wp_dequeue_script( $v->handle );
					break;
				}
			}
		}
	}
	public function dequeueStyles( $array ) {
		if ( ! function_exists( 'wp_styles' ) || function_exists( 'wp_dequeue_style' ) ) {
			return;
		}

		$array  = $this->toArray( $array );
		$styles = wp_styles();
		/* @var $v _WP_Dependency */
		foreach ( $styles->registered as $k => $v ) {
			foreach ( $array as $pattern ) {
				if ( $this->contains( $v->src, $pattern ) || $this->contains( $v->handle, $pattern ) ) {
					wp_dequeue_style( $v->handle );
					break;
				}
			}
		}
	}
	public function formatSeconds( $time ) {
		if ( '' === $time ) {
			return '';
		}

		$time    = intval( $time );
		$seconds = ( $time % 60 );
		$time    = ( ( $time - $seconds ) / 60 );
		$minutes = ( $time % 60 );
		$time    = ( ( $time - $minutes ) / 60 );
		$hours   = ( $time % 24 );
		$time    = ( ( $time - $hours ) / 24 );
		$days    = $time;

		$array = array();
		if ( $seconds > 0 ) {
			$array[] = $seconds . 's';
		}
		if ( $minutes > 0 ) {
			$array[] = $minutes . 'm';
		}
		if ( $hours > 0 ) {
			$array[] = $hours . 'h';
		}
		if ( $days > 0 ) {
			$array[] = $days . 'd';
		}
		$array = array_reverse( $array );
		$text  = implode( ' ', $array );
		return $text;
	}
	function logout() {
		if ( ! function_exists( 'wp_logout' ) ) {
			require_once( ABSPATH . WPINC . '/pluggable.php' );
		}
		wp_logout();
		return true;
	}
	function formatPercentage( $value, $options = array() ) {
		if ( is_bool( $options ) ) {
			$options = array( 'symbol' => $options );
		}
		$defaults = array( 'symbol' => true );
		$options  = $this->parseArgs( $options, $defaults );

		$value = floatval( $value );
		$value = round( $value, 3 );
		$value = number_format( $value, 3, ',', '' );
		if ( $options['symbol'] ) {
			$value .= ' %';
		}
		return $value;
	}
	function formatCurrencyMoney( $value, $options = array() ) {
		$defaults = array( 'currency' => $this->getDefaultCurrencySymbol() );
		$options  = $this->parseArgs( $options, $defaults );

		$value = $this->formatMoney( $value, $options );
		return $value;
	}
	function formatMoney( $value, $options = array() ) {
		if ( is_string( $options ) ) {
			$options = array( 'currency' => $options );
		}
		$defaults = array( 'currency' => false );
		$options  = $this->parseArgs( $options, $defaults );

		$value = floatval( $value );
		$value = round( $value, 3 );
		$value = number_format( $value, 3, ',', '.' );
		if ( '' != $options['currency'] ) {
			$symbol = $options['currency'];
			if ( strlen( $symbol ) > 1 ) {
				$symbol = $this->getCurrencySymbol( $symbol );
			}
			$value .= ' ' . $symbol;
		}
		return $value;
	}
	function sortOptions( &$options ) {
		if ( ! is_array( $options ) ) {
			return $options;
		}

		usort( $options, array( $this, 'sortOptions_Compare' ) );
		return $options;
	}
	public function sortOptions_Compare( $o1, $o2 ) {
		global $icp;
		$v1 = $icp->Utils->get( $o1, 'text', false );
		if ( false === $v1 ) {
			$v1 = $icp->Utils->get( $o1, 'name', false );
		}
		$v2 = $icp->Utils->get( $o2, 'text', false );
		if ( false === $v2 ) {
			$v2 = $icp->Utils->get( $o2, 'name', false );
		}

		//to order properly
		if ( $icp->Utils->startsWith( $v1, '[' ) ) {
			$v1 = ' ' . $v1;
		}
		if ( $icp->Utils->startsWith( $v2, '[' ) ) {
			$v2 = ' ' . $v2;
		}
		return strcasecmp( $v1, $v2 );
	}

	private function validate_ip( $ip ) {
		$ip = wp_unslash( $ip );
		if ( filter_var( $ip, FILTER_VALIDATE_IP ) ) {
			return $ip;
		}
		return '';
	}

	public function getVisitorIpAddress() {
		$ip = '';
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = $this->validate_ip( $_SERVER['HTTP_CLIENT_IP'] );
		}

		if ( '' == $ip && ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip = $this->validate_ip( $_SERVER['HTTP_X_FORWARDED_FOR'] );
		}

		if ( '' == $ip && ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
			$ip = $this->validate_ip( $_SERVER['REMOTE_ADDR'] );
		}
		return $ip;
	}

	public function toEmailsArray( $data ) {
		if ( ! is_array( $data ) ) {
			$data = str_replace( ',', '|', $data );
			$data = str_replace( ';', '|', $data );
			$data = str_replace( ' ', '|', $data );
			$data = $this->toArray( $data );
		}

		$result = array();
		foreach ( $data as $v ) {
			$v = trim( $v );
			if ( function_exists( 'is_email' ) ) {
				if ( ! is_email( $v ) ) {
					$v = '';
				}
			} elseif ( ! filter_var( $v, FILTER_VALIDATE_EMAIL ) ) {
				$v = '';
			}
			if ( '' != $v ) {
				$result[ $v ] = $v;
			}
		}
		$result = array_keys( $result );
		return $result;
	}
	function getCustomFields( $fields ) {
		$items  = $this->toArray( $fields );
		$result = array();
		foreach ( $items as $v ) {
			$name         = str_replace( '_', ' ', $v );
			$name         = str_replace( '-', ' ', $name );
			$name         = str_replace( '$', '', $name );
			$name         = ucwords( $name );
			$result[ $v ] = array(
				'name'   => $name,
				'format' => 'text',
			);
		}
		return $result;
	}
	function getCurrencySymbol( $currency ) {
		// Create a NumberFormatter
		$locale    = 'en_US';
		$formatter = new NumberFormatter( $locale, NumberFormatter::CURRENCY );

		// Figure out what 0.00 looks like with the currency symbol
		$withCurrency = $formatter->formatCurrency( 0, $currency );

		// Figure out what 0.00 looks like without the currency symbol
		$formatter->setPattern( str_replace( '¤', '', $formatter->getPattern() ) );
		$withoutCurrency = $formatter->formatCurrency( 0, $currency );

		// Extract just the currency symbol from the first string
		return str_replace( $withoutCurrency, '', $withCurrency );
	}
	function encodeUri( $string ) {
		$entities     = array( '%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%23', '%5B', '%5D' );
		$replacements = array( '!', '*', "'", '(', ')', ';', ':', '@', '&', '=', '+', '$', ',', '/', '?', '#', '[', ']' );
		$result       = urlencode( $string );
		//$result=str_replace($replacements, $entities, $result);
		return $result;
	}
	function addQueryString( $args, $uri ) {
		if ( ! is_array( $args ) || 0 == count( $args ) ) {
			return $uri;
		}

		$params = array();
		foreach ( $args as $k => $v ) {
			$params[] = $k . '=' . $this->encodeUri( $v );
		}
		$params = implode( '&', $params );
		if ( $this->contains( $uri, '?' ) ) {
			$uri .= '&' . $params;
		} else {
			$uri .= '?' . $params;
		}
		return $uri;
	}
	function arrayCopy( $fromArray, &$toArray, $options = array() ) {
		$defaults = array(
			'matchFields' => array(),
			'otherFields' => true,
			'key'         => '',
		);
		$options  = $this->parseArgs( $options, $defaults );

		foreach ( $fromArray as $i => $item ) {
			$new = array();
			foreach ( $options['matchFields'] as $k => $t ) {
				if ( is_bool( $k ) ) {
					$v = $i;
				} else {
					$v = $this->get( $item, $k, '' );
					unset( $item[ $k ] );
				}
				$new[ $t ] = $v;
			}

			if ( $options['otherFields'] ) {
				foreach ( $item as $k => $v ) {
					$new[ $k ] = $v;
				}
			}

			$k = $options['key'];
			if ( '' == $k ) {
				$toArray[] = $new;
			} else {
				$k = $this->get( $new, $k, '' );
				if ( '' != $k ) {
					$toArray[ $k ] = $new;
				}
			}
		}
		return $toArray;
	}
	public function formatTimer( $time ) {
		if ( ! is_int( $time ) ) {
			if ( is_string( $time ) ) {
				$time = str_replace( ' ', ':', $time );
				$time = str_replace( '.', ':', $time );
				$time = str_replace( '/', ':', $time );
				$time = explode( ':', $time );

				$length  = count( $time );
				$days    = 0;
				$hours   = 0;
				$minutes = 0;
				$secs    = intval( $time[ $length - 1 ] );

				if ( $length > 1 ) {
					$minutes = intval( $time[ $length - 2 ] );
					if ( $length > 2 ) {
						$hours = intval( $time[ $length - 3 ] );
						if ( $length > 3 ) {
							$days = intval( $time[ $length - 4 ] );
						}
					}
				}
				$time = $days * 86400 + $hours * 3600 + $minutes * 60 + $secs;
			} else {
				$time = 0;
			}
		} else {
			$time = intval( $time );
		}

		$secs    = $time % 60;
		$time    = ( $time - $secs ) / 60;
		$minutes = $time % 60;
		$time    = ( $time - $minutes ) / 60;
		$hours   = $time % 24;
		$days    = ( $time - $hours ) / 24;

		$result   = array();
		$result[] = $days;
		$result[] = ( $hours < 10 ? '0' : '' ) . $hours;
		$result[] = ( $minutes < 10 ? '0' : '' ) . $minutes;
		$result[] = ( $secs < 10 ? '0' : '' ) . $secs;
		$result   = implode( ':', $result );
		return $result;
	}
	public function parseTimer( $time ) {
		$time   = $this->formatTimer( $time );
		$time   = explode( ':', $time );
		$result = intval( $time[0] ) * 86400 + intval( $time[1] ) * 3600 + intval( $time[2] ) * 60 + intval( $time[3] );
		return $result;
	}
}
