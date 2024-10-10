<?php 
session_start();

$conn = mysqli_connect('localhost', 'root', '', 'egzamin');
require_once('./PHPMailer/mail.php');
require_once('./PHPMailer/Send.php');   

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'], $_POST['haslo'], $_POST['email'])) {
        $login = $_POST['login'];
        $haslo = $_POST['haslo'];
        $email = $_POST['email'];
        $haslo2 = $_POST['haslo2'];
        $v_code =bin2hex(random_bytes(16));
        $sprawdz = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
        $verification_code = substr(number_format(time() * rand(), 0,'',''),0,6);
        if (preg_match($sprawdz, $email)) {
            if (strlen($login) > 18 || strlen($haslo) > 18 || strlen($login) < 8 || strlen($haslo) < 8) {
                $error = "Your login and password must be below 18 characters and above 8 characters.";
            } else if ($haslo == $haslo2) {
                $stmt = mysqli_prepare($conn, "SELECT login FROM logowanie WHERE login = ?");
                mysqli_stmt_bind_param($stmt, "s", $login);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                if (mysqli_num_rows($result) > 0) {
                    $error = "Użytkownik istnieje w bazie"; 
                } else {
                    $stmt = mysqli_prepare($conn, "SELECT email FROM logowanie WHERE email = ?");
                    mysqli_stmt_bind_param($stmt, "s", $email);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    if (mysqli_num_rows($result) > 0) {
                        $error = "Email exists in database"; 
                    } else {
                        $haslo_hash = password_hash($haslo, PASSWORD_DEFAULT);
                        $stmt = mysqli_prepare($conn, 'INSERT INTO logowanie (login, haslo, email, verification_code) VALUES (?, ?, ?,?)');        
                        mysqli_stmt_bind_param($stmt, "ssss", $login, $haslo_hash, $email,$v_code);
                        mysqli_stmt_execute($stmt);
                        if($stmt)
                        {
                            header('Location:signup.php');
                        }
                    }
                }
            } else {
                $error = "The passwords do not match";
            }
        } else {
            $error = "Your email is invalid";
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
            <input type="text" name="login" required>
            <p>Password</p>
            <input type="password" name="haslo" required> 
            <p>Repeat password</p>
            <input type="password" name="haslo2" required> 
            <p>Email</p>
            <input type="email" name="email" required> 
            <p>Have an account already? <a href="logowanie.php">log in</a></p>
            <div class="login">
                <input type="submit" value="sign up">
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
