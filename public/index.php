<?php
session_start();

include_once "config.php";

<?php include "header.php";?>
<div class="video-container position-relative w-100 overflow-hidden">
    <video autoplay muted loop id="background-video" class="w-100">
        <source src="images/video2.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="video-overlay"></div>
    <div class="overlay-text position-absolute w-100 h-100 d-flex justify-content-center align-items-center">
        <div class="text-content">
            <h1 class="text-uppercase text-center text-white display-4">High Street Gym</h1>
            <p class="text-white text-center">
                It's more than just physical exercise. We train for the mind, too. Small, capped classes focused on form and long-terms health and wellness. 
            </p>
        </div>
    </div>
</div>
<main class="warm-background py-5 ">
    <h2 class="text-center font-bold">Your Local South Brisbane Gym</h2>
    <h4 class="text-center mx-auto" style="width: 80%">Our training method blends strength and conditioning, focusing on developing power and fortifying the body for long-term wellness</h4>
    <div class="text-center mt-5">
        <button class="btn btn-primary btn-lg">MEET THE TRAINERS</button>
    </div>
</main>
	
<?php include "footer.php"; ?>