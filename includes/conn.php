<?php 
$conn = mysqli_connect('localhost', 'root', '', 'egzamin');
if (!isset($_COOKIE['user_login'])) {
    header('Location: ../logowanie.php');
    exit(); 
}
?>