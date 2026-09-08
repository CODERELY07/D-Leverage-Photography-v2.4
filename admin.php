<?php
    $title = "Admin Dashboard";
    require_once 'includes/admin-head.php';
    require_once 'includes/admin-header.php';

?>
    <main class="py-5">
        <div class="container">
            <div class="page-heading">
                <div>
                    <span class="eyebrow">Overview</span>
                    <h1 class="h2 mb-0">Admin Dashboard</h1>
                </div>
                <div class="text-muted"><?php echo date('F j, Y'); ?></div>
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <a href="upload-portfolio.php" class="admin-box-link" aria-label="Manage portfolio images">
                        <div class="card border-0 shadow-sm h-100 admin-box">
                            <div class="card-body">
                                <div class="stat-icon gold">
                                    <i class="fas fa-image"></i>
                                </div>
                                <div class="admin-box-text">
                                    <h4>Portfolio Images</h4>
                                    <p class="text-muted mb-0">Portfolio Photos</p>
                                </div>
                                <p class="admin-box-count">
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
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Album Images Card -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <a href="albumImages.php" class="admin-box-link" aria-label="Manage album images">
                        <div class="card border-0 shadow-sm h-100 admin-box">
                            <div class="card-body">
                                <div class="stat-icon success">
                                    <i class="fas fa-images"></i>
                                </div>
                                <div class="admin-box-text">
                                    <h4>Album Images</h4>
                                    <p class="text-muted mb-0">Album Photos</p>
                                </div>
                                <p class="admin-box-count">
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
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Messages Card -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <a href="inbox.php" class="admin-box-link" aria-label="Open inbox">
                        <div class="card border-0 shadow-sm h-100 admin-box">
                            <div class="card-body">
                                <div class="stat-icon warning">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="admin-box-text">
                                    <h4>New Messages</h4>
                                    <p class="text-muted mb-0">Unread Messages</p>
                                </div>
                                <p class="admin-box-count">
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
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </main>
<?php
    require_once 'includes/admin-footer.php';
?>
