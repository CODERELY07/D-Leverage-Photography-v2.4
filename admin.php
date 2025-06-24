<?php 
    $title = "Admin Dashboard";
    require_once 'includes/admin-head.php';
?>
    <main>
        <div class="container mt-5">
            <div class="display-4 mb-5">
                <h4>Admin Dashboard</h4>
            </div>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card admin-box">
                        <div class="card-body">
                        <h4 class=" text-center  mb-3">Portfolio Images</h4>
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
                        <h4 class=" text-center mb-3">Album Images</h4>
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
                        <h4 class=" text-center mb-3">New Messages</h4>
                        <p class="text-center">You have 
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
<?php 
    require_once 'includes/admin-footer.php';
?>
