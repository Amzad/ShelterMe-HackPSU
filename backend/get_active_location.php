<?php
	require_once 'db_operations.php';

	header('Content-Type: text/plain');

	echo getActivePostalCode(getSessionUID());
?>