<?php
	define('DB_CONN_STRING', 'mysql:host=localhost;dbname=weatherapp');
	define('DB_CONN_USERNAME', 'website');
	define('DB_CONN_PASSWORD', 'website');
	
	$latTolerance = 0.5;
	$longTolerance = 0.5;
	
	function openDBConnection()
	{
		return (new PDO(DB_CONN_STRING, DB_CONN_USERNAME, DB_CONN_PASSWORD));
	}
	
	function getHurricaneShelters($targetLat, $targetLong, $latTolerance, $longTolerance)
	{
		$dbConnection = openDBConnection();
		
		$hurricaneCenterQuery = $dbConnection->prepare('SELECT * FROM weatherapp.hurricane_shelters
																 WHERE (ABS(latitude - :currentLat) <= :latTol AND ABS(longitude - :currentLong) <= :longTol);');
		$hurricaneCenterQuery->execute(array(':currentLat' => $targetLat, ':currentLong' => $targetLong, ':latTol' => $latTolerance, ':longTol' => $longTolerance));
		$resultSet = $hurricaneCenterQuery->fetchAll();
		
		$dbConnection = null;
		$hurricaneCenterQuery = null;
		
		return $resultSet;
	}
	
	function getSessionUID()
	{
		return 0;
	}
	
	function getActivePostalCode($uid)
	{
		$dbConnection = openDBConnection();
		
		$activePostalCodeQuery = $dbConnection->prepare('SELECT postal_code FROM weatherapp.locations WHERE (user_id = :uid AND active = 1) LIMIT 1');
		$activePostalCodeQuery->execute(array(':uid' => $uid));
		
		$resultRow = $activePostalCodeQuery->fetch();
		
		$dbConnection = null;
		$activePostalCodeQuery = null;
		
		return $resultRow['postal_code'];
	}
	
	function addPostalCode($uid, $postalCode)
	{
		$dbConnection = openDBConnection();
		
		// One postal code at a time for now
		$addPostalCodeQuery = $dbConnection->prepare('UPDATE weatherapp.locations SET postal_code = :postalCode WHERE user_id = :uid;');
		$addPostalCodeQuery->execute(array(':uid' => $uid, ':postalCode' => $postalCode));
		
		$dbConnection = null;
		$addPostalCodeQuery = null;
	}
?>