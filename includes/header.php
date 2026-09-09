<?php
     session_start();

     if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
      header('Location: admin/dashboard.php');
      exit();
    }
    $current_page = basename($_SERVER['SCRIPT_NAME']);
    $is_active = function (...$pages) use ($current_page) {
        return in_array($current_page, $pages, true) ? 'is-active' : '';
    };
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Dleverage Photography &mdash; Montreal-based wedding, portrait &amp; event photographer, available worldwide." />
    <meta name="theme-color" content="#faf9f5" />
    <title><?= $title?></title>
    <link rel="icon" type="image/x-icon" href="image/static-img/logo2.png">
    <?php

        $baseDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
        $baseHref = ($isHttps ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . rtrim($baseDir, '/') . '/';
    ?>
    <base href="<?php echo $baseHref; ?>">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />

    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
      crossorigin="anonymous"
    />

    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.2.0/magnific-popup.min.css"
    integrity="sha512-lvaVbvmbHhG8cmfivxLRhemYlTT60Ly9Cc35USrpi8/m+Lf/f/T8x9kEIQq47cRj1VQIFuxTxxCcvqiQeQSHjQ=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer"
  />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>"/>
  </head>
  <body class="grey">
    <a class="skip-link" href="#main-content">Skip to content</a>
    <div class="mobile-header fix">
      <div>
        <i class="fa-solid fa-bars" id="bar" role="button" tabindex="0" aria-label="Open menu" aria-expanded="false" aria-controls="mobileMenu"></i>
        <div class="logo">
          <a href="index.php"
            ><img src="image/static-img/logo-trim.png" alt="D'Leverage Logo"
            /></a>
        </div>
        <div></div>
      </div>
      <div class="mobile-menu-backdrop"></div>
      <ul class="mobile-menu" id="mobileMenu">
        <li><a href="portfolio.php" class="<?= $is_active('portfolio.php') ?>">Portfolio</a></li>
        <li><a href="about.php" class="<?= $is_active('about.php') ?>">About</a></li>
        <li class="mobile-nav-photo">
          <a href="photos.php" class="<?= $is_active('photos.php', 'photos-category.php', 'view-album.php') ?>">Photos</a>
        </li>
        <li class="bg-dark text-white px-5 py-1 rounded">
          <a href="contact.php">Book Now</a>
        </li>
      </ul>
    </div>
    <header class="header fix">
      <nav class="container-fluid" data-aos-duration="1200" data-aos="fade-down" aria-label="Primary">
        <ul class="menu">
          <li><a href="portfolio.php" class="<?= $is_active('portfolio.php') ?>">Portfolio</a></li>
          <li><a href="about.php" class="<?= $is_active('about.php') ?>">About</a></li>
          <li class="logo">
            <a href="index.php"
              ><img src="image/static-img/logo-trim.png" alt="D'Leverage Logo"
            /></a>
          </li>
          <li class="photo">
            <a href="photos.php" class="<?= $is_active('photos.php', 'photos-category.php', 'view-album.php') ?>">Photos</a>
          </li>
          <li class="nav-cta bg-dark text-white px-5 py-1 rounded">
            <a href="contact.php">Book Now</a>
          </li>
        </ul>
      </nav>
    </header>
    <div id="main-content"></div>