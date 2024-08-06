<?php
    require_once 'connection.php';

    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header('Location: adminLogin.php'); 
        exit();
    }
?>
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
        <div class="display-4 mb-5">
            <h4>Admin Dashboard</h4>
        </div>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card admin-box">
                    <div class="card-body">
                       <h4 class=" text-center display-5 mb-3">Portfolio Images</h4>
                       <p class="text-center">You have 
                       <?php 
                            $sql = "SELECT * FROM image";
                            $result = $db->query($sql) or die("Query Failed " . $db->error);

                            if($result){
                                $num_row = $result->num_rows;
                                echo $num_row;
                            }else{
                                echo "Error " . $db->error;
                            }
                        ?>
                        Uploaded Images
                        </p>
                        <a href="upload-portfolio.php">
                            <button class="mx-auto d-block btn-dark btn">Upload More</button>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card admin-box">
                    <div class="card-body">
                       <h4 class=" text-center display-5 mb-3">Album Images</h4>
                       <p class="text-center">You have 
                       <?php 
                            $sql = "SELECT * FROM album";
                            $result = $db->query($sql) or die("Query Failed " . $db->error);

                            if($result){
                                $num_row = $result->num_rows;
                                echo $num_row;
                            }else{
                                echo "Error " . $db->error;
                            }
                        ?>
                        Uploaded Images
                        </p>
                        <a href="albumImages.php">
                            <button class="mx-auto d-block btn-dark btn">Upload More</button>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card admin-box">
                    <div class="card-body">
                       <h4 class=" text-center display-5 mb-3">New Messages</h4>
                       <p class="text-center">You have 
                       <?php 
                            $sql = "SELECT * FROM contactdata WHERE status = 'unread'";
                            $result = $db->query($sql) or die("Query Failed " . $db->error);

                            if($result){
                                $num_row = $result->num_rows;
                                echo $num_row;
                            }else{
                                echo "Error " . $db->error;
                            }
                        ?>
                        Unread Messgaes
                        </p>
                        <a href="inbox.php">
                            <button class="mx-auto d-block btn-dark btn">View Now</button>
                        </a>
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