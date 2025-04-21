<?php
require('configure.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

    if (!empty($username) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?  ");
        $stmt->execute([$username]);
        $is_user_exist = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($is_user_exist) {
            echo '<div class="d-flex justify-content-center mt-5">
                        <div class="alert alert-warning" role="alert">
                        User Already Exist
                        </div>
                </div>';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users(username, password) VALUES(?, ?)");
            $stmt->execute([$username, $hash]);

            if ($stmt) {

                header('location:login.php');
                echo '<div class="d-flex justify-content-center mt-5">
                        <div class="alert alert-Success" role="alert">
                        User Registered Successfully
                        </div>
                </div>';
                exit;
            } else {
                echo '<div class="d-flex justify-content-center mt-5">
                        <div class="alert alert-danger" role="alert">
                        Failed to register
                        </div>
                </div>';
            }
        }
    } else {
        echo '<div class="d-flex justify-content-center mt-5">
        <div class="alert alert-danger" role="alert">
        username or password is empty, please enter credentials
        </div>
        </div>';
    }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
</head>

<body>

    <div class="container" style="width: 400px;">
        <h2 class="mt-5">Register</h2>
        <form action="register.php" method="post">
            <label class="form-label" for="username">Username</label>
            <input type="text" name="username" class="form-control">
            <label class="form-label" for="password">Password</label>
            <input type="password" name="password" class="form-control">
            <input type="submit" class="btn btn-dark mt-4" value="Register" name="register">
            <a href="login.php" class="btn btn-info mt-4">Go to Login</a>

        </form>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>

</body>

</html>