<?php

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'user';
$dsn = 'mysql:host=localhost;dbname=user';


$pdo = new PDO($dsn,$username,$password);

try{
    $pdo->exec("USE {$dbname}");
}
catch(PDOException $e){
    echo 'connection failed'. $e->getMessage();
}


?>