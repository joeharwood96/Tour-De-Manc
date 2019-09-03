<?php

$message = "";

include "header.php";

if (!isset($_SESSION['loggedIn']))
{
	// user isn't logged in, display a message saying they must be:
	echo "You must be logged in to view this page.<br>";
}
if(isset($_SESSION['username'])){

    //Set the username variable to the username session
    $username = $_SESSION["username"];
    //Get all of the user infromation of the user logged in from the user table 
    $query = "SELECT * FROM users WHERE username='$username'";
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	// this query can return data ($result is an identifier):
	$result = mysqli_query($connection, $query);
	
	// how many rows came back? (can only be 1 or 0 because username is the primary key in our table):
	$n = mysqli_num_rows($result);
		
	// if there was a match then extract their profile data:
    if ($n > 0)
    {
        $rows = mysqli_fetch_assoc($result);

        $userID = $rows['userId'];
        $firstname = $rows['firstName'];
        $lastname = $rows['lastName'];
        $userType = $rows['username'];
        $email = $rows['email'];
        $postcode = $rows['postcode'];
        $userType = $rows['userType'];

    }

    mysqli_close($connection);

    echo <<<_END

    <div class = "profile">
    <div class="card">
        <h2 class="profileText">$username</h2>
        Email: <p>$email</p>
        Postcode: <p>$postcode</p>
        First Name: <p>$firstname</p>
        Last Name: <p>$lastname</p>
        User Type: <p>$userType</p>

        <a href="update_user.php" title="Update User Profile" class="btn" style="background:#CD3333; color: white; margin-bottom:2%;">Update Profile</a>
        <a href="update_user2.php" title="Update User Password" class="btn" style="background:#CD3333; color: white; margin-bottom:15%;">Update Password</a>
    </div>
_END;
 
/*if($userType == 'rider'){

    //Get all of the user infromation of the user logged in from the user table 
    $query = "SELECT * FROM photo WHERE userid = $userID";
    $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    // this query can return data ($result is an identifier):
    $result = mysqli_query($connection, $query);

    // how many rows came back? (can only be 1 or 0 because username is the primary key in our table):
    $n = mysqli_num_rows($result);
        
    mysqli_close($connection);

    echo <<<_END

    <div class="profilePhotos">

_END;

    for($i = 0; $i < $n; $i++){
        $rows = mysqli_fetch_assoc($result);
        $src = $rows['photoSrc'];
    echo "<img src='$src' alt='' srcset='' id='riderPhoto'>";
    }

    echo <<<_END

    </div>

_END;
}*/
if ($userType == 'admin') {
   //Check if the delete button has been pressed
		if(isset($_POST['delete'])){


			$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
			//Get the username from the delete form 
			$username = $_POST['delete'];
			//Delete user from answers table 
			$query = "DELETE FROM photo WHERE username = '$username'";

			$result = mysqli_query($connection, $query);
			//Delete user from options table
			$query = "DELETE FROM users WHERE username = '$username'";
    
            // this query can return data ($result is an identifier):
            $result = mysqli_query($connection, $query);

            if ($result) 
            {
                // show a successful delete message:
                $message = "User deleted" ;
            } 
            else
            {
                // show an unsuccessful delete message:
                $message = "Delete failed";
            }
		}
		
		$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
		//Get the user from the session
		$username = $_SESSION['username'];
		//Find the username, firstname, lastname from users where the user is not an admin 
		$query = "SELECT username, firstname, lastname, usertype FROM users WHERE username != '$username'";

    	// this query can return data ($result is an identifier):
    	$result = mysqli_query($connection, $query);

    	// how many rows came back?:
        $n = mysqli_num_rows($result);
        echo "<div class='userTool'>";
		echo "<h2>Admin Tools</h2>";
		echo "<br>";
		echo "<table cellpadding='2' cellspacing='2' class='table'>";
        echo "<tr><th>Username</th><th>First Name</th><th>Last Name</th><th>User Type</th></tr>";
        // loop over all rows, adding them into the table:
        for ($i=0; $i<$n; $i++)
        {
			
            // fetch one row as an associative array (elements named after columns):
			$row = mysqli_fetch_assoc($result);
			$username = $row['username'];
			$firstname = $row['firstname'];
            $lastname = $row['lastname'];
            $usertype = $row['usertype'];
            // add it as a row in our table:
            echo "<tr>";
			echo "<td>{$username}</td>";
			echo "<td>{$firstname}</td>";
            echo "<td>{$lastname}</td>";
            echo "<td>{$usertype}</td>";
			//Add buttons to update and delete users
			echo <<<_END
			<td>
				<a href="update_user.php?username=$username" title="Update User Profile" class="btn btn-warning">Update</a>
				<form action="" method="POST">
					<button type="submit" name="delete" value="$username" id="delete" class="btn btn-danger" onClick="showAlert(event)">Delete</button>
				</form>
			<td>
_END;
            echo "</tr>";
        }
        echo "</table>";
        echo <<<_END
        <td>
            <a href="new_user.php" title="Add a new account" class="btn btn-success">Add a new account</a>
        <td>
_END;

        echo "</div>";
		//Javascript that gives an alert asking if the user wants to delete a survey
		echo <<<_END
        <script>
        function showAlert(e) {
            var txt;
            var r = confirm("Are you sure you want to delete user?");
            if (r == true) {
                txt = "You pressed OK!";
                return true;
            } else {
                txt = "You pressed Cancel!";
                e.preventDefault();
                return false;
            }
        }
        </script>

_END;

}

elseif ($userType == 'photographer') {
    echo <<<_END
    <div class="upload">
        <h2>Upload Photos</h2>

        <div class="input-group mb-3">
            <a href="API/index.php" title="Add a new account" class="btn btn-success">Upload Image</a>
        </div>

        <h2>My Photos</h2>
    </div>
_END;
}

elseif ($userType == 'eventOrganiser') {
    //Check if the delete button has been pressed
		if(isset($_POST['delete'])){


			$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
			//Get the username from the delete form 
            $eventName = $_POST['delete'];
			//Delete user from answers table 
			$query = "DELETE FROM photo WHERE eventId = (select eventId from event where eventName = '$eventName')";
    
            // this query can return data ($result is an identifier):
            $result = mysqli_query($connection, $query);

            //Delete user from answers table 
			$query = "DELETE FROM event WHERE eventName = '$eventName'";
    
            // this query can return data ($result is an identifier):
            $result = mysqli_query($connection, $query);

            if ($result) 
            {
                // show a successful delete message:
                $message = "Event deleted" ;
            } 
            else
            {
                // show an unsuccessful delete message:
                $message = "Delete failed";
            }
		}
		
		$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
		//Get the user from the session
		$username = $_SESSION['username'];
		//Find the username, firstname, lastname from users where the user is not an admin 
		$query = "SELECT eventName FROM event;";

    	// this query can return data ($result is an identifier):
    	$result = mysqli_query($connection, $query);

    	// how many rows came back?:
        $n = mysqli_num_rows($result);
        echo "<div class='userTool'>";
		echo "<h2>Event Organiser Tools</h2>";
		echo "<br>";
		echo "<table cellpadding='2' cellspacing='2' class='table'>";
        echo "<tr><th>Event</th></tr>";
        // loop over all rows, adding them into the table:
        for ($i=0; $i<$n; $i++)
        {
			
            // fetch one row as an associative array (elements named after columns):
			$row = mysqli_fetch_assoc($result);
			$event = $row['eventName'];
            // add it as a row in our table:
            echo "<tr>";
			echo "<td>{$event}</td>";
			//Add buttons to update and delete users
			echo <<<_END
			<td>
				<a href="update_event.php?eventname=$event" class="btn btn-warning">Update</a>
				<form action="" method="POST">
					<button type="submit" name="delete" value="$event" id="delete" class="btn btn-danger" onClick="showAlert(event)">Delete</button>
				</form>
			<td>
_END;
            echo "</tr>";
        }
        echo "</table>";

        echo <<<_END
        <td>
            <a href="new_event.php" title="Create an event" class="btn btn-success">Create an event</a>
        <td>
_END;

        echo "</div>";
		//Javascript that gives an alert asking if the user wants to delete a survey
		echo <<<_END
        <script>
        function showAlert(e) {
            var txt;
            var r = confirm("Are you sure you want to delete user?");
            if (r == true) {
                txt = "You pressed OK!";
                return true;
            } else {
                txt = "You pressed Cancel!";
                e.preventDefault();
                return false;
            }
        }
        </script>

_END;
}



}

echo "$message";
include "footer.php";

?>