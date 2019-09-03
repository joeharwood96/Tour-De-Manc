<?php

session_start();

require "vendor/autoload.php";


use Google\Cloud\Vision\VisionClient;
$vision = new VisionClient(['keyFile' => json_decode(file_get_contents("key.json"), true)]);

$familyPhotoResource = fopen($_FILES['image']['tmp_name'], 'r');

$image = $vision->image($familyPhotoResource, 
    ['FACE_DETECTION',
     'LABEL_DETECTION',
     'IMAGE_PROPERTIES',
     'DOCUMENT_TEXT_DETECTION'
    ]);
$result = $vision->annotate($image);

if ($result) {
    $imagetoken = random_int(1111111, 999999999);
    //move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/feed/' . $imagetoken . ".jpg");
} else {
    header("location: index.php");
    die();
}


$faces = $result->faces();
$labels = $result->labels();
$cycleNum = $result->text();
$properties = $result->imageProperties();
//$cycleNum = $result->fullText();

$analysedImage = "";

$analysedImage = $_FILES['image']['name'];


?>

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

    <div class="container-fluid" style="max-width: 1080px; margin-bottom: 5%;">
        <div class="row">
            <div class="col-md-12" style="margin: auto; background: white; padding: 20px; box-shadow: 10px 10px 5px #888">
                <div class="panel-heading">
                    <h2>Google Cloud Vision API</h2>
                </div>
                <hr>
                <div class="row" style="box-shadow: none;">
                    <div class="col-md-4" style="text-align: center;">
                        <img class="img-thumbnail" src="<?php echo "../images/working images/" . $analysedImage;?>" alt="Analysed Image"> 
                    </div>
                    <div class="col-md-8 border">
                        <ul class="nav nav-pills nav-fill mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <h2 style="color: #CD3333;">Cycle Number</h2>
                            </li>
                        </ul>
                        <hr>
                        <div class="tab-content" id="pills-tabContent">

                            <div class="tab-pane fade show active" id="pills-face" role="tabpanel" aria-labelledby="pills-face-tab">
                            <div class="tab-pane fade show" id="pills-cycleNum" role="tabpanel" aria-labelledby="pills-cycleNum-tab">
                                
                                <?php include "cycleNum.php" ;?>
                                   
                            </div class="col-mid-4">
                            <a href="index.php" class="btn btn-success">Add Another Image</a> 
                            <br><br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<div class="footer">

<p>© 2019 TeamDeManMet.</p>

</div>


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
</body>
</html>

