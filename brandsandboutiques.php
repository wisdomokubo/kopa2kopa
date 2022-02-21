<?php
// Change this to your connection info.
$DB_HOST = 'localhost';
$DB_USER = 'kopakopa_wisdom1';
$DB_PASS = '@yankume1';
$DB_NAME = 'kopakopa_kopa2kopa';
// Try and connect using the info above.
$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
	// If there is an error with the connection, stop the script and display the error.
	die ('Failed to connect to MySQL: ' . $mysqli->connect_errno);
}
// Now we check if the data was submitted, isset will check if the data exists.
if (!isset($_POST['email'], $_POST['phone'], $_POST['brand'], $_POST['city'], $_POST['igusername'])) {
	// Could not get the data that should have been sent.
	die ('Please complete the registration form!<br><a href="brandsandboutiques.html">Back</a>');
}
// Make sure the submitted registration values are not empty.
if (empty($_POST['email']) ||  empty($_POST['phone']) ||  empty($_POST['brand'])||  empty($_POST['city'])||  empty($_POST['igusername'])) {
	// One or more values are empty...
	die ('Please complete the registration form!<br><a href="brandsandboutiques.html">Back</a>');
}
// We need to check if the account with that email exists
if ($stmt = $mysqli->prepare('SELECT id FROM brandsandboutiques WHERE email = ?')) {
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        die ('Email is not valid!<br><a href="brandsandboutiques.html">Back</a>');
    }
	// Bind parameters (s = string, i = int, b = blob, etc).
	$stmt->bind_param('s', $_POST['email']);
	$stmt->execute(); 
	$stmt->store_result(); 
	// Store the result so we can check if the account exists in the database.
	if ($stmt->num_rows > 0) {
		// Email already exists
		echo 'Email exists, please choose another!<br><a href="brandsandboutiques.html">Back</a>';
	} else {
		// Email doesnt exist, insert new account
		if ($stmt = $mysqli->prepare('INSERT INTO brandsandboutiques (email,  phone, brand, city, igusername) VALUES (?, ?, ?, ?, ?)')) {
			$stmt->bind_param('sssss', $_POST['email'],  $_POST['phone'], $_POST['brand'], $_POST['city'], $_POST['igusername']);
			$stmt->execute();
			echo 'You have successfully applied!.<br> Anticipate a reply email from our team.
			<br><a href="index.html">Back</a>';
		} else {
			echo 'Could not complete process!';
		}
	}
	$stmt->close();
} else {
	echo 'Could not complete process!';
}
$mysqli->close();
?>