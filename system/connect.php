<?php
session_start();
// Turn on error reporting during development
error_reporting(E_ALL);
ini_set('display_errors', 1);

function database(){
	$servername = "localhost";
	$db = "src_brightweb";
	$username = "root";
	$password = "AdminAdmin"; // Make sure this is correct
	try {
		$stmt = new PDO("mysql:host=$servername;dbname=$db;charset=utf8;", $username, $password);
		$stmt->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		return $stmt;
	} catch (Exception $e) {
		echo "Database connection error: " . $e->getMessage();
		exit;
	}
}
?>