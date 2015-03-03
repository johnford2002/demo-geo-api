<?php
/**
 * City data service for all city data needs.
 *
 * @author John Ford <john.ford2002@gmail.com>
 * @since 2015-03-01
 */
namespace DemoGeoAPI\Service;

use DemoGeoAPI\Service;

class CityDataService extends AbstractDataService {

	/**
	 * Earth's radius in miles
	 */
	const EARTH_RADIUS = 3959;

	/**
	 * @var string $_primaryKeyColumn
	 */
	protected $_primaryKeyColumn = "CityID";

	/**
	 * @var string $_tableName
	 */
	protected $_tableName = "City";

	/**
	 * Fetch a city record by the city name and state
	 *
	 * @param string $city
	 * @param string $state
	 * @param string $type
	 * @throws \Exception
	 * @return array
	 */
	public function fetchByCityAndState($city, $state, $cityType = 'Name', $stateType = 'Abbreviation'){
		if(!in_array($cityType, Service\CityDataService::getAllowedCityLookupTypes())){
			throw new \Exception("Unrecognized type passed for city lookup, city: ".$city.", type: ".$cityType);
		}

		if(!in_array($stateType, Service\StateDataService::getAllowedStateLookupTypes())){
			throw new \Exception("Unrecognized type passed for state lookup, state: ".$state.", type: ".$stateType);
		}

		$sql = "
			SELECT
				C.*
			FROM
				City C
				LEFT JOIN State S USING (StateID)
			WHERE
				C.".$cityType." = ?
				AND S.".$stateType." = ?
			";

		try{
			$returnArray = $this->_dbConnection->fetchAssoc($sql, array($city, $state));
		} catch (\Exception $e){
			$returnArray = array();	
		}

		return $returnArray;
	}

	/**
	 * Fetch all city records for the given state
	 * @param mixed $state
	 * @param string $type
	 * @throws \Exception
	 * @return array
	 */
	public function fetchByState($state, $type = 'Abbreviation'){
		if(!in_array($type, Service\StateDataService::getAllowedStateLookupTypes())){
			throw new \Exception("Unrecognized type passed for city lookup by state, state: ".$state.", type: ".$type);
		}

		$sql = "
			SELECT
				C.CityID,
				C.Name AS City,
				S.StateID,
				S.Abbreviation As State,
				C.Status,
				C.Latitude,
				C.Longitude,
				C.DateAdded,
				C.DateTimeAdded,
				C.LastUpdated
			FROM
				City C
				LEFT JOIN State S USING (StateID)
			WHERE
				S.".$type." = ?
			";

		try{
			$returnArray = $this->_dbConnection->fetchAll($sql, array($state));
		} catch (\Exception $e){
			throw $e;
			$returnArray = array();
		}

		return $returnArray;
	}

	/**
	 * Attempt to retrieve all cities within the given radius of the given city
	 *
	 * Adapted for use from
	 * @author Chris Veness
	 * @see http://www.movable-type.co.uk/scripts/latlong-db.html
	 *
	 * @param array $cityArray
	 * @param number $radius -- miles
	 */
	public function fetchByCityRadius($cityArray, $radius){
		// Save the city's latitude and longitude values to easier to reference local variables
		$latDeg = $cityArray['Latitude'];
		$lonDeg = $cityArray['Longitude'];

		// Get the city's lat and lon as radians instead of degrees
		$latRad = deg2rad($latDeg);
		$lonRad = deg2rad($lonDeg);

		// Get the first-cut bounding box using city as the starting point (in degrees)
	    $maxLatDeg = $latDeg + rad2deg($radius/self::EARTH_RADIUS);
	    $minLatDeg = $latDeg - rad2deg($radius/self::EARTH_RADIUS);
	    // Compensate for degrees longitude getting smaller with increasing latitude
	    $maxLonDeg = $lonDeg + rad2deg($radius/self::EARTH_RADIUS/cos($latRad));
	    $minLonDeg = $lonDeg - rad2deg($radius/self::EARTH_RADIUS/cos($latRad));

	    // Complex SQL query to find cities in the database within radius of initial city
	    $sql = "
		    SELECT 
		    	FirstCut.*,
	            acos(sin(:lat)*sin(radians(Latitude)) + cos(:lat)*cos(radians(Latitude))*cos(radians(Longitude)-:lon)) * :EarthRadius As MileDistance
	        FROM (
	        	/* SUBQUERY TO LIMIT RESULTS USING BOUNDING BOX */
	            SELECT 
	            	C.CityID, 
	            	C.Name AS City,
	            	S.StateID,
	            	S.Abbreviation AS State,
	            	C.Status,
	            	C.Latitude,
	            	C.Longitude,
	            	C.DateAdded,
	            	C.DateTimeAdded,
	            	C.LastUpdated
	            FROM 
	            	City C
	            	LEFT JOIN State S USING (StateID)
	            WHERE 
	            	C.Latitude Between :minLat And :maxLat
	              	And C.Longitude Between :minLon And :maxLon
	        ) As FirstCut
	        WHERE 
	        	acos(sin(:lat)*sin(radians(Latitude)) + cos(:lat)*cos(radians(Latitude))*cos(radians(Longitude)-:lon)) * :EarthRadius < :rad
	        ORDER BY MileDistance
            ";

        // Parameters required for query
	    $params = array(
	        'lat'    		=> $latRad,
	        'lon'    		=> $lonRad,
	        'minLat' 		=> $minLatDeg,
	        'minLon' 		=> $minLonDeg,
	        'maxLat' 		=> $maxLatDeg,
	        'maxLon' 		=> $maxLonDeg,
	        'rad'    		=> $radius,
	        'EarthRadius'   => self::EARTH_RADIUS,
	    );

	    try{
	    	// Execute our SQL as a prepared statement with params
		    $statement = $this->_dbConnection->executeQuery($sql, $params);

		    // Get the results
		    $returnArray = $statement->fetchAll();
	    } catch (\Exception $e){
	    	$returnArray = array();
	    }
	    
	    return $returnArray;
	}

	/**
	 * Attempt to determine the type of a city lookup from the given value
	 *
	 * @return string $type
	 */
	public static function getCityType($city){
		// Perform lookup on StateID, Name, or Abbreviation
		if(is_numeric($city)){
			$type = 'CityID';
		} elseif(is_string($city)){
			$type = 'Name';
		} else {
			$type = 'Unknown';
		}

		return $type;
	}

	/**
	 * Get an array of allowed lookup types for city
	 *
	 * @return array
	 */
	public static function getAllowedCityLookupTypes(){
		return array('Name', 'CityID');
	}
}