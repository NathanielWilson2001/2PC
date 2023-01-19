<?php
	/*
	 *	db.php
	 *	Connects to the Database on localhost.
	 */
	$host = "localhost";
	$username = "root";
	$dbname = "compx";

	$conn = new mysqli($host, $username, "root", $dbname);

	if ($conn->connect_error) {
		die("Error:" . $conn->connect_error);
	}
    return $conn;

?>