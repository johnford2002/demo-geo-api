<?php
/**
 * State data service for all state data needs.
 *
 * @author John Ford <john.ford2002@gmail.com>
 * @since 2015-03-01
 */
namespace DemoGeoAPI\Service;

class StateDataService extends AbstractDataService {

	protected $_primaryKeyColumn = "StateID";
	protected $_tableName = "State";

	/**
	 * Attempt to determine the type of a state lookup from the given value
	 *
	 * @return string $type
	 */
	public static function getStateType($state){
		// Perform lookup on StateID, Name, or Abbreviation
		if(is_numeric($state)){
			$type = 'StateID';
		} elseif(is_string($state) && strlen($state)>2){
			$type = 'Name';
		} elseif (is_string($state) && strlen($state)===2) {
			$type = 'Abbreviation';
		} else {
			$type = 'Unknown';
		}

		return $type;
	}

	/**
	 * Get an array of allowed lookup types for state
	 *
	 * @return array
	 */
	public static function getAllowedStateLookupTypes(){
		return array('Abbreviation', 'Name', 'StateID');
	}
}