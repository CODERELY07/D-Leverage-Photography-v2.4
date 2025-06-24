<?php
    require_once 'connection.php'; 
    session_start();

    // Redirect if already logged in
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        header('Location: admin.php');
        exit();
    }

    $_SESSION['username_err'] = $_SESSION['username_err'] ?? '';
    $_SESSION['password_err'] = $_SESSION['password_err'] ?? '';
    $_SESSION['old_username'] = $_SESSION['old_username'] ?? '';

    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/admin.css">
    <link rel="stylesheet" href="./css/style.css">
    <style>
        body{ font: 14px sans-serif; }
    </style>
</head>
<body class="bg-main">
    <div class="wrapper mt-5">
            <div>
                <a href="index.php"
                    >
                    <img src="image/static-img/logo.png" width="100px"  alt="D'Leverage Logo"
                    />
                </a>
            </div>
        <h2>Login</h2>
        <p>Hello!, Make sure you are the admin</p>

            <?php 
            if(!empty($_SESSION['login_erro'])){
                echo '<div class="alert alert-danger">' . $_SESSION['login_erro'] . '</div>';
                unset($_SESSION['login_erro']);
            }        
            ?>
        <form action="actions/login.php" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($_SESSION['username_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $_SESSION['old_username']; ?>">
                <span class="invalid-feedback"><?php echo $_SESSION['username_err']; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($_SESSION['password_err'])) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $_SESSION['password_err']; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
        </form>
    </div>
</body>
</html>
<?php 
    unset($_SESSION['username_err']);
    unset($_SESSION['password_err']);
?>