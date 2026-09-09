<?php
    session_start();
    require_once __DIR__ . '/../config/connection.php';
    $username = $password = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (empty(trim($_POST["username"]))) {
            $_SESSION['username_err'] = "Please enter username.";
        } else {
            $username = trim($_POST["username"]);
        }

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

        if (empty($username_err) && empty($password_err)) {

            $sql = "SELECT id, username, password FROM admin WHERE username = ?";

            if ($stmt = $db->prepare($sql)) {
                $stmt->bind_param("s", $param_username);
                $param_username = $username;

                if ($stmt->execute()) {
                    $stmt->store_result();

                    if ($stmt->num_rows == 1) {

                        $stmt->bind_result($id, $username, $hashed_password);

                        if ($stmt->fetch()) {

                            if (password_verify($password, $hashed_password)) {

                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["username"] = $username;

                                header("Location: ./../admin/dashboard.php");
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

                $stmt->close();
            }
        }

        $db->close();
    }
?>