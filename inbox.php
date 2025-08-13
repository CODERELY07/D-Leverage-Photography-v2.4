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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/admin.css?v=<?php echo time(); ?>">
  </head>
  <body>
        <header class="bg-white shadow-sm py-3">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="logo">
                        <a href="admin.php">
                            <img src="image/static-img/logo.png" width="150" alt="D'Leverage Logo" class="img-fluid">
                        </a>
                    </div>
                    <div class="user-icon-con position-relative">
                        <div class="user-icon rounded-circle bg-light p-2" id="user" style="width: 40px; height: 40px; cursor: pointer;">
                            <i class="fa-solid fa-user d-flex justify-content-center align-items-center" style="font-size: 1.1rem;"></i>
                        </div>
                        
                        <div class="hide dropdown absolute card shadow" style="min-width: 180px; right: 0; top: 50px;">
                            <a href="upload-portfolio.php" class="d-block px-3 py-2 text-dark">Portfolio Images</a>
                            <a href="albumImages.php" class="d-block px-3 py-2 text-dark">Album Images</a>
                            <a href="inbox.php" class="d-block px-3 py-2 text-dark">Inbox</a>
                            <div class="dropdown-divider my-1"></div>
                            <a href="logout.php" class="d-block px-3 py-2 text-danger">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <main class="container py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">Your Clients Messages</h4>
                <div class="text-muted"><?php echo date('F j, Y'); ?></div>
            </div>
            
            <?php 
            if(isset($_SESSION['status']) && $_SESSION['status'] != ""){
                ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong><?php echo $_SESSION['status']?></strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    }
                    unset($_SESSION['status']);
                ?>
            
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0">Message Summary</h5>
                        <div class="d-flex">
                            <div class="messageNotif-container me-3">
                                <a href="inbox.php" class="text-decoration-none">
                                    <span class="badge bg-danger rounded-pill">
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
                                    <i class="fa-solid fa-envelope ms-1"></i> Unread
                                </a>
                            </div>
                            <div class="messageNotif-container">
                                <a href="inbox-read.php" class="text-decoration-none">
                                    <span class="badge bg-secondary rounded-pill">
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
                                    <i class="fa-solid fa-envelope-open ms-1"></i> Read
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>From</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $query = "SELECT * FROM contactData WHERE status='unread'";
                                    $result = $db->query($query);
                                    
                                    if ($result->num_rows > 0):
                                        while ($row = $result->fetch_assoc()):
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#messageModal<?php echo $row['id']; ?>">
                                                View Details
                                            </button>
                                            
                                            <!-- Modal -->
                                            <div class="modal fade" id="messageModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="messageModalLabel">Message Details</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <h6>Contact Information</h6>
                                                                <p class="mb-1"><strong>Name:</strong> <?php echo htmlspecialchars($row['fullname']); ?></p>
                                                                <p class="mb-1"><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                                                                <p class="mb-1"><strong>Phone:</strong> <?php echo htmlspecialchars($row['phonenumber']); ?></p>
                                                            </div>
                                                            
                                                            <div class="mb-3">
                                                                <h6>Shoot Details</h6>
                                                                <p class="mb-1"><strong>Date:</strong> <?php echo htmlspecialchars($row['shootdate']); ?></p>
                                                                <p class="mb-1"><strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?></p>
                                                                <p class="mb-1"><strong>Service:</strong> <?php echo htmlspecialchars($row['service']); ?></p>
                                                                <p class="mb-1"><strong>Session:</strong> <?php echo htmlspecialchars($row['session']); ?></p>
                                                            </div>
                                                            
                                                            <div class="mb-3">
                                                                <h6>Message</h6>
                                                                <p><?php echo htmlspecialchars($row['message']); ?></p>
                                                            </div>
                                                            
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>" class="btn btn-sm btn-outline-secondary">
                                                                    <i class="fas fa-envelope me-1"></i> Reply
                                                                </a>
                                                                
                                                                <form action="inbox-markAsRead.php" method="POST" class="mb-0">
                                                                    <input type="hidden" name="rowId" value="<?= $row['id']?>">
                                                                    <button type="submit" name="markReadBtn" class="btn btn-sm btn-primary">
                                                                        <i class="fas fa-check me-1"></i> Mark as read
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php
                                        endwhile;
                                    else:
                                ?>
                                    <tr>
                                        <td colspan="3" class="text-center py-4">No unread messages found</td>
                                    </tr>
                                <?php
                                    endif;
                                    $db->close();
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="js/adminScript.js?=<?php echo time()?>"></script>
    </body>
</html>