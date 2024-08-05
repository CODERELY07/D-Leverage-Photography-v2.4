<?php
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header('Location: adminLogin.php'); 
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
            <h4 class="display-5 mt-5  mb-4">Your Clients Messages</h4>
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
                        <td>Messages:</td>
                        <?php
                            $query = "SELECT * FROM contactData";
                            $result = $db->query($query);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>
                                        <div>
                                            <p>
                                                From: {$row['fullname']}<br>
                                                Email: {$row['email']}<br>
                                                <p class='display-6 '>Details</p>
                                                PhoneNumber: {$row['phonenumber']}<br>
                                                Shoot Date: {$row['shootdate']}<br>
                                           
                                                Location: {$row['location']}<br>
                                                Service:  {$row['service']}<br>
                                            
                                                Session: {$row['session']}<br>
                                                Message:  {$row['message']}
                                            </p>
                                            <div id='{$row["id"]}'> 
                                                    <a href='mailto:{$row["email"]}'>Send email</a>
                                                <br><br>
                                                <button class='btn btn-danger deleteMessage'>Delete</delete>
                                            </div>
                                        </div>
                                    </td>";
                                    echo "</tr>";
                                }
                            }
                            $db->close();
                        ?>
                    </tr>
                </table>            
            </div>
        </main>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        <script src="js/adminScript.js"></script>
        <script>
              // DElete messages
        document.querySelectorAll('.deleteMessage').forEach(function(btn){
            btn.addEventListener("click",function(e){
            
            if (confirm("Are you sure you want to delete this Message?")) {
                const div = e.target.closest('div');
                if (div) {
                    const divId =div.id;
                    const xhr = new XMLHttpRequest();
                    xhr.open("POST", "deleteMessages.php", true);
                    
                    xhr.onload = function() {
                        if (xhr.status >= 200 && xhr.status < 300) {
                            // if(this.responseText == 1){
                            //     Swal.fire("Deleted Successfully!");
                            // }else{
                            //     Swal.fire({
                            //     icon: "error",
                            //     text: "Something went wrong!",
                            //     });
                            // }
                            location.reload(true);
                        } else {
                            console.error("Error deleting message: ", xhr.statusText);
                        }
                    };
                    xhr.onerror = function() {
                        console.error("Error deleting message: ", xhr.statusText);
                    };
                    const data = `id=${encodeURIComponent(divId)}`;
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.send(data);
                } else {
                    console.error("No div found");
                }
            } else {
            
            }
            })
        })

        </script>
    </body>
</html>