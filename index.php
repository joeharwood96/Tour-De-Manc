<?php

require_once "header.php";


echo <<<_END

<div id="welcome">
    <h1 class="animate-pop-in" id="welcomeHeader">Welcome to TourDeManc Photo Booth</h1>
</div>


    <h1 class="aboutTitle">About</h1>
    <div id="about">

    <p>The TourDeManc Photo Booth allows you to view all your race day images in one convenient location. 
      Just login or signup to view all your images and search for your specific race by searching for your 
      race number. Through the power of Microsoft azure your images are scanned and categorised by the race 
      number visible on your bib.</p>

    <p>The Photo Booth has three primary users:</p>

    <div class="container">
        <div class="row">
          <div class="col" id="col">
            <img src="images/rider.jpg" alt="" srcset="" height="200" width="250" style="padding: 2%">
            <h2>Rider</h2>
            <button type="button" class="btn" id="aboutBtn" style="background:#CD3333; color: white;" data-toggle="collapse" data-target="#riderInfo" aria-expanded="false" aria-controls="riderInfo">Read More</button>
          </div>
          <div class="col" id="col">
              <img src="images/photographer.jpg" alt="" srcset="" height="200" width="250" style="padding: 2%">
            <h2>Photographer</h2>
            <button type="button" class="btn" id="aboutBtn" style="background:#CD3333; color: white;" data-toggle="collapse" data-target="#photoInfo" aria-expanded="false" aria-controls="collapseExample">Read More</button>
          </div>
          <div class="col" id="col">
              <img src="images/eventManger.jpg" alt="" srcset="" height="200" width="250" style="padding: 2%">
            <h2>Event Manager</h2>
            <button type="button" class="btn" id="aboutBtn" style="background:#CD3333; color: white;" data-toggle="collapse" data-target="#eventInfo" aria-expanded="false" aria-controls="collapseExample">Read More</button>
          </div>
        </div>
    
      
        <div class="row" id="rowinfo">
            <div class="col">
              <div class="collapse multi-collapse" id="riderInfo">
                <div class="card card-body">
                  Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.
                </div>
              </div>
            </div>
            <div class="col">
              <div class="collapse multi-collapse" id="photoInfo">
                <div class="card card-body">
                  Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.
                </div>
              </div>
            </div>
            <div class="col">
                <div class="collapse multi-collapse" id="eventInfo">
                  <div class="card card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.
                  </div>
                </div>
              </div>
          </div>
    </div>
  </div>



_END;

include "footer.php";

?>