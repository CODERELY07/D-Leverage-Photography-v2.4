<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header('Location: index.php'); 
        exit();
    }

    require_once 'connection.php';
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
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
                        </a>
                    </div>
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
        <main class="container">
            <h4 class="mt-5  mb-4">Your Clients Messages</h4>
            <?php 
            if(isset($_SESSION['status']) && $_SESSION['status'] != ""){
                ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong><?php echo $_SESSION['status']?></strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php
                    }
                    unset($_SESSION['status']);
                ?>
            <div class="card-body" style="overflow-x:hidden">
                <table class="table table-striped">
                    <tr>
                        <td class="message-icons">
                            Messages: 
                            <div class="d-flex">
                                <div class="messageNotif-container">
                                    <span class="messageNotif-number activeMessage">
                                        <?php 
                                            $sql = "SELECT * FROM contactData WHERE status = 'unread'";
                                            $result = $db->query($sql) or die("Query Failed " . $db->error);

                                            if($result){
                                                $num_row = $result->num_rows;
                                                echo $num_row;
                                            }else{
                                                echo "Error " . $db->error;
                                            }
                                        ?>
                                    </span>
                                    <a href="inbox.php">
                                        <i class="fa-solid fa-envelope activeMessage"></i>
                                    </a>
                                </div>
                                <div class="messageNotif-container">
                                    <span class="messageNotif-number">
                                        <?php 
                                            $sql = "SELECT * FROM contactData WHERE status = 'read'";
                                            $result = $db->query($sql) or die("Query Failed " . $db->error);

                                            if($result){
                                                $num_row = $result->num_rows;
                                                echo $num_row;
                                            }else{
                                                echo "Error " . $db->error;
                                            }
                                        ?>
                                    </span>
                                    <a href="inbox-read.php">
                                        <i class="fa-solid fa-envelope-open"></i>
                                    </a>
                                </div>
                            </div>
                        </td>
                        <div>
                            <?php
                                $query = "SELECT * FROM contactData WHERE status='unread'";
                                $result = $db->query($query);
                                ?>
                                <?php if ($result->num_rows > 0): ?>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td>
                                                <div>
                                                    <p>
                                                        From: <?php echo htmlspecialchars($row['fullname']); ?><br>
                                                        Email: <?php echo htmlspecialchars($row['email']); ?><br>
                                                    </p>
                                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
                                                        View Details
                                                    </button>
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLongTitle">Unread Messages</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                        <p>
                                                            From: <?php echo htmlspecialchars($row['fullname']); ?><br>
                                                            Email: <?php echo htmlspecialchars($row['email']); ?><br>
                                                            <p class='display-6'>Details</p>
                                                            PhoneNumber: <?php echo htmlspecialchars($row['phonenumber']); ?><br>
                                                            Shoot Date: <?php echo htmlspecialchars($row['shootdate']); ?><br>
                                                            Location: <?php echo htmlspecialchars($row['location']); ?><br>
                                                            Service: <?php echo htmlspecialchars($row['service']); ?><br>
                                                            Session: <?php echo htmlspecialchars($row['session']); ?><br>
                                                            Message: <?php echo htmlspecialchars($row['message']); ?>
                                                        </p>
                                                        <div id='<?php echo htmlspecialchars($row["id"]); ?>'>
                                                            <a class="text-underline" href='mailto:<?php echo htmlspecialchars($row["email"]); ?>'>Send email</a>
                                                            <br><br>
                                                        </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <form action="inbox-markAsRead.php" method="POST">
                                                                <input type="hidden" name="rowId" value="<?= $row['id']?>">
                                                                <button type="submit" name="markReadBtn" class="btn btn-primary">Mark as read</button>
                                                            </form>
                                                        </div>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                                <?php
                                $db->close();
                                ?>
                        </div>
                    </tr>
                </table>            
            </div>
        </main>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        <script src="js/adminScript.js"></script>
    </body>
</html>