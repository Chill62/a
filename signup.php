<?php 
session_start();

$conn = mysqli_connect('localhost', 'root', '', 'egzamin');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['login'], $_POST['haslo'],$_POST['email'])) {
        $login = $_POST['login'];
        $haslo = $_POST['haslo'];
        $email = $_POST['email'];
        $haslo2 = $_POST['haslo2'];
        $sprawdz = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
        if (preg_match($sprawdz, $email)) {
            if (strlen($login) > 18 || strlen($haslo) > 18 || strlen($login) < 8 || strlen($haslo) < 8) {
                $error = "Your login and password must be below 18 characters and above 8 characters.";
            } else if($haslo == $haslo2) {
                
                $stmt = mysqli_prepare($conn ,"Select login FROM logowanie WHERE login = ?");
                mysqli_stmt_bind_param($stmt , "s" , $login);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                if(mysqli_num_rows($result ) > 0)
                {
                   $error = "Użytkownik istnieje w bazie"; 
                }else
                $haslo_hash = password_hash($haslo , PASSWORD_DEFAULT);
                $stmt = mysqli_prepare($conn , 'INSERT INTO logowanie (login, haslo , email) VALUES (?,?,?)');        
                mysqli_stmt_bind_param($stmt, "sss", $login , $haslo_hash , $email);
                if(mysqli_stmt_execute($stmt))
                {
                    header("Location: logowanie.php");
                    exit(); 
                }
            }else{
                $error = "the passwords do not match";
            }
        }else {
            $error="your email is invalid";
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
            <p>Repeat password</p>
            <input type="password" name="haslo2"> 
            <p>Email</p>
            <input type="email" name="email"> 
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
