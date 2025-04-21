<?php

session_start();

require('configure.php');

if (!isset($_GET["id"])) {
    header('location:login.php');
    exit;
}
$id = null;
if (isset($_GET["id"])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
} else {
    echo "invalid user ID";
}


$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $new_username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $new_password = $_POST["password"] ?? "";

    if (($new_username) && ($new_password)) {


        $hash = password_hash($new_password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("UPDATE users SET username= ? , password = ? WHERE id = ?");
        $stmt->execute([$new_username, $hash, $id]);
        header('location:index.php');
        echo '<div class="d-flex justify-content-center mt-5">
                            <div class="alert alert-Success" role="alert">
                            Row Updated Successfully
                            </div>
                    </div>';
        exit;
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
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
</head>

<body>

    <div class="container" style="width: 400px;">
        <h2 class="mt-5">Edit</h2>
        <form action="update.php?id=<?php echo htmlspecialchars($id); ?>" method="post">
            <label class="form-label" for="username">Username</label>
            <input type="text" name="username" class="form-control" value="<?php echo $user["username"] ?>">
            <label class="form-label" for="password">Password</label>
            <input type="password" name="password" class="form-control" value="<?php echo $user["password"] ?>">
            <input type="submit" class="btn btn-warning mt-4" value="submit" name="submit">



        </form>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>

</body>

</html>