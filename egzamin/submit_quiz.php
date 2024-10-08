<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'egzamin');

$quiz = $_SESSION['quiz'];
$score = $_GET['score'];

if (!isset($_COOKIE['user_login'])) {
    header('Location: ../logowanie.php');
    exit(); 
}

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
            <div class="link-container">
                <div class="link"><a href="../main_site.php">Main site</a></div>
                <div class="link"><a href="../pytania/pytania.php">Hardest questions</a></div>
                <div class="link"><a href="../egzamin/egzamin.php">Exam</a></div>
                <div class="link"><a href="../admin/admin.php">Admin panel</a></div>
            </div>
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
                        if (isset($selectedAnswer)) {
                            if($selectedAnswer != $correctAnswer) {
                            echo "<div style='color:red'Wybrałeś złą odpowiedź - $selectedAnswer</div>";                                
                            }elseif ($selectedAnswer != null && $selectedAnswer == $correctAnswer) {
                                echo "<div style='color:green'>Wybrałeś dobrą odpowiedź - $selectedAnswer</div>";
                            }
                            if($selectedAnswer == null)
                            {
                                echo "<div style='color:blue'>Nie wybrałeś odpowiedzi</div>";
                            }
                        }        
                        

                         ?>
                        <div class="options">
                            <label <?php 
                                if ($correctAnswer == 'A') {
                                    echo 'class="green"';
                                } elseif ($selectedAnswer == 'A') {
                                    echo 'class="red"';
                                }else {
                                    echo 'class=not';
                                }
                            ?>>
                                <?php echo htmlspecialchars($question['A']); ?>
                            </label>
                            <label <?php 
                                if ($correctAnswer == 'B') {
                                    echo 'class="green"';
                                } elseif ($selectedAnswer == 'B') {
                                    echo 'class="red"';
                                }else {
                                    echo 'class=not';
                                }
                            ?>>
                                <?php echo htmlspecialchars($question['B']); ?>
                            </label>
                            <label <?php 
                                if ($correctAnswer == 'C') {
                                    echo 'class="green"';
                                } elseif ($selectedAnswer == 'C') {
                                    echo 'class="red"';
                                }else {
                                    echo 'class=not';
                                }
                            ?>>
                                <?php echo htmlspecialchars($question['C']); ?>
                            </label>
                            <label <?php 
                                if ($correctAnswer == 'D') {
                                    echo 'class="green"';
                                } elseif ($selectedAnswer == 'D') {
                                    echo 'class="red"';
                                }else {
                                    echo 'class=not';
                                }
                            ?>>
                                <?php echo htmlspecialchars($question['D']); ?>
                            </label>
                        </div>
                    </fieldset>
                </div>
                <?php endforeach; ?>
            </form>
        </main>
    </div>
</body>
</html>
