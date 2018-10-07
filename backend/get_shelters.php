<?php
	require_once 'db_operations.php';
	
	header('Content-Type: application/json');
	
	$hurricaneCenters = getHurricaneShelters(doubleval($_GET['latitude']), doubleval($_GET['longitude']), 0.5, 0.5);
	echo json_encode($hurricaneCenters);
?>