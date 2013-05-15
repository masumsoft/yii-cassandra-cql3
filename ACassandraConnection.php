<?php

/**
 * We will use this class as a Cassandra Wrapper for Yii
 *
 * @author Masum
 */

Yii::import('ext.yii-cassandra-cql3.autoload');
require_once('protected/extensions/yii-cassandra-cql3/autoload.php');

use phpcassa\Connection\ConnectionPool;
use phpcassa\ColumnFamily;
use phpcassa\ColumnSlice;
use phpcassa\SystemManager;
use phpcassa\Schema\StrategyClass;
use phpcassa\Schema\DataType;

class ACassandraConnection extends CApplicationComponent {

    protected $_pool = null;
	public $keyspace = null;
	public $servers = null;	
	
	/**
     * Establish connection to cassandra cluster
     * @return object 
     */
    private function _get_raw() {
        if ($this->_pool === null) {
            $this->_pool = new ConnectionPool($this->keyspace, $this->servers);
        }
		return $this->_pool->get();
    }
	
	
	public function cql3_query($query, $compression=cassandra\Compression::NONE, $consistency=cassandra\ConsistencyLevel::ONE) {
		$raw = $this->_get_raw();
		$cql_result = $raw->client->execute_cql3_query($query, $compression, $consistency);
		$this->_pool->return_connection($raw); 
		return $cql_result;
	}
	
	public function cql_get_rows($cql_result) {
		if ($cql_result->type == 1) { 
	        $rows = array(); 
	        foreach ($cql_result->rows as $rowIndex => $cqlRow) { 
	            $cols = array(); 
	            foreach ($cqlRow->columns as $colIndex => $column) {
	            	$type = DataType::get_type_for($cql_result->schema->value_types[$column->name]);
	            	$cols[$column->name] = $type->unpack($column->value);
	            }
	            $rows[] = $cols;
	        }
	        return $rows; 
	    } else {
	        return null;
	    }
	}

    	
    /**
     * Perform garbage collection 
     */
    public function __destruct() {
        if($this->_pool !== null) {
			$this->_pool->close();
		}
    }

}

?>