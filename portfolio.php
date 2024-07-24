<?php
  require_once 'connection.php';

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>D'Leverage Photography</title>
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
            ><img src="image/logo.png" alt="D'Leverage Logo"
          /></a>
        </div>
        <div></div>
      </div>
      <ul class="mobile-menu">
        <li><a href="portfolio.php">Portfolio</a></li>
        <li><a href="about.php">About</a></li>
        <li class="mobile-nav-photo">
          <a href="#">Photos</a>
          <div class="mobile-photos-type">
            <ul>
              <li><a href="#">Birthday Photos</a></li>
              <li><a href="#">Wedding Photos</a></li>
              <li><a href="#">Business Photos</a></li>
              <li><a href="photos.php">All Photos</a></li>
              <li></li>
            </ul>
          </div>
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
              ><img src="image/logo.png" alt="D'Leverage Logo"
            /></a>
          </li>
          <li class="photo">
            <a href="photos.php">Photos</a>
            <div class="photos-type">
              <ul>
                <li><a href="#">Birthday Photos</a></li>
                <li><a href="#">Wedding Photos</a></li>
                <li><a href="#">Business Photos</a></li>
              </ul>
            </div>
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
          <!-- <li class="buttons" data-filter="wedding">Wedding Photos</li>
          <li class="buttons" data-filter="birthday">Birthday Photos</li>
          <li class="buttons" data-filter="other">Other Photos</li> -->
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
          <!-- <a href="image/Wedding1.jpg" class="image img wedding">
            <img src="image/Wedding1.jpg" alt="" />
          </a>
          <a href="image/wedding2.jpg" class="image wedding img">
            <img src="image/wedding2.jpg" alt="" />
          </a>
          <a href="image/wedding3.JPG" class="image wedding img">
            <img src="image/wedding3.JPG" alt="" />
          </a>
          <a href="image/wedding4.JPG" class="image wedding img">
            <img src="image/wedding4.JPG" alt="" />
          </a>
          <a href="image/wedding5.JPG" class="image wedding img">
            <img src="image/wedding5.JPG" alt="" />
          </a>
          <a href="image/wedding6.JPG" class="image wedding img">
            <img src="image/wedding6.JPG" alt="" />
          </a>

          <a href="image/birthday1.JPG" class="image birthday img">
            <img src="image/birthday1.JPG" alt="" />
          </a>
          <a href="image/birthday2.JPG" class="image birthday img">
            <img src="image/birthday2.JPG" alt="" />
          </a>
          <a href="image/birthday3.JPG" class="image birthday img">
            <img src="image/birthday3.JPG" alt="" />
          </a>

          <a href="image/model1.JPG" class="image other img">
            <img src="image/model1.JPG" alt="sad" />
          </a>
          <a href="image/model2.JPG" class="image other img">
            <img src="image/model2.JPG" alt="" />
          </a>
          <a href="image/model13.JPG" class="image other img">
            <img src="image/model13.JPG" alt="" />
          </a>
          <a href="image/model14.JPG" class="image other img">
            <img src="image/model14.JPG" alt="" />
          </a> -->
        </div>
      </div>
    </main>
    <footer>
      <div class="footer-wrapper">
        <div class="socials">
          <a href="#">
            <i class="fa-brands fa-x-twitter"></i>
          </a>
          <a href="https://www.instagram.com/dleveragephoto" target="_blank">
            <i class="fa-brands fa-instagram"></i>
          </a>
          <a href="#">
            <i class="fa-brands fa-youtube"></i>
          </a>
          <a href="https://www.facebook.com/dleveragephoto" target="_blank">
            <i class="fa-brands fa-facebook"></i>
          </a>
        </div>
        <div class="menu">
            <ul>
                <li class="mb-3"><h5>Menu</h5></li>
                <li><a href="portfolio.html">Portfolio</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="photos.html">Photos</a></li>
            </ul>
        </div>
        <div class="about">
            <h5>About</h5>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Pariatur quasi explicabo iure amet, ipsum dolores eos quam magni atque ea exercitationem, optio unde, ipsam repellat. Alias, et minima nihil esse est laboriosam suscipit facilis excepturi sed dolor ipsam praesentium delectus consequuntur vero, similique accusamus tempora natus veniam ut voluptates omnis.</p>
        </div>
        <div class="footer-contact">
            <p>For more Inquiry <br>Please contact us</p>
            <button type="button">Contact</button>
        </div>
      </div>
      <p class="text-center text-white m-0 p-3">@2024 | ALL RIGHTS RESERVED | D’LEVERAGE</p>
    </footer>
    <!-- bootrap -->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"
    ></script>
    <!-- AOS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- Jquery -->
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
      integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    ></script>
    <!-- Magnific pop up -->
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.2.0/jquery.magnific-popup.min.js"
      integrity="sha512-fCRpXk4VumjVNtE0j+OyOqzPxF1eZwacU3kN3SsznRPWHgMTSSo7INc8aY03KQDBWztuMo5KjKzCFXI/a5rVYQ=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    ></script>
    <script>
      // Aos execute
      AOS.init();

      $(document).ready(function () {
        // Initially show all images and activate the 'all' filter
        $(".all").addClass("active");
        $(".image").show(400);

        // Initialize Magnific Popup for the gallery
        $(".gallery").magnificPopup({
          delegate: "a",
          type: "image",
          gallery: {
            enabled: true,
          },
        });

        // Click event handler for filter buttons
        $(".buttons").click(function () {
          // Remove 'active' class from all buttons and add it to the clicked button
          $(this).addClass("active").siblings().removeClass("active");

          // Get the data-filter value of the clicked button
          var filter = $(this).attr("data-filter");

          // Hide all images initially
          $(".image").hide();

          if (filter === "all") {
            // Show all images if 'all' filter is selected
            $(".image").show(400);
          } else {
            // Show only images matching the current filter
            $(".image." + filter).show(400);
          }
          // Update Magnific Popup with filtered images
          $(".gallery").magnificPopup({
            delegate: "a." + filter,
            type: "image",
            gallery: {
              enabled: true,
            },
          });
        });
      });
    </script>
  </body>
</html>
