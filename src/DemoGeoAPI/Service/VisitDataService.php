<?php
/**
 * Visit data service for all visit data needs.
 *
 * @author John Ford <john.ford2002@gmail.com>
 * @since 2015-03-01
 */
namespace DemoGeoAPI\Service;

class VisitDataService extends AbstractDataService {

	protected $_primaryKeyColumn = "VisitID";
	protected $_tableName = "Visit";

	/**
	 * Fetch all visit records for the given userId
	 *
	 * @param $userId
	 * @return array
	 */
	public function fetchByUserId($userId){
		$sql = "
			SELECT
				V.VisitID,
				U.UserID,
				U.FirstName,
				U.LastName,
				C.CityID,
				C.Name AS City,
				S.StateID,
				S.Abbreviation AS State,
				V.DateAdded,
				V.DateTimeAdded,
				V.LastUpdated
			FROM
				Visit V
				LEFT JOIN User U USING (UserID)
				LEFT JOIN City C USING (CityID)
				LEFT JOIN State S USING (StateID)
			WHERE
				U.UserID = ?
			";

		try{
			$returnArray = $this->_dbConnection->fetchAll($sql, array((int)$userId));
		} catch (\Exception $e){
			$returnArray = array();	
		}

		return $returnArray;
	}

	/**
	 * Delete all visit records for the given userId
	 *
	 * @param $userId
	 * @throws \Exception
	 * @return void
	 */
	public function deleteByUserId($userId){
		try{
			$this->_dbConnection->delete('Visit', array('UserID' => (int) $userId));
		} catch (\Exception $e){
			throw new \Exception("Unable to delete visits for UserID ".$userId);
		}
	}
}