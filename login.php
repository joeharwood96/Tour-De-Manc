<?php

include "header.php";

// values to show in the form:
$username = "";
$password = "";
$errors="";

// should we show the signin form:
$show_signin_form = false;
// message to output to user:
$message = "";

if (isset($_SESSION['loggedIn']))
{
    // users logged in, show message
    echo "You are already logged in<br>";

}
elseif (isset($_POST['username']))
{

    $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
  
        if (!$connection)
        {
            die("Connection failed: " . $mysqli_connect_error);
        }
    
        $username = $_POST['username'];
        $password= $_POST['password'];
		
		$username = sanitise($username,$connection);
		$password = sanitise($password,$connection);
		$username_val = validateString($username,1,1000);
		$password_val = validateString($password,1,1000);
		
		$errors = "";
		if($errors == ""){
			$password= $frontSalt.$password.$backSalt;
			$password= hash('sha512',$password);
			
			$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";

			// returned a username or not
			$result = mysqli_query($connection, $query);

			$n = mysqli_num_rows($result);

			if ($n > 0)
			{
				$_SESSION['loggedIn'] = true;
				$_SESSION['username'] = $username;

				$message = "<div class='message'>Hi, $username, you have successfully logged in, please <a href='profile.php'>click here</a> to view your profile.</div>";
			}
        else
        {
            // no matching credentials found so redisplay the signin form with a failure message:
            $show_signin_form = true;
            // show an unsuccessful signin message:
            $message = "<div class='message'>Sign in failed, please try again</div>";
        }

    // we're finished with the database, close the connection:
    mysqli_close($connection);
}

else
{
    // user has arrived at the page for the first time, just show them the form:

    // show signin form:
    $show_signin_form = true;
}
}
else {
            $show_signin_form = true;
            
}
if ($show_signin_form)
{
echo <<<_END

<div class="login">
    <form action="login.php" method="post">
        <div class="form-row">
            <div class="form-group col-md-6">
            <label for="inputUsername4">Username</label>
            <input type="text" class="form-control" name="username" id="inputUsername4" placeholder="Username" required>
            </div>
            <div class="form-group col-md-6">
            <label for="inputPassword4">Password</label>
            <input type="password" class="form-control" name="password" id="inputPassword4" placeholder="Password" required>
            </div>
        </div>
        <button type="submit" class="btn" style="background:#CD3333; color: white;">Sign in</button> <br><br>
    </form>
</div>
_END;
}

echo $message;

include "footer.php";

?>