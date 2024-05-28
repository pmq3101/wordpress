<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ICP_DaoUtils {
	var $data;
	var $tables;

	public function __construct() {
		$this->data   = array();
		$this->tables = array();
	}

	public function getDatabaseVersion() {
		ob_start();
		var_dump( $this->tables );
		$buffer = ob_get_clean();
		$buffer = md5( $buffer );
		return $buffer;
	}
	private function getColumnCreationSql( $table, $primary, $name, $column ) {
		global $icp;
		if ( ! isset( $column['type'] ) || '' == $column['type'] ) {
			return '';
		}

		$type = strtolower( $column['type'] );
		switch ( $type ) {
			case 'array':
				$type = 'text';
				//if(!isset($column['len'])) {
				//    $column['len']=100;
				//}
				break;
			case 'json':
				$type = 'longtext';
				unset( $column['len'] );
				break;
			case 'pointer':
				$type = 'int';
				break;
		}
		$type = strtoupper( $type );
		$sql  = $icp->Dao->encodeColumn( $table, $column['column'] ) . ' ' . $type;

		if ( isset( $column['len'] ) ) {
			$sql .= '(' . $column['len'] . ')';
		}
		if ( isset( $column['default'] ) && ! $icp->Utils->startsWith( $column['default'], '{' ) ) {
			$sql .= " DEFAULT '" . $column['default'] . "'";
		}
		if ( ( isset( $column['required'] ) && $column['required'] ) || ( isset( $column['primary'] ) && $column['primary'] ) ) {
			$sql .= ' NOT NULL';
		}
		if ( isset( $primary[ $name ] ) && 1 == count( $primary ) ) {
			$sql .= ' AUTO_INCREMENT';
		}
		return $sql;
	}

	function load( $prefix, $root ) {
		$h     = opendir( $root );
		$slash = substr( $root, strlen( $root ) - 1 );
		if ( '/' != $slash && '\\' != $slash ) {
			$root .= '/';
		}

		while ( $file = readdir( $h ) ) {
			if ( is_dir( $root . $file ) && '.' != $file && '..' != $file ) {
				$this->load( $prefix, $root . $file );
			} elseif ( strlen( $file ) > 5 ) {
				$ext    = '.php';
				$length = strlen( $ext );
				$start  = $length * -1; //negative
				if ( strcasecmp( substr( $file, $start ), $ext ) == 0 ) {
					$this->loadClass( $prefix, $root, $file );
				}
			}
		}
	}
	function loadClass( $prefix, $root, $file ) {
		global $wpdb,$icp;
		$source = $root . $file;
		if ( ! file_exists( $source ) ) {
			return false;
		}

		$result = false;
		$source = file_get_contents( $source );
		if ( null != $file && strlen( $source ) > 0 ) {
			$source = str_replace( '  ', ' ', $source );
			$source = str_replace( "\r\n", "\n", $source );
			$source = str_replace( "\n\n", "\n", $source );
			$source = explode( "\n", $source );

			$patternClass      = 'class ';
			$patternVar        = 'var $';
			$patternAnnotation = '//@';
			$annotations       = false;

			$data = array(
				'class'   => '',
				'fields'  => array(),
				'columns' => array(),
			);

			$exit = false;
			foreach ( $source as $row ) {
				$row = trim( $row );
				if ( $exit ) {
					break;
				}
				if ( '' == $row ) {
					continue;
				}

				$patterns = array( $patternClass, $patternVar, $patternAnnotation );
				foreach ( $patterns as $p ) {
					$pos = strpos( $row, $p );
					if ( false !== $pos ) {
						switch ( $p ) {
							case $patternClass:
								if ( is_array( $annotations ) !== false ) {
									$row           = substr( $row, $pos + strlen( $patternClass ) );
									$row           = explode( ' ', $row );
									$class         = $row[0];
									$data          = $icp->Utils->merge( true, $data, $annotations );
									$data['class'] = $class;
								} else {
									//class without @table annotation
									$exit = true;
								}
								$annotations = false;
								break;
							case $patternVar:
								if ( is_array( $annotations ) !== false ) {
									$row   = substr( $row, $pos + strlen( $patternVar ) );
									$row   = explode( ';', $row );
									$field = $row[0];
									$field = explode( '=', $field );
									$field = $field[0];
									if ( ! isset( $annotations['column'] ) ) {
										//TODO: camelCase to camel_case?
										$annotations['column'] = $field;
									}

									$v                       = $icp->Utils->get( $annotations, 'primary', false );
									$v                       = $icp->Utils->isTrue( $v );
									$annotations['primary']  = $v;
									$v                       = $icp->Utils->get( $annotations, 'required', false );
									$v                       = $icp->Utils->isTrue( $v );
									$annotations['required'] = $v;
									if ( ( ! isset( $annotations['type'] ) || '' == $annotations['type'] ) && $annotations['primary'] ) {
										$annotations['type'] = 'int';
									}
									$annotations['field'] = $field;

									$data['fields'][ $field ]                  = $annotations;
									$data['columns'][ $annotations['column'] ] = $annotations;
								}
								$annotations = false;
								break;
							case $patternAnnotation:
								//@annotaion1=value1 @annotation2=value2 @annotation3=value3
								$row = substr( $row, $pos + strlen( $patternAnnotation ) - 1 );
								$row = str_replace( '  ', ' ', $row );
								$row = explode( ' ', $row );

								if ( ! is_array( $annotations ) ) {
									$annotations = array();
								}
								foreach ( $row as $r ) {
									$r = explode( '=', $r );
									if ( 1 == count( $r ) ) {
										$defaultsTrue = array( '@ui-required', '@ui-readonly', '@ui-multiple', '@primary' );
										if ( in_array( $r[0], $defaultsTrue ) ) {
											//default true
											$r[] = true;
										} else {
											//default empty
											$r[] = '';
										}
									}
									if ( 2 == count( $r ) ) {
										$k = trim( $r[0] );
										$v = trim( $r[1] );
										//check if is an @annotation
										if ( substr( $k, 0, 1 ) == '@' ) {
											$k                 = substr( $k, 1 );
											$annotations[ $k ] = $v;
										}
									}
								}
								break;
							default:
								$annotations = false;
								break;
						}
						break;
					}
				}
			}

			if ( isset( $data['class'] ) && '' != $data['class'] ) {
				if ( isset( $data['table'] ) && '' != $data['table'] ) {
					$this->data[ $data['table'] ] = $data;
					if ( '' != $prefix ) {
						$data['table']                = $wpdb->prefix . $prefix . $data['table'];
						$this->data[ $data['table'] ] = $data;
					}
					$this->tables[ $data['class'] ] = $data;
				}

				$class                = $data['class'];
				$this->data[ $class ] = $data;
				$class                = str_replace( ICP_PLUGIN_PREFIX, '', $class );
				$this->data[ $class ] = $data;
				$result               = true;
			}
		}
		return $result;
	}
	public function getTableClass( $class ) {
		$table  = $this->getTable( $class );
		$result = '';
		if ( false !== $table && isset( $table['class'] ) ) {
			$result = $table['class'];
			if ( ! class_exists( $result ) ) {
				$result = '';
			}
		}
		return $result;
	}
	public function getClass( $class ) {
		if ( is_array( $class ) ) {
			if ( 0 == count( $class ) ) {
				$class = '';
			} else {
				foreach ( $class as $k => $v ) {
					$class = $v;
					break;
				}
				if ( is_array( $class ) ) {
					$class = '';
				}
			}
		}
		if ( '' != $class ) {
			if ( is_object( $class ) ) {
				$class = get_class( $class );
			} elseif ( ! class_exists( $class ) ) {
				$class = ICP_PLUGIN_PREFIX . $class;
				if ( ! class_exists( $class ) ) {
					$class = '';
				}
			}
		}
		return $class;
	}
	public function getTableName( $class ) {
		global $icp;
		$key    = array( 'DaoUtils.getTableName', $class );
		$result = $icp->Options->getCache( $key );
		if ( false === $result ) {
			$table  = $this->getTable( $class );
			$result = '';
			if ( false !== $table ) {
				$result = strtolower( $table['table'] );
			}
			$icp->Options->setCache( $key, $result );
		}
		return $result;
	}
	public function getTable( $class ) {
		global $icp;
		$key    = array( 'DaoUtils.getTable', $class );
		$result = $icp->Options->getCache( $key );
		if ( false === $result ) {
			if ( is_object( $class ) ) {
				$class = get_class( $class );
			}
			$classes   = array();
			$classes[] = $class;
			if ( strpos( $class, ICP_PLUGIN_PREFIX ) !== false ) {
				$class     = str_replace( ICP_PLUGIN_PREFIX, '', $class );
				$classes[] = $class;
			}
			if ( strpos( $class, 'Search' ) !== false ) {
				$class     = str_replace( 'Search', '', $class );
				$classes[] = $class;
			}

			$result = false;
			foreach ( $classes as $class ) {
				if ( isset( $this->data[ $class ] ) ) {
					$v = $this->data[ $class ];
					if ( isset( $v['table'] ) && '' != $v['table'] ) {
						$result = $v;
						break;
					} elseif ( isset( $v['tableClass'] ) && '' != $v['tableClass'] ) {
						$v = $v['tableClass'];
						if ( isset( $v['table'] ) && '' != $v['table'] ) {
							$result = $v;
							break;
						}
					}
				}
			}
			$icp->Options->setCache( $key, $result );
		}
		return $result;
	}
	public function getId( $instance ) {
		$primary = $this->getPrimary( $instance );
		$result  = -1;
		if ( isset( $instance->$primary ) ) {
			$result = $instance->$primary;
			$result = intval( $result );
		}
		return $result;
	}
	public function getPrimary( $class ) {
		global $icp;
		$key    = array( 'DaoUtils.getPrimary', $class );
		$result = $icp->Options->getCache( $key );
		if ( false === $result ) {
			$columns = $this->getPrimaryColumns( $class );
			$result  = '';
			foreach ( $columns as $k => $v ) {
				$result = $k;
				break;
			}
			$icp->Options->setCache( $key, $result );
		}
		return $result;
	}
	public function getPointersColumns( $class ) {
		global $icp;
		if ( is_object( $class ) ) {
			$class = get_class( $class );
		}
		$class = str_replace( ICP_PLUGIN_PREFIX, '', $class );
		$class = str_replace( 'Search', '', $class );

		$key    = array( 'DaoUtils.getPointersColumns', $class );
		$result = $icp->Options->getCache( $key );
		if ( false === $result ) {
			$options = array(
				'includeUiTypes' => 'pointer',
				'includeNested'  => false,
			);
			$result  = $this->getColumns( $class, $options );
			$icp->Options->setCache( $key, $result );
		}
		return $result;
	}
	public function getAllColumns( $class ) {
		global $icp;
		$key    = array( 'DaoUtils.getAllColumns', $class );
		$result = $icp->Options->getCache( $key );
		if ( false === $result ) {
			$options = array(
				'includeUiTypes'     => false,
				'excludeUiTypes'     => false,
				'includeNested'      => true,
				'includeNestedAlias' => true,
			);
			$result  = $this->getColumns( $class, $options );
			$icp->Options->setCache( $key, $result );
		}
		return $result;
	}
	public function getColumnsNames( $class, $options = array() ) {
		$columns = $this->getColumns( $class, $options );
		$result  = array_keys( $columns );
		return $result;
	}
	public function getColumns( $class, $options = array() ) {
		global $icp;

		$defaults = array(
			'includeUiTypes'     => false,
			'excludeUiTypes'     => false,
			'includeNested'      => false,
			'includeNestedAlias' => true,
		);
		$options  = $icp->Utils->parseArgs( $options, $defaults );

		//we cannot do this due to $class could be a search class and not a table class
		//$class=$this->getTable($class);
		$result = array();
		if ( is_array( $class ) ) {
			foreach ( $class as $c ) {
				$class = $c;
				break;
			}
		}
		if ( is_object( $class ) ) {
			$class = get_class( $class );
		}
		if ( isset( $this->data[ $class ] ) ) {
			$v = $this->data[ $class ];
			if ( isset( $v['fields'] ) ) {
				$result = $v['fields'];
			}
		}

		if ( strpos( $class, 'Search' ) !== false ) {
			$domainClass = str_replace( 'Search', '', $class );
			if ( isset( $this->data[ $domainClass ] ) ) {
				$defaults = $this->data[ $domainClass ];
				$defaults = $defaults['fields'];
				//the name of the field in ..Search class is not the same
				//we use pattern suffix to determe the query type (Equals, Like, etc)
				$patterns = $icp->Dao->getSearchPatterns();
				foreach ( $result as $name => $value ) {
					foreach ( $patterns as $p ) {
						if ( '' == $p || $icp->Utils->endsWith( $name, $p ) ) {
							$k = $name;
							if ( strlen( $p ) > 0 ) {
								$k = substr( $name, 0, strlen( $name ) - strlen( $p ) );
							}
							if ( isset( $defaults[ $k ] ) ) {
								//no default value will be copied
								$array = $defaults[ $k ];
								unset( $array['default'] );
								$value = $icp->Utils->parseArgs( $value, $array );
								switch ( strtolower( $p ) ) {
									case 's':
									case 'Ids':
										//could happens that the original  type is int but for ...
										//Search class we need to search more that one value su use the array type instead
										//mmmh...why this??
										//$value['type']='array';
										break;
								}
								$result[ $name ] = $value;
								break;
							}
						}
					}
				}
			}
		} else {
			$all = $result;
			foreach ( $result as $k => $v ) {
				if ( isset( $v['ui-type'] ) && 'pointer' == $v['ui-type'] ) {
					if ( strtolower( $k ) != 'id' && $icp->Utils->endsWith( $k, 'Id' ) ) {
						$n             = substr( $k, 0, strlen( $k ) - 2 );
						$v['instance'] = $n;
						$all[ $k ]     = $v;

						if ( $options['includeNested'] ) {
							$k       = $n;
							$class   = $this->getClass( $v['rel'] );
							$columns = $this->getColumns( $class, $options );
							if ( false !== $columns && count( $columns ) > 0 ) {
								$kk    = $icp->Utils->lowerUnderscoreCase( $k );
								$kk    = explode( '_', $kk );
								$alias = '';
								foreach ( $kk as $t ) {
									$alias .= substr( $t, 0, 1 );
								}
								foreach ( $columns as $n => $c ) {
									$all[ $k . '.' . $n ] = $c;

									if ( $options['includeNestedAlias'] ) {
										$cc                       = $c;
										$cc['alias']              = $k . '.' . $n;
										$all[ $alias . '.' . $n ] = $cc;
									}
								}
							}
						}
					}
				}
			}
			$result = $all;
		}

		$includes = $icp->Utils->toArray( $options['includeUiTypes'] );
		$excludes = $icp->Utils->toArray( $options['excludeUiTypes'] );
		if ( count( $includes ) > 0 || count( $excludes ) > 0 ) {
			foreach ( $result as $k => $v ) {
				$include = ( isset( $v['ui-type'] ) && '' != $v['ui-type'] );
				if ( $include && ! $icp->Utils->inArray( $v['ui-type'], $includes ) ) {
					$include = false;
				}
				if ( $include && $icp->Utils->inArray( $v['ui-type'], $excludes ) ) {
					$include = false;
				}

				if ( ! $include ) {
					unset( $result[ $k ] );
				}
			}
		}

		if ( is_array( $result ) ) {
			ksort( $result );
		} else {
			$icp->Log->error( 'NO COLUMNS FOUND FOR CLASS=[%s]', $class );
		}

		return $result;
	}
	public function getColumn( $class, $name ) {
		global $icp;
		$class = $this->getClass( $class );
		if ( '' == $class ) {
			return array();
		}
		$key    = array( 'DaoUtils.getColumn', $class, $name );
		$result = $icp->Options->getCache( $key );
		if ( false === $result ) {
			$columns = $this->getAllColumns( $class );
			$result  = false;
			if ( isset( $columns[ $name ] ) ) {
				$result = $columns[ $name ];
			}
			$icp->Options->setCache( $key, $result );
		}
		return $result;
	}
	public function getPrimaryColumns( $class ) {
		global $icp;
		$key    = array( 'DaoUtils.getPrimaryColumns', $class );
		$result = $icp->Options->getCache( $key );
		if ( false === $result ) {
			$result  = array();
			$options = array( 'includeNested' => false );
			$columns = $this->getColumns( $class, $options );
			foreach ( $columns as $k => $v ) {
				if ( isset( $v['primary'] ) && $v['primary'] ) {
					$result[ $k ] = $v;
				}
			}
			$icp->Options->setCache( $key, $result );
		}
		return $result;
	}
	public function getColumnsDefaults( $class ) {
		global $icp;
		$key    = array( 'DaoUtils.getColumnsDefaults', $class );
		$result = $icp->Options->getCache( $key );
		if ( false === $result ) {
			$result  = array();
			$options = array( 'includeNested' => false );
			$fields  = $this->getColumns( $class, $options );
			foreach ( $fields as $k => $v ) {
				if ( isset( $v['default'] ) && '' !== $v['default'] ) {
					$v = $v['default'];
					if ( ! $icp->Utils->startsWith( $v, '{' ) ) {
						$result[ $k ] = $v;
					}
					/*if($v=='currentCallCenterId') {
						$u=$ec->Session->getUser();
						$v=$u->id;
						if($v>0 && $u->userRight==ICP_UserConstants::USER_RIGHT_CALL_CENTER) {
							$result[$k]=$v;
						}
					} elseif($v=='currentAgentId') {
						$u=$ec->Session->getUser();
						$v=$u->id;
						if($v>0 && ($u->userRight==ICP_UserConstants::USER_RIGHT_AGENT || $u->userRight==ICP_UserConstants::USER_RIGHT_AGENT_MANAGER)) {
							$result[$k]=$v;
						}
					} elseif($v=='currentUserId') {
						$v=$ec->Session->getUserId();
						if($v>0) {
							$result[$k]=$v;
						}
					}*/
				}
			}
			$icp->Options->setCache( $key, $result );
		}
		return $result;
	}
	public function getColumnsFormats( $class ) {
		global $icp;
		$key    = array( 'DaoUtils.getColumnsFormats', $class );
		$result = $icp->Options->getCache( $key );
		if ( false === $result ) {
			$result  = array();
			$options = array( 'includeNested' => false );
			$fields  = $this->getColumns( $class, $options );
			foreach ( $fields as $k => $v ) {
				if ( isset( $v['type'] ) && '' != $v['type'] ) {
					$result[ $k ] = '%s';
					switch ( strtolower( $v['type'] ) ) {
						case 'int':
						case 'long':
							$result[ $k ] = '%d';
							break;
						case 'float':
						case 'double':
						case 'numeric':
							$result[ $k ] = '%f';
							break;
						case 'date':
						case 'datetime':
						case 'time':
							$result[ $k ] = '%s';
							break;
					}
				}
			}
			$icp->Options->setCache( $key, $result );
		}
		return $result;
	}
	//create class from $_POST/$_GET variables...so cool! :)
	function qs( $class, $prefix = '' ) {
		global $icp;

		$result   = false;
		$instance = $this->newDomainClass( $class );
		$columns  = $this->getAllColumns( $class );
		if ( false !== $instance ) {
			$match = false;
			foreach ( $columns as $name => $column ) {
				if ( isset( $column['ui-type'] ) && '' != $column['ui-type'] ) {
					$k          = $prefix . str_replace( '_', '.', $name );
					$v          = $icp->Utils->qs( $k, false );
					$allowEmpty = false;
					switch ( $column['ui-type'] ) {
						case 'toggle':
						case 'dropdown':
						case 'tags':
						case 'check':
						case 'checklist':
						case 'radio':
						case 'radiolist':
							$allowEmpty = true;
							break;
					}

					if ( false !== $v || $allowEmpty ) {
						$value = $this->decode( $class, $name, $v );
						if ( $icp->Utils->set( $instance, $name, $value ) ) {
							if ( '' !== $value || ! $allowEmpty ) {
								$match = true;
							}
						}
					}
				}
			}
			if ( $match ) {
				$result = $instance;
			}
		}

		if ( is_object( $class ) ) {
			$class = get_class( $class );
			$class = str_replace( ICP_PLUGIN_PREFIX, '', $class );
		}
		return $result;
	}
	//decode data from database to class
	public function decode( $class, $name, $value ) {
		global $icp;
		if ( 0 !== $value && is_null( $value ) ) {
			return $value;
		}

		$name   = str_replace( ICP_PLUGIN_PREFIX, '', $name );
		$name   = str_replace( '_', '.', $name );
		$column = $this->getColumn( $class, $name );
		if ( $column && isset( $column['type'] ) ) {
			switch ( strtolower( $column['type'] ) ) {
				case 'bool':
					$value = $icp->Utils->isTrue( $value );
					break;
				case 'int':
				case 'long':
				case 'float':
				case 'double':
				case 'numeric':
					if ( is_numeric( $value ) ) {
						$value = $icp->Utils->parseNumber( $value );
					} else {
						if ( isset( $column['ui-type'] ) && 'toggle' == $column['ui-type'] ) {
							$value = $icp->Utils->parseNumber( $value );
						} else {
							$value = '';
						}
					}
					break;
				case 'datetime':
				case 'date':
				case 'time':
					$value = $icp->Utils->parseDateToTime( $value );
					break;
				case 'array':
					$value = $icp->Utils->dbarray( $value, false );
					break;
				case 'json':
					$value = json_decode( $value, true );
					break;
				default:
					if ( is_array( $value ) ) {
						if ( isset( $value['name'] ) ) {
							$value = $value['name'];
						} else {
							$value = '';
						}
					} else {
						$value = trim( $value );
						//$value=str_replace("\\'", "'", $value);
						$value = stripslashes( $value );
					}
					break;
			}
			if ( isset( $column['ui-type'] ) ) {
				switch ( $column['ui-type'] ) {
					case 'toggle':
						if ( is_null( $value ) || '' === $value ) {
							$value = 0;
						}
						break;
					case 'timer':
						if ( '' != $value ) {
							$value = $icp->Utils->parseTimer( $value );
							$value = $icp->Utils->formatTimer( $value );
						}
						break;
				}
			}
		}
		return $value;
	}
	public function isColumnDate( $class, $name ) {
		$result = false;
		$column = $this->getColumn( $class, $name );
		if ( $column && isset( $column['type'] ) ) {
			switch ( strtolower( $column['type'] ) ) {
				case 'datetime':
				case 'date':
				case 'time':
					$result = true;
					break;
			}
		}
		return $result;
	}
	public function isColumnText( $class, $name ) {
		global $icp;
		$result = false;
		$column = $this->getColumn( $class, $name );
		if ( $column && isset( $column['type'] ) ) {
			if ( $icp->Utils->contains( $column['type'], 'text' ) || $icp->Utils->contains( $column['type'], 'char' ) ) {
				$result = true;
			}
		}
		return $result;
	}
	public function isColumnArray( $class, $name ) {
		$result = false;
		$column = $this->getColumn( $class, $name );
		if ( $column && isset( $column['type'] ) ) {
			switch ( strtolower( $column['type'] ) ) {
				case 'array':
					$result = true;
					break;
			}
		}
		return $result;
	}
	public function isColumnNumeric( $class, $name ) {
		$result = false;
		$column = $this->getColumn( $class, $name );
		if ( $column && isset( $column['type'] ) ) {
			switch ( strtolower( $column['type'] ) ) {
				case 'int':
				case 'long':
				case 'float':
				case 'double':
				case 'numeric':
					$result = true;
					break;
			}
		}
		return $result;
	}
	public function encodeQuote( $class, $name, $value ) {
		return $this->encode( $class, $name, $value, true );
	}
	//encode data from class to database including quote if needed
	public function encode( $class, $name, $value, $quote ) {
		global $icp;

		$requireQuote = true;
		$column       = $this->getColumn( $class, $name );
		if ( $column ) {
			switch ( strtolower( $column['type'] ) ) {
				case 'bool':
					$value = ( $icp->Utils->isTrue( $value ) ? 1 : 0 );
					break;
				case 'int':
				case 'long':
				case 'float':
				case 'double':
				case 'numeric':
					$value        = '' . $icp->Utils->parseNumber( $value );
					$value        = str_replace( ',', '.', $value );
					$requireQuote = false;
					break;
				case 'datetime':
					$value = $icp->Utils->formatSqlDatetime( $value );
					break;
				case 'date':
					$value = $icp->Utils->formatSqlDate( $value );
					break;
				case 'time':
					$value = $icp->Utils->formatSqlTime( $value );
					break;
				case 'array':
					$array = $icp->Utils->dbarray( $value, false );
					$value = '';
					foreach ( $array as $v ) {
						$value .= ',' . $v . ',';
					}
					break;
				case 'json':
					if ( is_array( $value ) || is_object( $value ) ) {
						$value = json_encode( $value );
					}
					break;
				default:
					if ( is_array( $value ) ) {
						$value = implode( '|', $value );
					} elseif ( is_object( $value ) ) {
						throw new Exception( 'VALUE OF CLASS=' . get_class( $value ) . ' CANNOT BE PASSED IN ENCODE' );
					} else {
						$value = trim( $value );
					}
					//$value=str_replace('"', '""', $value);
					break;
			}
		}
		if ( $requireQuote && $quote ) {
			//$value=str_replace('\\', '\\\\"', $value);
			//$value=str_replace('"', '\\"', $value);
			//$value=str_replace('"', '""', $value);
			$value = addslashes( $value );
			$value = '"' . $value . '"';
		}
		return $value;
	}
	public function newInnerClass( $domainClass, $property, $innerClass ) {
		global $icp;
		$result = $this->newDomainClass( $innerClass );
		$column = $icp->Dao->Utils->getColumn( $domainClass, $property );
		if ( isset( $column['defaults'] ) && '' != $column['defaults'] ) {
			$defaults = $icp->Utils->toArray( $column['defaults'] );
			foreach ( $defaults as $v ) {
				$v = explode( ':', $v );
				if ( count( $v ) > 1 ) {
					$k          = $v[0];
					$v          = $v[1];
					$v          = $icp->Utils->getConstant( $innerClass, $k, $v );
					$result->$k = $icp->Dao->Utils->decode( $innerClass, $k, $v );
				}
			}
		}
		return $result;
	}
	public function newDomainClass( $class ) {
		global $icp;

		$result = $class;
		if ( is_string( $class ) ) {
			$class  = $this->getClass( $class );
			$result = new $class();

		} elseif ( ! is_object( $class ) ) {
			return false;
		}

		$columns = $icp->Dao->Utils->getAllColumns( $result );
		foreach ( $columns as $k => $v ) {
			if ( isset( $v['default'] ) && '' != $v['default'] ) {
				$default = $v['default'];
				$default = str_replace( '{url}', ICP_BLOG_URL, $default );
				$default = str_replace( '{domain}', $icp->Utils->trimHttp( ICP_BLOG_URL ), $default );
				$default = str_replace( '{email}', ICP_BLOG_EMAIL, $default );
				$default = $icp->Dao->Utils->decode( $result, $k, $default );
				$icp->Utils->set( $result, $k, $default );
			}

			if ( isset( $v['ui-type'] ) ) {
				if ( 'pointer' == $v['ui-type'] ) {
					$innerClass = $icp->Dao->Utils->getClass( $v['rel'] );
					if ( '' != $v['instance'] ) {
						$k = $v['instance'];
						$v = $this->newInnerClass( $result, $k, $innerClass );
						$icp->Utils->set( $result, $k, $v );
					}
				} elseif ( 'toggle' == $v['ui-type'] ) {
					$t = $icp->Utils->get( $result, $k );
					if ( is_null( $t ) ) {
						$icp->Utils->set( $result, $k, 0 );
					}
				}
			}
		}
		return $result;
	}
	public function getUiFields( $instance, $prefix = '', $all = true ) {
		$result  = array();
		$columns = $this->getAllColumns( $instance );
		foreach ( $columns as $k => $v ) {
			if ( ! isset( $v['ui-type'] ) || '' == $v['ui-type'] || 'pointer' == $v['ui-type'] ) {
				continue;
			}
			if ( isset( $v['alias'] ) && '' != $v['alias'] ) {
				continue;
			}

			$k = $prefix . $k;
			$k = str_replace( '.', '_', $k );
			if ( $all || ! is_null( $v ) ) {
				$result[ $k ] = $v;
			}
		}
		return $result;
	}
	public function isColumnVisible( $instance, $name ) {
		global $icp;
		$column = $this->getColumn( $instance, $name );
		$result = true;
		if ( isset( $column['ui-visible'] ) && '' != $column['ui-visible'] ) {
			$visible    = $column['ui-visible'];
			$conditions = explode( '&', $visible );
			$all        = true;
			foreach ( $conditions as $c ) {
				$options = explode( ':', $c );
				$k       = $options[0];
				$v       = $icp->Utils->get( $instance, $k, '' );
				$options = $options[1];
				$options = $icp->Utils->toArray( $options );
				if ( ! in_array( $v, $options ) ) {
					$all = false;
					break;
				}
			}

			if ( ! $all ) {
				$result = false;
			}
		}
		return $result;
	}
}
