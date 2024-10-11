<?php
session_start();

include '../multi/conn.php';

if (!isset($_COOKIE['user_login'])) {
    session_destroy();
    header('Location: ../logowanie.php');
    exit(); 
}
if(isset($_POST['reset'])) {
    session_destroy();
    header('Location: egzamin.php?refresh=true');

}
if (!isset($_SESSION['quiz'])) {
    $q = "SELECT pytania.id, pytania.zapytanie, pytania.poprawna_odpowiedz, odpowiedz.A, odpowiedz.B, odpowiedz.C, odpowiedz.D 
          FROM pytania 
          JOIN odpowiedz ON pytania.odpowiedz_id = odpowiedz.id 
          ORDER BY RAND() LIMIT 25;";
          
    $result = mysqli_query($conn, $q);
    $quiz = [];

    while ($question = mysqli_fetch_array($result)) {
        $quiz[] = $question;
    }
    $_SESSION['quiz'] = $quiz; 
}

if (isset($_POST['przycisk'])) {
    $score = 0; 
    $right = 0;
    $userAnswers = []; 
    
    foreach ($_SESSION['quiz'] as $question) {
        $correctAnswer = $question['poprawna_odpowiedz']; 
        $questionId = $question['id'];
        
        if (isset($_POST["question_" . $questionId])) {
            $totalUpdateSql = "UPDATE pytania SET total = total + 1 WHERE id = $questionId";
            mysqli_query($conn, $totalUpdateSql);

            $userAnswer = $_POST["question_" . $questionId]; 
            $userAnswers[$questionId] = $userAnswer; 
            
            if ($userAnswer == $correctAnswer) {
                $score++;
                $sql = "UPDATE pytania SET poprawnosc = poprawnosc + 1 WHERE id = $questionId";
                mysqli_query($conn, $sql);
            }
        }
    } 
    setcookie('user_answers', json_encode($userAnswers), time() + (86400 * 30), "/");

    $score2 = $score * 4;
    $userLogin = $_COOKIE['user_login'];
    $stmt = mysqli_prepare($conn, 'INSERT INTO ranking (wynik, data, godzina, user_login) VALUES (?, CURRENT_DATE, CURRENT_TIME, ?)');
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "is", $score2, htmlspecialchars($userLogin)); 
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    
    header("Location: submit_quiz.php?score=$score");
    exit();
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>egzamin</title>
    <link rel="stylesheet" href="egzamin.css">
</head>
<body>
    <header>
        <nav>
            <?php include_once '../multi/navbar.html'?>
        </nav>
    </header>
    <main>
        <form method="POST">
            <?php 
            foreach ($_SESSION['quiz'] as $question):
                $questionId = $question['id']; 
            ?>
            <div class="quiz">
                <fieldset>
                    <legend><?php echo htmlspecialchars($question['zapytanie']); ?></legend>
                    <div class="options">
                        <label>
                            <input type="radio" name="question_<?php echo $question['id']; ?>" value="A">
                            <?php echo htmlspecialchars($question['A']); ?>
                        </label>
                        <label>
                            <input type="radio" name="question_<?php echo $question['id']; ?>" value="B">
                            <?php echo htmlspecialchars($question['B']); ?>
                        </label>
                        <label>
                            <input type="radio" name="question_<?php echo $question['id']; ?>" value="C">
                            <?php echo htmlspecialchars($question['C']); ?>
                        </label>
                        <label>
                            <input type="radio" name="question_<?php echo $question['id']; ?>" value="D">
                            <?php echo htmlspecialchars($question['D']); ?>
                        </label>
                    </div>
                </fieldset>
            </div>
            <?php endforeach; ?>    
            <div class="button">
                <button type="submit" class="submit" name="przycisk">Check answers</button><br>
            </div>
            
            <div class="log"><input type="submit" class="submit" name="reset" value="Reset exam"></div>
        </form>
    </main>
</body>
</html>
