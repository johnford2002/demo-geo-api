<?php
/**
 * Abstract data service for all other data services to extend.
 *
 * @author John Ford <john.ford2002@gmail.com>
 * @since 2015-03-01
 */
namespace DemoGeoAPI\Service;

use \Doctrine\DBAL\Connection;

abstract class AbstractDataService {

	/**
	 * @var string $_primaryKeyColumn
	 */
	protected $_primaryKeyColumn;

	/**
	 * @var string $_tableName
	 */
	protected $_tableName;

	/**
	 * @var Doctrine\DBAL\Connection $_dbConnection
	 */
	protected $_dbConnection;

	/**
	 * General purpose constructor, sets up DB connection
	 *
	 * @param \Doctrine\DBAL\Connection $dbConn
	 */
	public function __construct(Connection $dbConn){
		$this->_dbConnection = $dbConn;
	}

	/**
	 * Return a record from the table using the primary key
	 *
	 * @param mixed $pk
	 * @return array
	 */
	public function fetchByPrimaryKey($pk){
		$sql = "SELECT * FROM ".$this->_tableName." WHERE ".$this->_primaryKeyColumn."=?";

		try{
			$resultArray = $this->_dbConnection->fetchAssoc($sql, array($pk));
		} catch (\Exception $e){
			$resultArray = array();
		}
		
		return $resultArray;
	}

	/**
	 * Insert a record
	 *
	 * @param array $dataArray
	 * @throws \Exception
	 * @return void
	 */
	public function insert($dataArray){
		$visitArray['DateAdded'] = date('Y-m-d');
		$visitArray['DateTimeAdded'] = date('Y-m-d H:i:s');
		$visitArray['LastUpdated'] = date('Y-m-d H:i:s');

		try{
			$this->_dbConnection->insert($this->_tableName, $dataArray);
		} catch(\Exception $e){
			throw new \Exception("Failed to insert record for table '".$this->_tableName."' with data: ".var_export($dataArray, true));
		}
	}
}