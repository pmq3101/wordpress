<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ICP_Dao {
	var $Utils;

	public function __construct() {
		$this->Utils = new ICP_DaoUtils();
	}

	//object relational map
	public function orms( $class, $values, $options = array() ) {
		if ( false === $values || is_null( $values ) ) {
			return array();
		}
		$result = array();
		foreach ( $values as $v ) {
			$v = $this->orm( $class, $v, $options );
			if ( false !== $v ) {
				$result[] = $v;
			}
		}
		return $result;
	}
	//object relational map
	public function orm( $class, $value, $options = array() ) {
		global $icp;
		if ( false === $value || is_null( $value ) ) {
			return false;
		}

		$tableClass = $icp->Dao->Utils->getTableClass( $class );
		if ( '' == $tableClass ) {
			throw new Exception( 'NO TABLE CLASS FOUND FOR CLASS=' . $class );
		}

		//database iscase-insensitive
		$columns = $icp->Dao->Utils->getColumns( $tableClass );
		foreach ( $columns as $k => $v ) {
			$k             = strtolower( $k );
			$columns[ $k ] = $v;
		}

		$result = new $tableClass();
		foreach ( $value as $k => $v ) {
			if ( isset( $columns[ $k ] ) ) {
				$c          = $columns[ $k ];
				$k          = $c['field'];
				$result->$k = $icp->Dao->Utils->decode( $tableClass, $k, $v );
			}
		}
		if ( isset( $options['loadPointers'] ) && $options['loadPointers'] ) {
			$columns = $icp->Dao->Utils->getPointersColumns( $class );
			foreach ( $columns as $k => $v ) {
				$class = $icp->Dao->Utils->getClass( $v['rel'] );
				$id    = $result->$k;
				if ( '' !== $id && intval( $id ) > 0 ) {
					$instance = $this->queryForId( $class, $id );
					if ( false !== $instance ) {
						$property          = $v['instance'];
						$result->$property = $instance;
					}
				}
			}
		}
		return $result;
	}

	public function queryForId( $class, $id ) {
		global $wpdb, $icp;
		$table   = $icp->Dao->Utils->getTableName( $class );
		$primary = $icp->Dao->Utils->getPrimary( $class );

		$result = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM %s WHERE %s = %s LIMIT 1;', $table, $primary, $id ) );

		$options = array( 'loadPointers' => true );
		$result  = $this->orm( $class, $result, $options );
		return $result;
	}
	public function queryForIds( $search, $id = 'id' ) {
		$array  = $this->queryForList( $search );
		$result = array();
		foreach ( $array as $v ) {
			$result[] = $v->$id;
		}
		return $result;
	}
	public function queryForMap( $search, $options = array() ) {
		global $wpdb, $icp;
		$array   = $this->queryForList( $search, $options );
		$table   = $icp->Dao->Utils->getTableName( $search );
		$primary = $icp->Dao->Utils->getPrimary( $table );

		$result = array();
		foreach ( $array as $v ) {
			$k            = $v->$primary;
			$result[ $k ] = $v;
		}
		return $result;
	}
	private function executeIntQuery( $sql, $default = false ) {
		global $wpdb;

		$data   = $wpdb->get_row( $wpdb->prepare( '%s', $sql ) );
		$result = $default;
		if ( false !== $data ) {
			//associative array stdClass
			foreach ( $data as $k => $v ) {
				$result = intval( $v );
				break;
			}
		}
		return $result;
	}
	public function queryForCount( $search, $options = array() ) {
		if ( is_array( $options ) ) {
			$options = array();
		}
		$options['count'] = true;
		$sql              = $this->getQuerySql( $search, $options );
		$result           = $this->executeIntQuery( $sql, 0 );
		return $result;
	}
	public function encodeColumn( $class, $column, $alias = '' ) {
		return '`' . strtolower( $column ) . '`';
	}
	public function getSearchPatterns() {
		$patterns = array( 'Equals', 'Like', 'Ids', 'From', 'To', 's', '' );
		return $patterns;
	}
	protected function getQuerySql( $search, $options = array() ) {
		global $icp;
		if ( is_string( $options ) ) {
			$options = array(
				'queryClass'  => $options,
				'resultClass' => $options,
			);
		}
		$defaults   = array(
			'queryClass'  => $search,
			'resultClass' => $search,
			'count'       => false,
			'distinct'    => false,
			'columns'     => '',
		);
		$options    = $icp->Utils->parseArgs( $options, $defaults );
		$queryClass = $options['queryClass'];

		$table = $icp->Dao->Utils->getTableName( $queryClass );
		if ( '' == $table ) {
			throw new Exception( 'TABLE NOT FOUND FOR CLASS=' . $queryClass );
		}

		$where    = array();
		$columns  = $icp->Dao->Utils->getColumns( $queryClass );
		$patterns = $this->getSearchPatterns();
		foreach ( $columns as $name => $column ) {
			if ( ! isset( $column['type'] ) || '' == $column['type'] ) {
				continue;
			}

			foreach ( $patterns as $pattern ) {
				$property = $name . $pattern;
				if ( isset( $search->$property ) && ! is_null( $search->$property ) ) {
					$value = $search->$property;
					$value = $icp->Utils->trim( $value );
					//if($value==='' || is_null($value)) {
					if ( is_null( $value ) ) {
						continue;
					}

					$operator = '';
					switch ( $pattern ) {
						case 'Equals':
							$value    = $icp->Dao->Utils->encodeQuote( $queryClass, $name, $value );
							$operator = '=';
							break;
						case 'Like':
							$value = trim( $value );
							while ( strpos( $value, '  ' ) !== false ) {
								$value = str_replace( '  ', ' ', $value );
							}
							if ( '' != $value ) {
								$value    = '%' . str_replace( ' ', '%', $value ) . '%';
								$value    = $icp->Dao->Utils->encodeQuote( $queryClass, $name, $value );
								$operator = 'LIKE';
							}
							break;
						case 'Ids':
						case 's':
						case '':
							if ( '' == $pattern && ! $icp->Utils->endsWith( $name, 'Ids' ) ) {
								$value    = $icp->Dao->Utils->encodeQuote( $queryClass, $name, $value );
								$operator = '=';
							} else {
								if ( $icp->Dao->Utils->isColumnNumeric( $queryClass, $name ) ) {
									$value = $icp->Utils->iuarray( $value, false );
									if ( count( $value ) > 0 ) {
										$value    = '(' . implode( ',', $value ) . ')';
										$operator = 'IN';
									}
								} elseif ( $icp->Dao->Utils->isColumnArray( $queryClass, $name ) ) {
									switch ( strtolower( $column['type'] ) ) {
										case 'array':
											$value = $icp->Utils->dbarray( $value, false );
											$array = array();
											foreach ( $value as $v ) {
												$array[] = $this->encodeColumn( $queryClass, $name ) . ' LIKE "%,' . $v . ',%"';
											}
											if ( count( $patterns ) > 0 ) {
												$where[] = $array;
											}
											$operator = '';
											break;
										/*
										 * //UNUSED
										case 'iuarray':
											$value=$ec->Utils->iuarray($value, FALSE);
											if(count($value)>0) {
												$value=$ec->Dao->Utils->encodeQuote($table, $name, $value);
												$value=str_replace(',,', ',%,', $value);
												$operator="LIKE";
											}
											break;
										*/
									}
								}
							}
							break;
						case 'From':
							if ( 0 !== $value ) {
								$value    = $icp->Dao->Utils->encodeQuote( $queryClass, $name, $value );
								$operator = '>=';
							}
							break;
						case 'To':
							if ( 0 !== $value ) {
								$value    = $icp->Dao->Utils->encodeQuote( $queryClass, $name, $value );
								$operator = '<=';
							}
							break;
					}

					if ( '' != $operator ) {
						$annotation = $icp->Dao->Utils->getColumn( $search, $property );
						if ( isset( $annotation['query'] ) && '' != $annotation['query'] ) {
							$array = $icp->Utils->toArray( $annotation['query'] );
						} else {
							$array = array( $name );
						}
						if ( count( $array ) > 0 ) {
							if ( 1 == count( $array ) ) {
								$where[] = $this->encodeColumn( $queryClass, $array[0] ) . ' ' . $operator . ' ' . $value;
							} else {
								$clause = array();
								foreach ( $array as $v ) {
									$clause[] = $this->encodeColumn( $queryClass, $v ) . ' ' . $operator . ' ' . $value;
								}
								$where[] = $clause;
							}
						}
					}
				}
			}
		}

		$select = '*';
		if ( $options['count'] ) {
			$select = 'COUNT(*)';
		} elseif ( '' != $options['columns'] ) {
			$select = $options['columns'];
		}

		if ( $options['distinct'] ) {
			$select = 'DISTINCT ' . $select;
		}
		$sql = 'SELECT ' . $select . ' FROM ' . $table . ' ';
		if ( count( $where ) > 0 ) {
			$buffer = '';
			foreach ( $where as $clause ) {
				if ( '' != $buffer ) {
					$buffer .= " \n AND ";
				}
				if ( is_array( $clause ) ) {
					$clause = $icp->Utils->implode( ' ( ', ' ) ', "OR \n", $clause );
					if ( '' != $clause ) {
						$buffer .= " ( \n " . $clause . " \n ) ";
					}
				} elseif ( is_string( $clause ) ) {
					if ( '' != $clause ) {
						$buffer .= ' ( ' . $clause . ' ) ';
					}
				}
			}

			if ( '' != $buffer ) {
				$sql .= "\n WHERE " . $buffer;
			}
		}

		$orderBy = $icp->Utils->get( $search, 'orderBy', '' );
		if ( '' != $orderBy && 'orderBy' != $orderBy ) {
			$array = $icp->Dao->Utils->getColumn( $search, 'orderBy' );
			if ( isset( $array['query'] ) && '' != $array['query'] ) {
				$array = $icp->Utils->toArray( $array['query'] );
				if ( is_numeric( $orderBy ) ) {
					$orderBy = intval( $orderBy );
					if ( isset( $array[ $orderBy ] ) ) {
						$orderBy = $array[ $orderBy ];
					} else {
						$orderBy = '';
					}
				}
			}

			$buffer  = '';
			$orderBy = explode( ',', $orderBy );
			foreach ( $orderBy as $clause ) {
				$clause = str_replace( ' ', '#', $clause );
				$clause = explode( '#', $clause );
				if ( 1 == count( $clause ) ) {
					$clause[] = 'ASC';
				}
				if ( '' != $buffer ) {
					$buffer .= " \n , ";
				}
				$buffer .= $this->encodeColumn( $table, $clause[0] ) . ' ' . $clause[1];
			}
			if ( '' != $buffer ) {
				$sql .= "\n ORDER BY " . $buffer;
			}
		}

		$limit = $icp->Utils->iget( $search, 'limit', 0 );
		if ( $limit > 0 ) {
			$sql   .= "\n LIMIT " . $limit;
			$offset = $icp->Utils->iget( $search, 'offset', 0 );
			if ( $offset > 0 ) {
				$sql .= ',' . $offset;
			}
		}
		$icp->Log->debug( 'QUERY: %s', $sql );
		return $sql;
	}
	public function queryForNew( $instance ) {
		if ( ! is_object( $instance ) ) {
			return false;
		}

		$result = $this->queryForFirst( $instance );
		if ( false === $result ) {
			$instance->id = 0;
			$result       = $instance;
		}
		return $result;
	}
	public function queryForFirst( $search, $options = array() ) {
		$search->limit = 1;
		if ( ! is_array( $options ) ) {
			$options = array();
		}
		$options['loadPointers'] = true;
		$search->limit           = 1;
		$array                   = $this->queryForList( $search, $options );
		$result                  = false;
		if ( false !== $array && count( $array ) > 0 ) {
			$result = $array[0];
		}
		return $result;
	}
	public function queryForList( $search, $options = array() ) {
		global $wpdb, $icp;
		if ( is_string( $options ) ) {
			$options = array(
				'queryClass'  => $options,
				'resultClass' => $options,
			);
		}
		$defaults    = array(
			'queryClass'  => $search,
			'resultClass' => $search,
		);
		$options     = $icp->Utils->parseArgs( $options, $defaults );
		$resultClass = $options['resultClass'];

		if ( ! is_string( $search ) || ! $icp->Utils->startsWith( $search, 'SELECT ' ) ) {
			$sql = $this->getQuerySql( $search, $options );
		} else {
			$sql = $search;
		}

		$result = $wpdb->get_results( $wpdb->prepare( '%s', $sql ) );
		$result = $this->orms( $resultClass, $result, $options );
		return $result;
	}

	private function prepare( $class, $instance, &$output ) {
		global $icp;

		if ( '' == $class ) {
			$class = get_class( $instance );
		}
		$table    = $icp->Dao->Utils->getTableName( $class );
		$defaults = $icp->Dao->Utils->getColumnsDefaults( $class );
		$formats  = $icp->Dao->Utils->getColumnsFormats( $class );
		//transform instance to array
		$array = $icp->Utils->parseArgs( $instance, $defaults );

		$columns  = $icp->Dao->Utils->getColumns( $instance );
		$instance = array();
		foreach ( $columns as $k => $v ) {
			if ( isset( $v['type'] ) && '' != $v['type'] ) {
				if ( ! isset( $v['primary'] ) || ! $v['primary'] ) {
					$v = $array[ $k ];
					if ( is_null( $v ) ) {
						//unset($instance[$k]);
					} elseif ( $icp->Dao->Utils->isColumnNumeric( $class, $k ) && '' === $v ) {
						//unset($instance[$k]);
					} else {
						$instance[ $k ] = $icp->Dao->Utils->encodeQuote( $class, $k, $v, true );
					}
				}
			}
		}
		// Force fields to lower case
		$instance = array_change_key_case( $instance );
		$formats  = array_change_key_case( $formats );
		// White list columns
		$instance = array_intersect_key( $instance, $formats );

		// Reorder $column_formats to match the order of columns given in $data
		$array = array();
		foreach ( $instance as $k => $v ) {
			$format = '%s';
			if ( isset( $formats[ $k ] ) ) {
				$format = $formats[ $k ];
			}
			$array[] = $format;
		}
		$formats = $array;

		$output['table']    = $table;
		$output['formats']  = $formats;
		$output['defaults'] = $defaults;
		$output['instance'] = $instance;
		return true;
	}

	public function delete( $class, $ids = 0 ) {
		global $wpdb, $icp;
		$table   = $icp->Dao->Utils->getTableName( $class );
		$primary = $icp->Dao->Utils->getPrimary( $class );

		$ids = $icp->Utils->iarray( $ids );
		if ( 0 == count( $ids ) ) {
			return false;
		}

		$result = $wpdb->query( $wpdb->prepare( 'DELETE FROM %s WHERE %s IN(%s)', $table, $primary, implode(',', $ids) ) );
		return ( false !== $result );
	}
}
