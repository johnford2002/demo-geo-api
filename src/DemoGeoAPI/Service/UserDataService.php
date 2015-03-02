<?php
/**
 * User data service for all user data needs.
 *
 * @author John Ford <john.ford2002@gmail.com>
 * @since 2015-03-01
 */
namespace DemoGeoAPI\Service;

class UserDataService extends AbstractDataService {

	protected $_primaryKeyColumn = "UserID";
	protected $_tableName = "User";
}