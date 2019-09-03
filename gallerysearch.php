<?php


require_once "header.php";

$riderNum = "";
$event = "";

$riderNum = $_POST['search'];

// connect directly to our database (notice 4th argument):
$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (isset($_GET['riderNum'])) {
    //$surveyid = $_SESSION['id'];
    $riderNum1 = $_GET['riderNum'];
    
    // connect directly to our database (notice 4th argument):
    $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    // connection failed, return an error message
    if (!$connection) {
        die("Connection failed: " . $mysqli_connect_error);
    }

        $searchquery  = "SELECT * FROM photo WHERE riderNum = $riderNum;";
        $searchresult = mysqli_query($connection, $searchquery);

        $a = mysqli_num_rows($searchresult);

    echo <<<_END

    <div class="gallerySearch">
    <form class="form-inline" action="gallerysearch.php" method="POST">
        <input class="form-control mr-sm-2" name="search" id="search" type="text" placeholder="Search by Rider Number" aria-label="Search">
        <button class="btn" type="submit" style="background:#CD3333; color: white;">Search</button>
    </form>
    </div>
    <div class="galleryPhotos">
_END;
        
    // no data returned, we just test for true(success)/false(failure):
    if ($a) {
        // show a successful signup message:
        for($i = 0; $i < $a; $i++){
            $rows = mysqli_fetch_assoc($searchresult);
            $src = $rows['photoSrc'];
            echo "<img src='$src' alt='' srcset='' id='riderPhoto'>";
        }
      
    // we're finished with the database, close the connection:
    mysqli_close($connection);
    
    // if we got some results then add them all into a big array:  
}
echo <<<_END
</div>

_END;

} elseif(isset($_POST['search'])) {
    //$surveyid = $_SESSION['id'];
    $riderNum = $_POST['search'];
    $event = $_POST['event'];
    
    // connect directly to our database (notice 4th argument):
    $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    // connection failed, return an error message
    if (!$connection) {
        die("Connection failed: " . $mysqli_connect_error);
    }

    if($riderNum){
        $searchquery  = "SELECT distinct photoSrc FROM photo WHERE riderNum = $riderNum;";
    } else {
        $searchquery = "SELECT distinct photoSrc FROM photo WHERE eventId = $event;";
    }
        $searchresult = mysqli_query($connection, $searchquery);

        $a = mysqli_num_rows($searchresult);

    echo <<<_END

    <div class="gallerySearch">
    <form class="form-inline" action="gallerysearch.php?riderNum=$riderNum" method="POST">
        <input class="form-control mr-sm-2" name="search" id="search" type="text" placeholder="Search by Rider Number" aria-label="Search">
        <button class="btn" type="submit" style="background:#CD3333; color: white;">Search</button>
    </form>
    </div>
    <div class="galleryPhotos">
_END;
        
    // no data returned, we just test for true(success)/false(failure):
    if ($a) {
        // show a successful signup message:
        for($i = 0; $i < $a; $i++){
            $rows = mysqli_fetch_assoc($searchresult);
            $src = $rows['photoSrc'];
            echo "<img src='$src' alt='' srcset='' id='riderPhoto'>";
        }
      
    // we're finished with the database, close the connection:
    mysqli_close($connection);
    
    // if we got some results then add them all into a big array:  
}
echo <<<_END
</div>

_END;
}
// finish of the HTML for this page:
require_once "footer.php";

?>