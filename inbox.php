<?php
    $title = "Inbox | Admin";
    require_once 'includes/admin-head.php';
    require_once 'includes/admin-header.php';
?>
        <main class="container py-4">
            <div class="page-heading">
                <div>
                    <span class="eyebrow">Inbox</span>
                    <h1 class="h3 mb-0">Your Clients Messages</h1>
                </div>
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
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
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
                            <thead>
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
                                        <td data-label="From"><?php echo htmlspecialchars($row['fullname']); ?></td>
                                        <td data-label="Email"><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td data-label="Actions">
                                            <button type="button" class="icon-btn icon-view" data-tooltip="View details" aria-label="View message from <?php echo htmlspecialchars($row['fullname']); ?>" data-bs-toggle="modal" data-bs-target="#messageModal<?php echo $row['id']; ?>">
                                                <i class="fas fa-eye"></i>
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
                                                            
                                                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
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
<?php
    require_once 'includes/admin-footer.php';
?>