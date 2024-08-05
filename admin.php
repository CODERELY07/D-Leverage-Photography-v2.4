<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/admin.css?v=<?php echo time(); ?>">
  </head>
  <body>
    <header>
        <div class="container">
           <div class="flex">
                <div class="logo">
                <a href="admin.php">
                        <img src="image/static-img/logo.png" width="150px" alt="D'Leverage Logo"
                    />
                    </a></div>
                <div class="user-icon-con">
                   <div class="user-icon" id="user">
                    <i class="fa-solid fa-user" ></i>
                   </div>
                   
                    <div class="hide dropdown absolute card">
                        <a href="upload-portfolio.php">Portfolio Images</a><br>
                        <a href="albumImages.php">Album Images</a><br>
                        <a href="inbox.php">Inbox</a><br>
                        <a href="logout.php">Logout</a>
                    </div>
                </div>
           </div>
        </div>
    </header>
    <main>
    <div class="container mt-5">
        <div class="display-4">
            <h4>Admin Dashboard</h4>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                       <h4>Portfolio Images</h4>
                       <?php

                       ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        Album Images
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        Messages
                    </div>
                </div>
            </div>
        </div>
    </div>

    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/adminScript.js"></script>
  </body>
</html>