<?php

session_start();

require('configure.php');

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");

$stmt->execute([$id]);

header('location:index.php');
echo '<div class="d-flex justify-content-center mt-5">
                            <div class="alert alert-Success" role="alert">
                            Deleted Successfully
                            </div>
                    </div>';
exit;
