<?php
	require_once ('unirest' . DIRECTORY_SEPARATOR . 'Unirest.php');

	define('ACCUWEATHER_API_KEY', 'HackPSU2018');
	
	define('ACCUWEATHER_CITY_NAME_QUERY', 'http://dataservice.accuweather.com/locations/v1/cities/search');
	define('ACCUWEATHER_ALERT_QUERY_PREFIX', 'http://dataservice.accuweather.com/alerts/v1/');
	
	define('JSON_REQUEST_HEADER', array('Accept' => 'application/json'));
	
	class WeatherLocation
	{
		private $locationKey = '';
		
		public function fromString(string $locationStr)
		{
			$queryParameters = array('apikey' => ACCUWEATHER_API_KEY, 'q' => $locationStr);
			
			$accuweatherResponse = Unirest\Request::get(ACCUWEATHER_CITY_NAME_QUERY, JSON_REQUEST_HEADER, $queryParameters);
			
			if(count($accuweatherResponse->body) == 1)
			{
				$this->$locationKey = $accuweatherResponse->body[0]['Key'];
				return true;
			}
			else
			{
				return false;
			}
		}
		
		public function fromKey(string $locationKey)
		{
			$this->$locationKey = $locationKey;
		}
		
		public function getKey()
		{
			return $this->$locationKey;
		} 
	}
	
	
	function getWeatherAlerts(WeatherLocation $location)
	{
		$queryParameters = array('apikey' => ACCUWEATHER_API_KEY);
		
		$accuweatherResponse = Unirest\Request::get(ACCUWEATHER_ALERT_QUERY_PREFIX + urlencode($location->getKey()), JSON_REQUEST_HEADER, $queryParameters);
		
		return $accuweatherResponse->body;
	}
	
	function getAlertType(object $alertObj)
	{
		if(mb_stristr($alertObj['Area']['Name'], 'hurricane', FALSE, 'utf-8'))
		{
			return 'hurricane';
		}
		else
		{
			return 'unknown';
		}
	}
?>