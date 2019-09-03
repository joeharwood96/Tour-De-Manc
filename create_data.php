<?php

require_once "connection.php";

//DEBUG - NEED TO ADD SALTING DETAILS TO THE CREDENTIALS FILE

$connection = mysqli_connect($dbhost, $dbuser, $dbpass);
echo "<a href='login.php'>Login Here!</a><br><br>";
if (!$connection){
	die("Connection failed: " . $mysqli_connect_error);
}

$sql = "CREATE DATABASE IF NOT EXISTS " . $dbname;

if (mysqli_query($connection, $sql)) {
	echo "Database ". $dbname ." created successfully, or already exists<br>";
} 
else{
	die("Error creating database: ". $dbname . mysqli_error($connection));
}

mysqli_select_db($connection, $dbname);


$sql = "DROP TABLE IF EXISTS user_event, user_photographer, photo, event, users ";
if (mysqli_query($connection, $sql)) {
	echo "Dropped all tables as: <br>";
} 
else {	
	die("Error checking for existing table: " . mysqli_error($connection));
}
///////////////////////////////////////////
////////////// USERS TABLE ////////////////
///////////////////////////////////////////

$sql = "DROP TABLE IF EXISTS users";
if (mysqli_query($connection, $sql)) {
	echo "Dropped existing table: users<br>";
} 
else {	
	die("Error checking for existing table: " . mysqli_error($connection));
}

//password limit at 128 as the hashing function takes the total to 128 characters encryption
$sql = "CREATE TABLE users (
			userId INT AUTO_INCREMENT,
			username VARCHAR(32) NOT NULL UNIQUE, 
			password VARCHAR(128) NOT NULL, 
			firstName VARCHAR(64),
			lastName VARCHAR(64),
			telephone VARCHAR(16),
			email VARCHAR(64), 
			postcode VARCHAR(8),
			userType VARCHAR(32),
			PRIMARY KEY(userId)
		);";

if (mysqli_query($connection, $sql)) {
	echo "Table created successfully: Users<br>";
}
else {
	die("Error creating table: " . mysqli_error($connection));
}

$usernames[] = 'admin'; $passwords[] = 'secret'; $firstName[] = 'Admin'; $lastName[] = 'cyclist'; $telephone[] = '07496355542'; $emails[] = 'admin@teamdemanmet.com'; $postcode[] = 'B000 LLL'; $userType[] = 'admin';
$usernames[] = 'cyclist'; $passwords[] = 'secret'; $firstName[] = 'cyclist'; $lastName[] = 'cyclist'; $telephone[] = '07496355542'; $emails[] = 'cyclist@teamdemanmet.com'; $postcode[] = 'B000 LLL'; $userType[] = 'rider';
$usernames[] = 'photographer'; $passwords[] = 'secret'; $firstName[] = 'photographer'; $lastName[] = 'photographer'; $telephone[] = '07496355542'; $emails[] = 'photographer@teamdemanmet.com'; $postcode[] = 'B000 LLL'; $userType[] = 'photographer';
$usernames[] = 'eventorganiser'; $passwords[] = 'secret'; $firstName[] = 'eventorganiser'; $lastName[] = 'eventorganiser'; $telephone[] = '07496355542'; $emails[] = 'eventOrganiser@teamdemanmet.com'; $postcode[] = 'B000 LLL'; $userType[] = 'eventOrganiser';

for ($i=0; $i<count($usernames); $i++){
	$sPasswords[$i] = $frontSalt.$passwords[$i].$backSalt;
	$hPasswords[$i] = hash('sha512',$sPasswords[$i]); //takes each password input from the array and hashes it before inputted into the database.
	$sql = "INSERT INTO users (username, password, firstName, lastName, telephone, email, postcode, userType)
	VALUES ('$usernames[$i]', '$hPasswords[$i]', '$firstName[$i]', '$lastName[$i]', '$telephone[$i]','$emails[$i]', '$postcode[$i]', '$userType[$i]')";
	if (mysqli_query($connection, $sql)) {
		echo "row inserted into users<br>";
	}
	else {
		die("Error inserting row: " . mysqli_error($connection));
	}
}

///////////////////////////////////////////
///////////////// Event ///////////////////
///////////////////////////////////////////

	$sql = "DROP TABLE IF EXISTS event";

	if (mysqli_query($connection, $sql)) {
		echo "Dropped existing table: event<br>";
	} 
	else {	
		die("Error checking for existing table: " . mysqli_error($connection));
	}
	
	$sql = "CREATE TABLE event (
		eventId INT NOT NULL AUTO_INCREMENT,
		eventName VARCHAR(64),
		PRIMARY KEY(eventid)
		);";
	
	if (mysqli_query($connection, $sql)) {
		echo "Table created successfully: Event<br>";
	}
	else {
		die("Error creating table: " . mysqli_error($connection));
	}
	
	$eventName[] = 'Great Manchester Run';
	//$eventName[] = "The Manchester Cycling Event";
	
	for ($i=0; $i<count($eventName); $i++){
		$sql = "INSERT INTO event (eventName) VALUES ('$eventName[$i]')";
		
		// no data returned, we just test for true(success)/false(failure):
		if (mysqli_query($connection, $sql)) {
			echo "row inserted into event<br>";
		}
		else {
			die("Error inserting row: " . mysqli_error($connection));
		}
	}

	///////////////////////////////////////////
////////////// users_events table /////////
///////////////////////////////////////////

// if there's an old version of our table, then drop it:
	$sql = "DROP TABLE IF EXISTS user_event";

	// no data returned, we just test for true(success)/false(failure):
	if (mysqli_query($connection, $sql)) 
	{
		echo "Dropped existing table: user_event<br>";
	} 
	else 
	{	
		die("Error checking for existing table: " . mysqli_error($connection));
	}
	
	// make our table:
	$sql = "CREATE TABLE user_event (
				userId INT,
				eventId INT,
				FOREIGN KEY (userId) REFERENCES users(userId),
				FOREIGN KEY (eventId) REFERENCES event(eventId),
				PRIMARY KEY (userId, eventId)
			);"; //primary composite key - cant have the same combination of user to event - each user can only be a part of one event "once".
	
	if (mysqli_query($connection, $sql)) {
		echo "Table created successfully: user_event<br>";
	}
	else {
		die("Error creating table: " . mysqli_error($connection));
	}
	/*
	for ($j=0; $j<count($usernames); $j++){
		for ($i=0; $i<count($option); $i++){
			$sql = "INSERT INTO user_event (userId, eventId) VALUES (1,1)";
			$questionID_option[$i] = $questionID_option[$i] + 5;
			if (mysqli_query($connection, $sql)) {
				echo "row inserted<br>";
			}
			else {
				die("Error inserting row: " . mysqli_error($connection));
			}
		}
	}
*/

///////////////////////////////////////////
////////////// Photos Table/ //////////////
///////////////////////////////////////////

$sql = "DROP TABLE IF EXISTS photo";

	if (mysqli_query($connection, $sql)) {
		echo "Dropped existing table: Photo<br>";
	} 
	else {	
		die("Error checking for existing table: " . mysqli_error($connection));
	}
	
	$sql = "CREATE TABLE photo (
		photoId INT NOT NULL AUTO_INCREMENT,
		userId INT,
		photoSrc VARCHAR(256), 
		riderNum INT(10),
		eventId INT, 
		PRIMARY KEY(photoId), 
		FOREIGN KEY (eventId) REFERENCES event(eventId)
		)";
	
	if (mysqli_query($connection, $sql)) {
		echo "Table created successfully: Photo<br>";
	}
	else {
		die("Error creating table: " . mysqli_error($connection));
	}
	
	for($i=0; $i < 1; $i++){
		$src[] = "images/riders/(119_of_121).jpg";
		$src[] = "images/riders/(114_of_121).jpg";
		$src[] = "images/riders/(115_of_121).jpg";
		$src[] = "images/riders/(120_of_121).jpg";
		$src[] = "images/riders/(119_of_121).jpg";
		$src[] = "images/riders/(115_of_121).jpg";
		$src[] = "images/riders/(115_of_121).jpg";

	}

	for ($i=0; $i<count($src);$i++){

		$sql = "INSERT INTO photo (photoSrc, eventId, userId, riderNum) VALUES ('$src[$i]', 1, 3, $i)"; //insert one 'photo' for each user to have
		echo $sql;
		if (mysqli_query($connection, $sql)) {
			echo "row inserted into photo<br>";
		}
		else {
			die("Error inserting row: " . mysqli_error($connection));
		}
	}



///////////////////////////////////////////
////////////// user_photographer table /////////
///////////////////////////////////////////

// if there's an old version of our table, then drop it:
	$sql = "DROP TABLE IF EXISTS user_photographer";

	// no data returned, we just test for true(success)/false(failure):
	if (mysqli_query($connection, $sql)) 
	{
		echo "Dropped existing table: user_photographer<br>";
	} 
	else 
	{	
		die("Error checking for existing table: " . mysqli_error($connection));
	}
	
	// make our table:
	$sql = "CREATE TABLE user_photographer (
				userId INT,
				photoId INT,
				FOREIGN KEY (userId) REFERENCES users(userId),
				FOREIGN KEY (photoId) REFERENCES photo(photoId),
				PRIMARY KEY (userId, photoId)
			);"; //primary composite key - cant have the same combination of user to event - each user can only be a part of one event "once".
	
	if (mysqli_query($connection, $sql)) {
		echo "Table created successfully: user_photographer<br>";
	}
	else {
		die("Error creating table: " . mysqli_error($connection));
	}
// we're finished, close the connection:
mysqli_close($connection);
?>