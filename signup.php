<?php

include "header.php";

// default values we show in the form:
$username = ""; 
$password = ""; 
$password2 = "";
$firstName= ""; 
$lastName = ""; 
$telephone ="";
$email = ""; 
$errors = "";
$username_val = "";
$password_val = "";
$password_val2 = "";
$fname_val = "";
$lname_val = "";
$tel_val = "";
$email_val = "";
$result = "";

// should we show the signup form?:
$show_signup_form = false;
// message to output to user:
$message = "";

if (isset($_SESSION['loggedIn']))
{
    // user is already logged in, just display a message:
    echo "You are already logged in, please log out if you wish to create a new account<br>";

}
elseif (isset($_POST['username']))
{
    // user just tried to sign up:

    // connect directly to our database (notice 4th argument) we need the connection for sanitisation:
    $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

    // if the connection fails, we need to know, so allow this exit:
    if (!$connection)
    {
        die("Connection failed: " . $mysqli_connect_error);
    }
    
    $username = $_POST['username'];
    $password = $_POST['password'];
	$password2 = $_POST['password2'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];

	//able to use functions from 'validate' script due to 'validate.php' being 'required' in header, and header being 'included' for each script.
	//escape unwanted characters in 'sanitise' function from validate script
	$username = sanitise($username,$connection);
    $password = sanitise($password,$connection);
	$password2 = sanitise($password2,$connection);
    $firstName = sanitise($firstName,$connection);
    $lastName = sanitise($lastName,$connection);
    $telephone = sanitise($telephone,$connection);
    $email = sanitise($email,$connection);
	
	//check that the strings entered are valid for storage in the database
	$username_val = validateString($username, 1, 1000);
	$password_val = validateString($password, 1, 1000);
	$password_val2 = validateString($password2, 1, 1000);
	$fname_val = validateString($firstName, 1, 1000);
	$lname_val = validateString($lastName, 1, 1000);
	$tel_val = validateString($telephone, 1, 1000);
	$email_val = validateString($email, 1, 1000);
	$errors = "";
	$regExp = "#^.{1,}@.{1,}\..{1,}$#";
	
	if(preg_match($regExp,$email)){
		$email_val .= ""; //it is correct to give it no errors
	}
	else{
		$email_val .= "Email invalid - Suggested Format: john.smith@gmail.com.<br>"; //set text to have value becuase it failed
	}
	
	if ($password !== $password2)
	{
		$errors .= "Passwords dont match.<br>"; //populate errors
	}
	
	$query = "SELECT * FROM Users WHERE username ='$username'";
		
	$result = mysqli_query($connection, $query);
	if(mysqli_num_rows($result)!=0){
		$errors.="Username taken.<br>";
	}
		
	$errors .= $username_val . $password_val . $password_val2 . $fname_val . $lname_val . $tel_val . $email_val; //additional population to errors, if the sanitise functions return anything other than "" (empty string)
	echo $errors;
	if($errors == ""){
        // insert validated user details
		$password = hash('sha512',$frontSalt.$password.$backSalt);
		
        $query = "INSERT INTO users (username, password, firstName, lastName, telephone, email, userType) VALUES ('$username', '$password', '$firstName', '$lastName','$telephone','$email', 'cyclist');";

        $result = mysqli_query($connection, $query);

        // no data returned, we just test for true(success)/false(failure):
        if ($result) 
        {
            // show a successful signup message:
            $message = "<div class='message'>Signup was successful, please <a href='login.php'>Click here</a> to sign in.</div>";
        } 
        else 
        {
            // show the form:
            $show_signup_form = true;
            // show an unsuccessful signup message:
            $message = "<div class='message'>Sign up failed, please try again</div>";
        }
	}
	else
	{
        // show the form:
		$show_signup_form = true;
		// show an unsuccessful signup message:
		$message = "<div class='message'>Sign up failed, please try again</div>";
    }
    // we're finished with the database, close the connection:
    mysqli_close($connection);
}
else
{
    // just a normal visit to the page, show the signup form:
    $show_signup_form = true;

}

if ($show_signup_form)
{	

    echo 
        <<<_END
<div class="login">
<form action="signup.php" method="post">
<div class="form-row">
         <div class="form-group col-md-6">
          <label for="inputAddress">Username</label>
          <input type="text" class="form-control" id="inputAddress" name="username" placeholder="Username" value="$username" required>
        </div>

  <div class="form-group col-md-6">
            <label for="inputPassword4">Password</label>
            <input type="password" class="form-control" id="inputPassword4" name="password" placeholder="Password" value="$password" required>
          </div>
    </div>

	<div class="form-group">
          <label for="inputPassword4">Re-enter Password</label>
          <input type="password" class="form-control" id="inputAddress2" placeholder="Re-enter Password" name="password2" value="$password2" required>
    </div>
       
<div class="form-group">
          <label for="inputAddress2">First Name</label>
          <input type="text" class="form-control" id="inputAddress2" placeholder="First Name" name="firstName" value="$firstName" required>
        </div>
        
<div class="form-group">
          <label for="inputAddress2">Last Name</label>
          <input type="text" class="form-control" id="inputAddress2" placeholder="Last Name" name="lastName" value="$lastName" required>
        </div>
        
<div class="form-group">
            <label for="telephone">Telephone</label>
            <input type="text" class="form-control" id="telephone" placeholder="Telephone" name="telephone" value="$telephone" required>
        </div>
        
<div class="form-group">
        <label for="inputEmail4">Email</label>
            <input type="email" class="form-control" id="inputEmail4" placeholder="Email" name="email" value="$email" required>
          </div>
          
        <button type="submit" class="btn" style="background:#CD3333; color: white;">Sign Up</button>
    </form>
</div>

_END;
}

// display our message to the user:
echo $message;
echo $errors;

include "footer.php";

?>