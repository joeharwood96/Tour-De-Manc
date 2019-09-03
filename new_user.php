
<?php


// execute the header script:
require_once "header.php";

// default values we show in the form:
$username  = "";
$password  = "";
$usertype = "";


// strings to hold any validation error messages:
$username_val  = "";
$password_val  = "";



// should we show the signup form?:
$show_createuser_form = false;
// message to output to user:
$message = "";

if ($_SESSION['username'] == "admin") {
    
    // connect directly to our database (notice 4th argument) we need the connection for sanitisation:
    $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    
    // if the connection fails, we need to know, so allow this exit:
    if (!$connection) {
        die("Connection failed: " . $mysqli_connect_error);
    }
    
    
    if (isset($_POST['username'])) {
        
        // take copies of the credentials the user submitted, and sanitise (clean) them:
        $username  = sanitise($_POST['username'], $connection);
        $password  = sanitise($_POST['password'], $connection);
        $selected_val = $_POST['uType'];          

        // validation of the strings based on the function defined in the helper.
        $username_val  = validateString($username, 1, 16);
        $password_val  = validateString($password, 1, 16);
        


        
        // concatenate all the validation results together ($errors will only be empty if ALL the data is valid):
        $errors = $username_val . $password_val;
        
        // check that all the validation tests passed before going to the database:
        if ($errors == "") {
            
            // try to insert the new details into the users database:
            $insertquery  = "INSERT INTO users (username, password, userType) VALUES ('$username', '$password', '$selected_val');";
            $insertresult = mysqli_query($connection, $insertquery);

            echo $insertquery;
            echo 'hello';
            echo $usertype;
            

            // no data returned, we just test for true(success)/false(failure):
            if ($insertresult) {
                // show a successful signup message:
                $message = "Creation of new user was successful!<br>";
            } else {
                // show the form:
                $show_createuser_form = true;
                // show an unsuccessful signup message:
                $message              = "Create user failed, please try again<br>";
            }
            
        }
    } else {
        // validation failed, show the form again with guidance:
        $show_createuser_form = true;
        // show an unsuccessful signin message:
        $message              = "Create user failed, please check the errors shown above and try again<br>";
    }
    
    // we're finished with the database, close the connection:
    mysqli_close($connection);
    
} else {
    // just a normal visit to the page, show the signup form:
    $show_createuser_form = true;
    
}

if ($show_createuser_form) {
    // show the form that allows users to sign up
    // Note we use an HTTP POST request to avoid their password appearing in the URL:    
    echo <<<_END
    <div class="update">

<form action="new_user.php" method="post">
  Please choose a username and password:<br>
  <div class="form-group">
  Username: <input type="text" name="username" class="form-control" maxlength="16" value="$username" required> $username_val
  <br>
  </div>
  <div class="form-group">
  Password: <input type="password" name="password" class="form-control" maxlength="16" value="$password" required> $password_val
  <br>
  </div>
  User Type:<br> 

  <select name="uType">
  <option value="rider">Rider</option>
  <option value="photographer">Photographer</option>
  <option value="eventOrganiser">Event Organiser</option>
  <option value="admin">Admin</option>
  </select>

  <br><br>
  <input type="submit" class="btn btn-success" value="Submit">
</form>    
</div>
_END;
}

// display our message to the user:
echo $message;

// finish off the HTML for this page:
require_once "footer.php";

?>

