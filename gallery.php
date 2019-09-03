<?php

include "header.php";

$riderNum = "";
$search = "";

//Get all of the user infromation of the user logged in from the user table 
$query = "SELECT distinct photoSrc FROM photo";
$eventquery  = "SELECT * FROM event";
$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
// this query can return data ($result is an identifier):
$result = mysqli_query($connection, $query);
$eventresult = mysqli_query($connection, $eventquery);


// how many rows came back? (can only be 1 or 0 because username is the primary key in our table):
$n = mysqli_num_rows($result);
$n1 = mysqli_num_rows($eventresult);


if ($n1 > 0) {
            
    echo <<<_END

    <div class="gallerySearch">

    <div class="form-group">
    <form class="form" action="gallerysearch.php" method="POST">
    <select class="form-control" name="event" id="sel1">

_END;

    for ($i = 0; $i < $n1; $i++) {

        $row = mysqli_fetch_assoc($eventresult);
    
        $eventName = $row['eventName'];
        $eventid = $row['eventId'];
        echo $eventName;

        echo <<<_END

        <option value="$eventid">$eventid: $eventName</option>
_END;

    }
    
    echo <<<_END
  </select>
</div>
_END;
    
}

    echo <<<_END

        <input class="form-control" name="search" id="search" type="text" placeholder="Search by Rider Number" aria-label="Search" style="width:100%;">
        <br><button class="btn col-2" type="submit" style="background:#CD3333; color: white;">Search</button>
    </form>

    
    </div>
    $riderNum
    <div class="galleryPhotos">
_END;

for($i = 0; $i < $n; $i++){
    $rows = mysqli_fetch_assoc($result);
    $src = $rows['photoSrc'];
    echo "<a href='view.php?q=$src'><img src='$src' alt='' srcset='' id='riderPhoto' ></a>";
}


echo <<<_END
</div>

_END;



mysqli_close($connection);



include "footer.php";

?>