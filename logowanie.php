<?php 
session_start();

$conn = mysqli_connect('localhost', 'root', '', 'egzamin');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['login'], $_POST['haslo'])) {
        $login = $_POST['login'];
        $haslo = $_POST['haslo'];

        if (strlen($login) > 18 || strlen($haslo) > 18 || strlen($login) < 8 || strlen($haslo) < 8) {
            $error = "Your login and password must be below 18 characters and above 8 characters.";
        } else {            
            $stmt = mysqli_prepare($conn, 'SELECT * FROM logowanie WHERE login = ?');
            mysqli_stmt_bind_param($stmt, "s", $login);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if (mysqli_num_rows($result) > 0) {
                $user = mysqli_fetch_assoc($result);
                if ($user['is_verified'] == 0) {
                    $error = "Your account is not verified. Please check your email for the verification link.";
                } elseif (password_verify($haslo, $user['haslo'])) {
                    $_SESSION['user_login'] = $user['login'];
                    setcookie('user_login', $login, time() + (86400 * 30), "/");    
                    header('Location: main_site.php');
                    exit();
                } else {
                    $error = "Incorrect password.";
                }
            } else {
                $error = "Account doesn't exist.";
            }
            
            mysqli_stmt_close($stmt);
        }
    }
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form">
        <div class="user">
            <img src="./img/user.png">
        </div>
        
        <form method="POST">
            <p>Username</p>
            <input type="text" name="login">
            <p>Password</p>
            <input type="password" name="haslo"> 
            <p style="font-family: 'book-light';margin-top:5px;">Don't have an account? <a href="signup.php">Sign up</a></p>
            <div class="login">
                <input type="submit" value="login">
            </div>
        </form>
        <?php 
        if (isset($error)) {
            echo "<div class='error'>".$error."</div>";
        }
        ?>
    </div>
</body>
</html>
