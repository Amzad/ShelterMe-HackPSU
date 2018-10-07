<?php
	require_once 'db_operations.php';
	
	addPostalCode(getSessionUID(), $_GET['postalcode']);
?>