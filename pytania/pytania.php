<?php 
    $conn = mysqli_connect('localhost', 'root', '', 'egzamin');

    if (!isset($_COOKIE['user_login'])) {
        header('Location: ../logowanie.php');
        exit(); 
    }

    $sql = "SELECT ROW_NUMBER() OVER (ORDER BY poprawnosc ASC) as pozycja, poprawnosc, zapytanie, total FROM pytania WHERE poprawnosc != 0 ORDER BY poprawnosc ASC LIMIT 10";
    $q = mysqli_query($conn, $sql);
    mysqli_close($conn)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <header>
        <nav>
            <div class="link-container1">
                <div class="link1"><a href="../main_site.php">Main site</a></div>
                <div class="link1"><a href="../pytania/pytania.php">Hardest questions</a></div>
                <div class="link1"><a href="../egzamin/egzamin.php">Exam</a></div>
                <div class="link1"><a href="pytania.php">Admin panel</a></div>
            </div>
        </nav>
    </header>
    <div class="main">
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Correctness</th>
                    <th scope="col">Question</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $results = []; 

                    while ($row = mysqli_fetch_array($q)) {
                        $row['Ratio'] = ($row['poprawnosc'] / $row['total']) * 100;
                        $results[] = $row; 
                    }

                    $array = range(1, 10); 
                    usort($results, function($a, $b) {
                        return $a['Ratio'] <=> $b['Ratio']; 
                    });

                    foreach ($results as $index => $row) {
                        $Ratio = number_format($row['Ratio'], 2) . "%"; 
                        echo '<tr>';
                        echo '<th scope="row">' . $array[$index] . '</th>'; 
                        echo '<td>' . $Ratio . '</td>';
                        echo '<td>' . $row['zapytanie'] . '</td>';
                        echo '</tr>';
                    }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

