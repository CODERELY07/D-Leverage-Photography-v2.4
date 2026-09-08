<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require_once 'connection.php';
    session_start();
    // Check if the user is logged in
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header('Location: index.php'); 
        exit();
    }
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title?></title>
    <meta name="theme-color" content="#faf9f5">
    <link rel="icon" type="image/x-icon" href="image/static-img/logo2.png">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/admin.css?v=<?php echo time(); ?>">
    <?php
        // dirname() returns "\" (not "/") for a root-level script on Windows,
        // which rtrim(..., '/') doesn't strip. Browsers then treat that stray
        // backslash as another path separator, doubling the slash after the
        // host (http://host\/ -> http://host//). Normalize to "/" first.
        $baseDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        $baseHref = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . rtrim($baseDir, '/') . '/';
    ?>
    <base href="<?php echo $baseHref; ?>">
  </head>
  <body>
    