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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | D'Leverage Admin</title>
    <link rel="icon" type="image/x-icon" href="image/static-img/logo2.png">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./css/admin.css?v=<?php echo time(); ?>">
</head>
<body class="bg-main">
    <div class="wrapper">
        <a href="index.php" class="logo-link">
            <img src="image/static-img/logo-trim.png" alt="D'Leverage Logo">
        </a>
        <h2>Welcome back</h2>
        <p>Sign in to manage your portfolio, albums and inbox.</p>

            <?php
            if(!empty($_SESSION['login_erro'])){
                echo '<div class="alert alert-danger d-flex align-items-center gap-2"><i class="fas fa-circle-exclamation"></i><span>' . $_SESSION['login_erro'] . '</span></div>';
                unset($_SESSION['login_erro']);
            }
            ?>
        <form action="actions/login.php" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($_SESSION['username_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($_SESSION['old_username']); ?>">
                <span class="invalid-feedback"><?php echo htmlspecialchars($_SESSION['username_err']); ?></span>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($_SESSION['password_err'])) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo htmlspecialchars($_SESSION['password_err']); ?></span>
            </div>
            <div class="form-group mb-0">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-right-to-bracket me-2"></i>Login
                </button>
            </div>
        </form>
    </div>
</body>
</html>
<?php
    unset($_SESSION['username_err']);
    unset($_SESSION['password_err']);
?>