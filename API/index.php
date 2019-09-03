<?php

include "../connection.php";

echo <<<_END

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <link rel="stylesheet" href="../style.css">
    <title>TourDeManc</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark" style="background:rgb(26, 25, 25);">
<a href="https://www.tourdemanc.co.uk/"><img src="../images/logo.png" alt="Logo for Tour De Manc. A click of this logo will return you to the original website"></a>
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
      
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="../index.php">Home<span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../profile.php">Profile</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../gallery.php">Gallery</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../signout.php">Log Out</a>
        </li>
      </ul>
    </div>
</nav>


    <br><br><br>
        <div class="col-md-6 offset-md-3" style="margin: auto; background: white; padding: 20px; box-shadow: 10px 10px 5px #888">
            <div class="panel-heading">
                <h2>Google Cloud Vision API</h2>
            </div>
            <hr>
            <form action="check.php" method="post" enctype="multipart/form-data">
                <input type="file" name="image" accept="image/*" class="form-control">
_END;
$eventquery  = "SELECT * FROM event";
$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

$eventresult = mysqli_query($connection, $eventquery);
$n1 = mysqli_num_rows($eventresult);

        echo <<<_END

        <div class="gallerySearch">

        <div class="form-group">
        <form class="form-inline" action="gallerysearch.php" method="POST">
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
            echo <<<_END
                <br>
                <button type="submit" style="border-radius: 0px; background:#CD3333; color: white;" class="btn btn-lg btn-block">Analyse Image</button>
            </form>
    </div>

<div class="footer">

<p>© 2019 TeamDeManMet.</p>

</div>


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
</body>
</html>

_END;


?>