<?php
    require_once 'connection.php'; 
    session_start();

    // Redirect if already logged in
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        header('Location: admin.php');
        exit();
    }

    // Initialize variables
    $username = $password = "";
    $username_err = $password_err = $login_err = "";

    // Check if the request method is POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Validate username
        if (empty(trim($_POST["username"]))) {
            $username_err = "Please enter username.";
        } else {
            $username = trim($_POST["username"]);
        }

        // Validate password
        if (empty(trim($_POST["password"]))) {
            $password_err = "Please enter your password.";
        } else {
            $password = trim($_POST["password"]);
        }

        // Check credentials
        if (empty($username_err) && empty($password_err)) {

            $sql = "SELECT id, username, password FROM admin WHERE username = ?";
        
            if ($stmt = $db->prepare($sql)) {
                $stmt->bind_param("s", $param_username);
                $param_username = $username;
        
                if ($stmt->execute()) {
                    $stmt->store_result();
        
                    // Check if username exists
                    if ($stmt->num_rows == 1) {
                        // Bind result variables to allow fetching
                        $stmt->bind_result($id, $username, $hashed_password);
        
                        if ($stmt->fetch()) {
                            // After fetch(), the variables $id, $username, and $hashed_password
                            //the id,username and hashed password is equal to the column id,username and password 
                            if (password_verify($password, $hashed_password)) {
                              
                                session_start();
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["username"] = $username;
        
                                // Redirect to admin page
                                header("Location: admin.php");
                                exit();
                            } else {
                             
                                $login_err = "Invalid username or password.";
                            }
                        }
                    } else {
                     
                        $login_err = "Invalid username or password.";
                    }
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }
        
                // Close statement
                $stmt->close();
            }
        }
        // Close connection
        $db->close();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding:140px 20px; }
    </style>
</head>
<body>
    <div class="wrapper container mt-5">
        <h2>Login</h2>
        <p>Hello!, Make sure you are the admin</p>

        <?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
        </form>
    </div>
</body>
</html>