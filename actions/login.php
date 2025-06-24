<?php
    session_start();
    require_once './../connection.php';
    $username = $password = '';
    
    // Check if the request method is POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Validate username
        if (empty(trim($_POST["username"]))) {
            $_SESSION['username_err'] = "Please enter username.";
        } else {
            $username = trim($_POST["username"]);
        }

        // Validate password
        if (empty(trim($_POST["password"]))) {
            $_SESSION['password_err'] = "Please enter your password.";
        } else {
            $password = trim($_POST["password"]);
        }

        if (!empty($_SESSION['username_err']) || !empty($_SESSION['password_err'])) {
            $_SESSION['old_username'] = $_POST['username'] ?? '';
            header("Location: ../adminLogin.php"); 
            exit();
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
                              
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["username"] = $username;
                                // Redirect to admin page
                                header("Location: ./../admin.php");
                                exit();
                            } else {
                                $_SESSION['login_erro'] = "Invalid username or password.";
                                header("Location: ../adminLogin.php"); 
                                exit();
                            }
                        }
                    } else {
                        $_SESSION['login_erro'] = "Invalid username or password.";
                        header("Location: ../adminLogin.php"); 
                        exit();
                    }
                } else {
                     $_SESSION['login_erro'] =  "Oops! Something went wrong. Please try again later.";
                     header("Location: ../adminLogin.php"); 
                     exit();
                }
        
                // Close statement
                $stmt->close();
            }
        }
        // Close connection
        $db->close();
    }
?>