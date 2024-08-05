<?php
  require_once 'connection.php';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Portfolio | D'Leverage Photography</title>
    <link rel="icon" type="image/x-icon" href="image/static-img/logo2.png">
    <!-- Aos -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
    <!-- Bootstrap -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
      integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <link rel="stylesheet" href="css/style.css" />
    <!-- Magnific pop up css-->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.2.0/magnific-popup.min.css"
      integrity="sha512-lvaVbvmbHhG8cmfivxLRhemYlTT60Ly9Cc35USrpi8/m+Lf/f/T8x9kEIQq47cRj1VQIFuxTxxCcvqiQeQSHjQ=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <script defer src="js/script.js"></script>
  </head>
  <body class="grey">
    <div class="mobile-header fix">
      <div>
        <i class="fa-solid fa-bars" id="bar"></i>
        <div class="logo">
          <a href="index.html"
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
          <!-- <div class="mobile-photos-type">
            <ul>
              <li><a href="#">Birthday Photos</a></li>
              <li><a href="#">Wedding Photos</a></li>
              <li><a href="#">Business Photos</a></li>
              <li><a href="photos.php">All Photos</a></li>
              <li></li>
            </ul>
          </div> -->
        </li>
        <li class="bg-dark text-white px-5 py-1 rounded">
          <a href="contact.php">Book Now</a>
        </li>
      </ul>
    </div>
    <header class="header fix">
      <nav
        class="container-fluid"
        data-aos-duration="1200"
        data-aos="fade-down"
      >
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
            <!-- <div class="photos-type">
              <ul>
                <li><a href="#">Birthday Photos</a></li>
                <li><a href="#">Wedding Photos</a></li>
                <li><a href="#">Business Photos</a></li>
              </ul>
            </div> -->
          </li>
          <li class="bg-dark text-white px-5 py-1 rounded">
            <a href="contact.php">Book Now</a>
          </li>
        </ul>
      </nav>
    </header>
    <div style="margin-top: 200px"></div>
    <main>
      <div class="gallery">
        <ul class="controls">          
          <li class="buttons active" data-filter="all">All</li>
          
         <?php
            $query = "SELECT DISTINCT category FROM image";
            $result = $db->query($query);

            while($data = $result->fetch_assoc()){
          ?>
          <li class="buttons" data-filter="<?php echo $data['category'];?>"><?php echo $data['category']; ?> Photos</li>
          <?php
          }
          ?>
        </ul>

        <div class="image-container">
         <?php
            $query = "SELECT * FROM image";
            $result = $db->query($query);

            while($data = $result->fetch_assoc()){
          ?>
          <a href="./image/<?php echo $data['filename']; ?>" class="image <?php echo $data['category']; ?> img">
            <img src="./image/<?php echo $data['filename']; ?>" alt="<?php echo $data['filename']; ?>">
          </a>
          <?php
          }
          ?>
        </div>
      </div>
    </main>
<?php
  require_once 'includes/footer-jquery.php';
?>