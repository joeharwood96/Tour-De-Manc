<?php

// execute the header script:
require_once "header.php";

// default values we show in the form:
$eventname = "";




// strings to hold any validation error messages:
$eventname_val = "";


// should we show the set profile form?:
$show_update_form = false;
// message to output to user:
$message = "";

if (!isset($_SESSION['loggedIn']))
{
	// user isn't logged in, display a message saying they must be:
	echo "You must be logged in to view this page.<br>";
}
//Information is grabbed from the form below in order to obtain the variables which were extracted from the database
elseif (isset($_POST['eventname']))
{


 
	// user just tried to update their profile

	// connect directly to our database (notice 4th argument) we need the connection for sanitisation:
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

	// if the connection fails, we need to know, so allow this exit:
	if (!$connection)
	{
		die("Connection failed: " . $mysqli_connect_error);
	}


//**New Comment**Code to sanitise inputted data

    $eventname = sanitise($_POST['eventname'], $connection);
    

	
//**New Comment**Sanitised data being posted to the variable declared
    $eventname = $_POST['eventname'];


    $eventnameUpdate = $_GET["eventname"];

	// SERVER-SIDE VALIDATION CODE MISSING:
	//**New Comment**Server side validation
 	$eventname_val=validateString($eventname,1,256);
   
	$errors = "";

	// check that all the validation tests passed before going to the database:
	if ($errors == "")
	{
		// query to get all questions from the database and update them with the new information
        $query = "SELECT * FROM event WHERE eventName='$eventname'";
		
        $query1 = "UPDATE event SET eventName = '$eventname' WHERE eventName like '$eventnameUpdate%';";


		// this query can return data ($result is an identifier):
		$result = mysqli_query($connection, $query);
        $result1 = mysqli_query($connection, $query1);

		// no data returned, we just test for true(success)/false(failure):
		if ($result)
		{
			// show a successful update message:
			$message = "<br>";
		}
		else
		{
			// show the set profile form:
			$show_update_form = true;
			// show an unsuccessful update message:
			$message = "Update failed<br>";
		}
        
        if ($result1)
		{
			// show a successful update message:
			$message = "Update successful<br>";
		}
		else
		{
			// show the set profile form:
			$show_update_form = true;
			// show an unsuccessful update message:
			$message = "Update failed<br>";
		}
	}


	// we're finished with the database, close the connection:
	mysqli_close($connection);

}
else
{
	///////////* The code below displays the question information and update fields once it has been clicked on to edit. *///////////

	// read the username from the session:
    $eventnameUpdate = $_GET["eventname"];

}




//Form to display input fields, this information will then be parsed to update the specific question.
echo <<<_END
<div class="login">
<div class="container">
<form action=update_event.php?eventname=$eventnameUpdate method="post">
  Update event:<br>
  Event Name: <input type="text" name="eventname" maxlength="256" placeholder="$eventname" required><br>
  
  <input type="submit" class ="btn btn-success" value="Update">
</form>	
</div>
</div>
_END;

// display our message to the user:
echo $message;

// finish of the HTML for this page:
require_once "footer.php";
?>