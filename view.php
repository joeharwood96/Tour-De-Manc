<?php

include "header.php";

$message="";

if(isset($_GET['q'])){
	$photo = $_GET['q'];
	
	$query = "SELECT p.photoSrc, p.eventId, e.eventName, p.userId FROM photo p INNER JOIN event e on p.eventId = e.eventId WHERE p.photoSrc = '$photo';";

	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	$result = mysqli_query($connection, $query);
	$rowCount = mysqli_num_rows($result);
	
	
	if($rowCount > 0){
	$row = mysqli_fetch_assoc($result);
	$src = $row['photoSrc'];

	echo"<div class='login'>";
	echo "<div class='container'><img src='$src' alt='' srcset='' id='riderPhoto'></div>";
    echo "<div class='container'><p style='font-family:russo one'>Photo taken during the ".$row['eventName'].".</p></div>";
	//class was 'card'
	echo <<<_END
	
	<div class="container">
	<form class="form-inline" action="view.php?q=$photo" method="POST">
        <input class="form-control mr-sm-2" name="tagPhoto" id="tagPhoto" type="text" placeholder="Tag yourself in this photo" style="width: 50%;">
		<input class="form-control mr-sm-2" name="eventId" id="eventId" type="text" value="{$row['eventId']}" hidden>
		<input class="form-control mr-sm-2" name="userId" id="userId" type="text" value="{$row['userId']}" hidden>
		<input class="form-control mr-sm-2" name="eventName" id="eventName" type="text" value="{$row['eventName']}" hidden>
        <button class="btn" type="submit" style="background:#CD3333; color: white;">Add Tag!</button>
    </form>
	</div>
	</div>
_END;
	}
}

if(isset($_POST['tagPhoto'])){
	$photo = $_GET['q'];
	$tag = $_POST['tagPhoto'];
	$eventName = $_POST['eventName'];
	$eventId = $_POST['eventId'];
	$userId = $_POST['userId'];
	$tag = sanitise($tag,$connection);
	$tag_val = validateInt($tag,1,999999);//IMPORTANT - TO DO
	
	if($tag_val == ""){
		$query = "INSERT INTO photo (photoSrc, userId, riderNum, eventId) VALUES ('$photo',$userId,$tag,$eventId);";
		
		$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
		$result = mysqli_query($connection, $query);
		if ($result){
			// show a successful update message:
			$message = "<div class='message'>You are now linked to this photo: <a href='gallery.php'>Click Here</a> to return to the Gallery</div>" ;
		} 
		else{
			// show the set profile form:
			$showForm = true;
			// show an unsuccessful update message:
			$message = "<div class='message'>Unable to tag you to this photo</div>";
		}
	}
	else{
		$message = "<div class='message'>Invalid Tag: Should be your rider num</div>";
	}
}


mysqli_close($connection);

echo $message;

include "footer.php";

?>