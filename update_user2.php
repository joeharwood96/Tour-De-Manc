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
elseif (isset($_POST['newPassword1'])){
    // user just tried to update their profile
	
	// connect directly to our database (notice 4th argument) we need the connection for sanitisation:
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	
	// if the connection fails, we need to know, so allow this exit:
	if (!$connection)
	{
		die("Connection failed: " . $mysqli_connect_error);
	}
	
	$username = $_SESSION['username'];
	$password1 = $_POST['newPassword1'];
	$password2 = $_POST['newPassword2'];
	$password1 = sanitise($password1,$connection);
	$password2 = sanitise($password2,$connection);
	
	$pass_val1 = validateString($password1,1,1000);
	$pass_val2 = validateString($password2,1,1000);
	
	$errors = $pass_val1 . $pass_val2;
	if($errors == ""){
		$password1 = hash('sha512',$frontSalt.$password1.$backSalt);
		// we need an UPDATE:
		$query = "Update users set password = '$password1' where username = '$username'";
		$result = mysqli_query($connection, $query);		

		echo $password1 . " = was inserted into the database <br>";
		echo $frontSalt."s".$backSalt ." = salted 's'<br>";
		
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

if($showForm){
  echo  <<<_END
    <div class="update">
    <form action="update_user2.php" method="post">
		<div class="form-group">
            <label for="inputAddress2">Username</label>
            <input type="text" title="Your Username" class="form-control" id="inputAddress2" placeholder="$username" name="username" value="$firstName" readonly>
        </div>
		
		<div class="form-group">
            <input type="text" title="" class="form-control" id="inputAddress2" placeholder="" name="udpatePassword" value="" hidden>
        </div>
            
		<div class="form-group">
            <label for="inputAddress2">Last Name</label>
            <input type="text" title="enter new password" class="form-control" id="inputAddress2" placeholder="Type New Password" name="newPassword1" value="" required>
		</div>
            
		<div class="form-group">
			<label for="telephone">Telephone</label>
			<input type="text" title="re-enter new password" class="form-control" id="inputAddress2" placeholder="Re-enter Password" name="newPassword2" value="" required>
		</div>
			
         <button type="submit" class="btn" style="background:#CD3333; color: white;">Update Password</button>
        </form>
    </div>

_END;

}

echo "$message";

include "footer.php";

?>