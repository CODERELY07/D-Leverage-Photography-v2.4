<?php
     session_start();
     // Check if the user is logged in
     if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
      header('Location: admin.php');
      exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $title?></title>
    <link rel="icon" type="image/x-icon" href="image/static-img/logo2.png">
    <base href="<?php echo rtrim((isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']), '/') . '/'; ?>">
    <!-- Aos -->    
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
    <!-- Bootstrap -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
      crossorigin="anonymous"
    />
    <!-- Magnific pop up css-->
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.2.0/magnific-popup.min.css"
    integrity="sha512-lvaVbvmbHhG8cmfivxLRhemYlTT60Ly9Cc35USrpi8/m+Lf/f/T8x9kEIQq47cRj1VQIFuxTxxCcvqiQeQSHjQ=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer"
  />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Sweetalert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>"/>
    <!-- <script defer src="js/script.js?<?php echo time()?>"></script> -->
  </head>
  <body class="grey">
    <div class="mobile-header fix">
      <div>
        <i class="fa-solid fa-bars" id="bar"></i>
        <div class="logo">
          <a href="index.php"
            ><img src="image/static-img/logo.png" alt="D'Leverage Logo"
            /></a>
        </div>
        <div></div>
      </div>
      <ul class="mobile-menu">
        <li><a href="portfolio.php">Portfolio</a></li>
        <li><a href="about.php">About</a></li>
        <li class="mobile-nav-photo">
          <a href="photos.php">Photos</a>
        </li>
        <li class="bg-dark text-white px-5 py-1 rounded">
          <a href="contact.php">Book Now</a>
        </li>
      </ul>
    </div>
    <header class="header fix">
      <nav class="container-fluid" data-aos-duration="1200" data-aos="fade-down">
        <ul class="menu">
          <li><a href="portfolio.php">Portfolio</a></li>
          <li><a href="about.php">About</a></li>
          <li class="logo">
            <a href="index.php"
              ><img src="image/static-img/logo.png" alt="D'Leverage Logo"
            /></a>
          </li>
          <li class="photo">
            <a href="photos.php">Photos</a>
          </li>
          <a href="contact.php">
            <li class="bg-dark text-white px-5 py-1 rounded">
              Book Now
            </li>
          </a>
        </ul>
      </nav>
    </header>