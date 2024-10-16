<?php 
session_start();

include './includes/conn.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="main_site.css">
</head>
<body>
    <header>
        <nav>
            <?php include_once './includes/navbar.html'?>
        </nav>
    </header>
    <div class="middle">
        <div class="leaderboard">
            <div class="highscore"><h1>High scores</h1></div>    
            <div class="track"><h3>Use the leaderboard to track and compare scores</h3></div>
            <div class="inside">
                <div class="list">
                    <div>Position</div>
                    <div>Login</div> 
                    <div>Score</div> 
                </div>
                <div class="ranking">
                    <?php 
                        $sql = "SELECT ROW_NUMBER() OVER (ORDER BY ranking.wynik DESC) AS pozycja,ranking.wynik,logowanie.login FROM ranking JOIN logowanie ON ranking.user_login = logowanie.login WHERE ranking.wynik >= 50 LIMIT 10;";
                        $q = mysqli_query($conn , $sql);
                        while($row = mysqli_fetch_array($q)) {
                            echo "<div class='flexgap'>";
                                echo "<div class='position'>" . $row['pozycja'] . "</div>";
                                echo "<div class='login'>" . $row['login'] . "</div>";
                                echo "<div class='wynik'>" . $row['wynik'] ."%</div>";
                            echo "</div>";
                        }
                    ?>
                </div>
            </div>
        </div>
        <div class="test">
            <h2>Last Test Taken</h2>
            <div class="dane">
                <div class="result">
                    <?php 
                    $userLogin = htmlspecialchars($_COOKIE['user_login']);
                    $sql2 = "SELECT wynik, godzina, data FROM ranking WHERE user_login = '$userLogin' ORDER BY id DESC LIMIT 1";
                    $q2 = mysqli_query($conn, $sql2);
                    if ($row2 = mysqli_fetch_array($q2)) {
                        echo "<div>"."wynik: ".$row2['wynik'] . "%</div>";
                        echo "<div>"."godzina: ". $row2['godzina'] . "</div>";
                        echo "<div>"."data: ". $row2['data'] . "</div>";
                    } else {
                        echo "<div><b>You haven't taken a test yet</b></div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>