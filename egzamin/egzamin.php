<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'egzamin');

if (!isset($_SESSION['quiz'])) {
    $q = "SELECT pytania.id, pytania.zapytanie, pytania.poprawna_odpowiedz, odpowiedz.A, odpowiedz.B, odpowiedz.C, odpowiedz.D 
          FROM pytania 
          JOIN odpowiedz ON pytania.odpowiedz_id = odpowiedz.id 
          ORDER BY RAND() LIMIT 40;";
          
    $result = mysqli_query($conn, $q);
    $quiz = [];

    while ($question = mysqli_fetch_array($result)) {
        $quiz[] = $question;
    }
}

if (isset($_POST['przycisk'])) {
    $score = 0; 
    $quiz = $_SESSION['quiz'];

    foreach ($quiz as $question) {
        $questionId = $question['id']; 
        $correctAnswer = $question['poprawna_odpowiedz']; 

        if (isset($_POST["question_$questionId"]) && $_POST["question_$questionId"] == $correctAnswer) {
            $score++;
        }
        
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
            <div class="link-container">
                <div class="link"><a href="../main_site.php">Main site</a></div>
                <div class="link"><a href="questions.php">Hardest questions</a></div>
                <div class="link"><a href="../egzamin/egzamin.php">Exam</a></div>
                <div class="link"><a href="admin_panel.php">Admin panel</a></div>
            </div>
        </nav>
    </header>
    <main>
        <form method="POST">
            <?php 
            $questionId = $question['id'];
            foreach ($_SESSION['quiz'] as $question): 
            ?>
            <div class="quiz">
                <fieldset>
                    <legend><?php echo htmlspecialchars($question['zapytanie']); ?></legend>
                    <div class="options">
                        <label>
                            <?php if(isset($_POST["question_$questionId"])) {echo $_POST["question_$questionId"];} ?>
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
                <button type="submit" class="submit" name="przycisk">Sprawdź odpowiedzi</button>
            </div>
            <div style="height: 250px;"></div>
        </form>
    </main>
</body>
</html>
