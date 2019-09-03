<?php

include "header.php";

$email = "";
$postcode = "";
$firstName = "";
$lastName = "";
$telephone = "";

$showForm = "";

if (!isset($_SESSION['loggedIn']))
{
	// user isn't logged in, display a message saying they must be:
	echo "You must be logged in to view this page.<br>";
}
elseif (isset($_POST['email']))
{

    // user just tried to update their profile
	
	// connect directly to our database (notice 4th argument) we need the connection for sanitisation:
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	
	// if the connection fails, we need to know, so allow this exit:
	if (!$connection)
	{
		die("Connection failed: " . $mysqli_connect_error);
	}
	

	$email = $_POST['email'];
	$postcode = $_POST['postcode'];
	$firstName = $_POST['firstName'];
	$lastName = $_POST['lastName'];
    $telephone = $_POST['telephone'];

	$email = sanitise($email,$connection);
	$postcode = sanitise($postcode,$connection);
	$firstName = sanitise($firstName,$connection);
	$lastName = sanitise($lastName,$connection);
	$telephone = sanitise($telephone,$connection);
	
	$email_val = validateString($email,1,1000);
	$postcode_val = validateString($postcode,1,1000);
	$firstName_val = validateString($email,1,1000);
	$lastName_val = validateString($email,1,1000);
	$tel_val = validateString($email,1,1000); //TO DO - IMPORTANT
	
	$errors = $email_val . $firstName_val . $lastName_val . $tel_val . $postcode_val;
	if($errors == ""){
		// check if the username has been given in the url
		if(isset($_GET['username']))
		{
            $username = $_GET['username'];
		} else {
			// if no user name is not in url set username to session
			$username = $_SESSION["username"];
		}
		
		// now write the new data to our database table...
		
		// check to see if this user already had a favourite:
		$query = "SELECT * FROM users WHERE username='$username'";
		
		// this query can return data ($result is an identifier):
		$result = mysqli_query($connection, $query);
		
		// how many rows came back? (can only be 1 or 0 because username is the primary key in our table):
		$n = mysqli_num_rows($result);
			
		// if there was a match then UPDATE their profile data
		if ($n > 0){
            // we need an UPDATE:
            $query = "UPDATE users SET email='$email', firstName='$firstName', lastName='$lastName', postcode='$postcode', telephone='$telephone' WHERE username='$username'";
            $result = mysqli_query($connection, $query);
		}
	

		// no data returned, we just test for true(success)/false(failure):
		if ($result){
			// show a successful update message:
			$message = "<div class='message'>Profile successfully updated: <a href='profile.php'>Click Here</a> to return to your profile</div>" ;
		} 
		else{
			// show the set profile form:
			$showForm = true;
			// show an unsuccessful update message:
			$message = "<div class='message'>Update failed</div>";
		}
	}
	else {
		// show the set profile form:
			$showForm = true;
			// show an unsuccessful update message:
			$message = "<div class='message'>Update failed</div>";
	}
}
else{
    $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	
	// if the connection fails, we need to know, so allow this exit:
	if (!$connection)
	{
		die("Connection failed: " . $mysqli_connect_error);
	}

    $username = $_SESSION['username'];
    // check for a row in our profiles table with a matching username:
	$query = "SELECT * FROM users WHERE username='$username'";
	
	// this query can return data ($result is an identifier):
	$result = mysqli_query($connection, $query);
	
	// how many rows came back? (can only be 1 or 0 because username is the primary key in our table):
    $n = mysqli_num_rows($result);
    
		
	// if there was a match then extract their profile data:
	if ($n > 0)
	{
		// use the identifier to fetch one row as an associative array (elements named after columns):
		$row = mysqli_fetch_assoc($result);
		// extract their profile data for use in the HTML:
		$email = $row['email'];
		$postcode = $row['postcode'];
		$firstName = $row['firstName'];
		$lastName = $row['lastName'];
        $telephone = $row['telephone'];

        $showForm = true;
    }
    // we're finished with the database, close the connection:
	mysqli_close($connection);
}

if ($_SESSION['username'] == "admin")
{
	//dont show the form for non admin users
	$showForm = false;

	//Get the username from the url:
	if(isset($_GET['username']))
	{
		$username = $_GET['username'];
	}	

	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	
	// if the connection fails, we need to know, so allow this exit:
	if (!$connection)
	{
		die("Connection failed: " . $mysqli_connect_error);
	}
	
	// check for a row in our profiles table with a matching username:
	$query = "SELECT * FROM users WHERE username='$username'";
	
	// this query can return data ($result is an identifier):
	$result = mysqli_query($connection, $query);
	
	// how many rows came back? (can only be 1 or 0 because username is the primary key in our table):
	$n = mysqli_num_rows($result);
		
	// if there was a match then extract their profile data:
	if ($n > 0)
	{
		// use the identifier to fetch one row as an associative array (elements named after columns):
		$row = mysqli_fetch_assoc($result);
		// extract their profile data for use in the HTML:
		$email = $row['email'];
		$postcode = $row['postcode'];
		$firstName = $row['firstName'];
		$lastName = $row['lastName'];
        $telephone = $row['telephone'];

	}
	
	mysqli_close($connection);
	//Show the form of the user being updated
	echo <<<_END
    <div class="update">
    <form action="update_user.php?username=$username" method="post">
    <div class="form-group">
            <label for="inputAddress2">First Name</label>
            <input type="text" title="input new first name" class="form-control" id="inputAddress2" placeholder="$firstName" name="firstName" value="$firstName" required>
        </div>
            
    <div class="form-group">
            <label for="inputAddress2">Last Name</label>
            <input type="text" title="input new surname" class="form-control" id="inputAddress2" placeholder="$lastName" name="lastName" value="$lastName" required>
            </div>
            
    <div class="form-group">
                <label for="telephone">Telephone</label>
                <input type="text" title="input new telephone number" class="form-control" id="telephone" placeholder="$telephone" name="telephone" value="$telephone" required>
			</div>
			
	<div class="form-group">
                <label for="postcode">Postcode</label>
                <input type="text" title="input new postcode" class="form-control" id="postcode" placeholder="$postcode" name="postcode" value="$postcode" required>
            </div>
            
    <div class="form-group">
            <label for="inputEmail4">Email</label>
                <input type="email" title="input new email" class="form-control" id="inputEmail4" placeholder="$email" name="email" value="$email" required>
            </div>
            <button type="submit" class="btn" style="background:#CD3333; color: white;">Update</button>
        </form>
    </div>
	
_END;

}

if($showForm){
  echo  <<<_END
    <div class="update">
    <form action="update_user.php" method="post">
    <div class="form-group">
            <label for="inputAddress2">First Name</label>
            <input type="text" title="input new first name" class="form-control" id="inputAddress2" placeholder="$firstName" name="firstName" value="$firstName" required>
        </div>
            
    <div class="form-group">
            <label for="inputAddress2">Last Name</label>
            <input type="text" title="input new surname" class="form-control" id="inputAddress2" placeholder="$lastName" name="lastName" value="$lastName" required>
            </div>
            
    <div class="form-group">
                <label for="telephone">Telephone</label>
                <input type="text" title="input new telephone number" class="form-control" id="telephone" placeholder="$telephone" name="telephone" value="$telephone" required>
			</div>
			
	<div class="form-group">
                <label for="postcode">Postcode</label>
                <input type="text" title="input new postcode" class="form-control" id="postcode" placeholder="$postcode" name="postcode" value="$postcode" required>
            </div>
            
    <div class="form-group">
            <label for="inputEmail4">Email</label>
                <input type="email" title="input new email address" class="form-control" id="inputEmail4" placeholder="$email" name="email" value="$email" required>
            </div>
            
            <button type="submit" class="btn" style="background:#CD3333; color: white;">Update Profile</button>
        </form>
    </div>

_END;

}

echo "$message";

include "footer.php";

?>