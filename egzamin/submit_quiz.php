<?php
session_start();
include '../includes/conn.php';

$quiz = $_SESSION['quiz'];
$score = $_GET['score'];

include 'funkcja_quiz.php';

$userAnswers = [];
if (isset($_COOKIE['user_answers'])) {
    $userAnswers = json_decode($_COOKIE['user_answers'], true); 
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Wynik Egzaminu</title>
    <link rel="stylesheet" href="./submit_quiz.css">
</head>
<body>
    <header>
        <nav>
            <?php include_once '../includes/navbar.html'?>
        </nav>
    </header>   
    <div class="answers">
        <div class="h">
            <h1><b>EE.09 / INF.03 / E.14</b> - Test results</h1>
        </div>
        <div class="score">
            <?php 
            echo "Your score is <b>".$score ."/" . count($quiz) . " -  " . ($score / count($quiz) * 100) . "%</b>";
            ?>
        </div>
        <main>
            <form method="POST">
                <?php foreach ($quiz as $question):    
                    $questionId = $question['id'];
                    $correctAnswer = $question['poprawna_odpowiedz'];
                    $selectedAnswer = '';
                    if (isset($userAnswers[$questionId])) {
                        $selectedAnswer = $userAnswers[$questionId];
                    }
                ?>
                <div class="quiz">
                    <fieldset>
                        <legend><?php echo htmlspecialchars($question['zapytanie']); ?></legend>
                        <div class="user_answer"><?php 
                        _collor($selectedAnswer,$correctAnswer);
                        Oblicz($correctAnswer , $selectedAnswer,$question); 
                         ?>
                        
                    </fieldset>
                </div>
                <?php endforeach; ?>
            </form>
        </main>
    </div>
</body>
</html>
