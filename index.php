    <?php

    session_start();

    require('configure.php');

    if (!isset($_SESSION["id"])) {
        header('location:login.php');
        exit;
    }

    $search = isset($_GET["search"]) ? $_GET["search"] : "";

    if ($search) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username LIKE ?");
        $stmt->execute(["%" . htmlspecialchars($search) . "%"]);
    } else {
        $stmt = $pdo->query("SELECT * FROM users");
    }

    $search_result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {



        if (isset($_POST["logout"])) {
            session_destroy();
            header('Location: login.php');
            echo '<div class="d-flex justify-content-center mt-5">
                            <div class="alert alert-Success" role="alert">
                            Logged out Successfully
                            </div>
                    </div>';
            exit;
        }

        $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        $password = $_POST["password"] ?? "";



        if (($username) && ($password)) {

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
                $stmt = $pdo->prepare("INSERT INTO users(username, password) VALUES(?,?)");
                $stmt->execute([$username, $hash]);

                header('location:' . $_SERVER["PHP_SELF"]);
                echo '<div class="d-flex justify-content-center mt-5">
                            <div class="alert alert-Success" role="alert">
                            Row Added Successfully
                            </div>
                    </div>';
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
        <title>Home</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

    </head>

    <body>

        <h2 class="mt-5 text-center ">PHP CRUD operations with MySQL</h2>

        <form method="post" class="container  d-flex justify-content-between mb-5 mt-3">
            <h3>Welcome, <span> <?= $_SESSION['username'] ?> </span></h3>
            <input type="submit" class="btn btn-danger" value="Logout" name="logout">
        </form>

        <form class="d-flex mb-4 container mt-4" action="index.php" method='get' style="width: 600px;">
            <input class="form-control me-2" type="search" name='search' placeholder="Search">
            <button class="btn btn-outline-success" type="submit">Search</button>
        </form>

        <h3 class="mt-5 text-center mb-5 text-primary ">Add row of data</h3>
        <form action="index.php" class="container d-flex justify-content-around" method="post">

            <div class="container ">
                <label class="form-label" for="username">Username</label><br>
                <input type="text" name="username" class="form-control" style="width: 300px;">
            </div>
            <div class="container">
                <label class="form-label" for="username">Password</label><br>
                <input type="password" name="password" class="form-control" style="width: 300px;">
            </div>


            <input type="submit" class="btn btn-info text-white mt-4" value="Add" name="add">


        </form>
        <h3 class="mt-5 text-center mb-5 text-primary ">Database</h3>
        <table class="container table   text-center table-dark mt-5">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($search_result as $row) { ?>
                    <tr>
                        <td><?= $row["id"] ?></td>
                        <td><?= $row["username"] ?></td>
                        <td><?= $row["password"] ?></td>
                        <td><a href="update.php?id=<?= $row["id"] ?>" class="btn btn-warning text-white">Edit</a></td>
                        <td><a href="delete.php?id=<?= $row["id"] ?>" class="btn btn-danger ">Delete</a></td>
                    </tr>

                <?php } ?>
            </tbody>
        </table>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>

    </body>

    </html>