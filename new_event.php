
<?php


// execute the header script:
require_once "header.php";

// default values we show in the form:
$eventName  = "";



// strings to hold any validation error messages:
$eventName_val  = "";



// should we show the signup form?:
$show_createuser_form = false;
// message to output to user:
$message = "";

if ($_SESSION['username'] == "eventorganiser") {
    
    // connect directly to our database (notice 4th argument) we need the connection for sanitisation:
    $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    
    // if the connection fails, we need to know, so allow this exit:
    if (!$connection) {
        die("Connection failed: " . $mysqli_connect_error);
    }
    
    
    if (isset($_POST['eventName'])) {
        
        // take copies of the credentials the user submitted, and sanitise (clean) them:
        $eventName  = sanitise($_POST['eventName'], $connection);


        
        
        
        
        // validation of the strings based on the function defined in the helper.
        $eventName_val  = validateString($eventName, 1, 64);


        
        // concatenate all the validation results together ($errors will only be empty if ALL the data is valid):
        $errors = $eventName_val;
        
        // check that all the validation tests passed before going to the database:
        if ($errors == "") {
            
            // try to insert the new details into the users database:
            $insertquery  = "INSERT INTO event (eventName) VALUES ('$eventName');";
            $insertresult = mysqli_query($connection, $insertquery);
            
            // no data returned, we just test for true(success)/false(failure):
            if ($insertresult) {
                // show a successful signup message:
                $message = "Creation of new event was successful!<br>";
            } else {
                // show the form:
                $show_createuser_form = true;
                // show an unsuccessful signup message:
                $message = "Create Event failed, please try again<br>";
            }
            
        }
    } else {
        // validation failed, show the form again with guidance:
        $show_createuser_form = true;
       
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

<form action="new_event.php" method="post">
  Please choose an Event Name<br>
  <div class="form-group">
  Event Name: <input type="text" name="eventName" class="form-control" maxlength="16" value="$eventName" required> $eventName_val
  <br>
  </div>
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

