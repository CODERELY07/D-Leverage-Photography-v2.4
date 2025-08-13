<?php 
    $title = "Admin Dashboard";
    require_once 'includes/admin-head.php';
    require_once 'includes/admin-header.php';

?>
    <main class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <h1 class="h2 font-weight-bold text-dark">Admin Dashboard</h1>
                <div class="text-muted"><?php echo date('F j, Y'); ?></div>
        </div>
            
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100 admin-box">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary bg-opacity-10 p-3 rounded mr-3">
                                    <i class="fas fa-image text-primary" style="font-size: 1.5rem;"></i>
                                </div>
                                <h4 class="h5 mb-0">Portfolio Images</h4>
                            </div>
                            <div class="my-auto py-2">
                                <p class="lead text-center mb-1">
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
                                <p class="text-muted text-center">Uploaded Images</p>
                            </div>
                            <a href="upload-portfolio.php" class="mt-auto">
                                <button class="btn btn-primary btn-block py-2">
                                    <i class="fas fa-upload mr-2"></i>Upload More
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Album Images Card -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100 admin-box">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success bg-opacity-10 p-3 rounded mr-3">
                                    <i class="fas fa-images text-success" style="font-size: 1.5rem;"></i>
                                </div>
                                <h4 class="h5 mb-0">Album Images</h4>
                            </div>
                            <div class="my-auto py-2">
                                <p class="lead text-center mb-1">
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
                                <p class="text-muted text-center">Uploaded Images</p>
                            </div>
                            <a href="albumImages.php" class="mt-auto">
                                <button class="btn btn-success btn-block py-2">
                                    <i class="fas fa-upload mr-2"></i>Upload More
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Messages Card -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100 admin-box">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-warning bg-opacity-10 p-3 rounded mr-3">
                                    <i class="fas fa-envelope text-warning" style="font-size: 1.5rem;"></i>
                                </div>
                                <h4 class="h5 mb-0">New Messages</h4>
                            </div>
                            <div class="my-auto py-2">
                                <p class="lead text-center mb-1">
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
                                <p class="text-muted text-center">Unread Messages</p>
                            </div>
                            <a href="inbox.php" class="mt-auto">
                                <button class="btn btn-warning btn-block py-2">
                                    <i class="fas fa-inbox mr-2"></i>View Now
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php 
    require_once 'includes/admin-footer.php';
?>