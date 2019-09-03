<?php

include "../connection.php";

?>



    <div class="col-6">
        <h5>Image Information</h5>
        <hr>
        <ol>
            <?php $i = 0; 
            
            $analysedImage = $_FILES['image']['name'];

            $event = "";

            $event = $_POST['event'];

        ?>
            <?php foreach (array_slice($cycleNum, 0, 1) as $key => $cycleNums): ?>
                <li><strong><?php 
                if ($cycleNum) {

                echo ($cycleNums->info()['description']);

            }
                else {
                    echo "not working";
                } ?></strong></li>             

            <?php endforeach ?>

            <?php

$number = ($cycleNums->info()['description']);

$number1 = preg_replace( '/[^0-9 -]+/', '', $number );

echo "Your chosen number is: " .$number1;

$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);


$name = $_SESSION['username'];

//$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);


//SQL INSERT STATEMENT
$insertquery = "INSERT INTO photo (userId, photoSrc, riderNum, eventId) VALUES ((SELECT userId FROM users WHERE username LIKE '$name'), 'images/riders/$analysedImage', $number1, $event)";


$insertresult = mysqli_query($connection, $insertquery);

if ($insertresult){
    echo "Your image has been successfully uploaded.";
}
else {
    echo "Your images has failed to upload.";
}

?>

            

        </ol>
</div>