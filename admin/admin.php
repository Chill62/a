<?php 
$conn = mysqli_connect('localhost', 'root', '', 'egzamin');

if (!isset($_COOKIE['user_login'])) {
    header('Location: ../logowanie.php');
    exit(); 
}

if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $sql2 = "DELETE FROM pytania WHERE id = $id";
    $sql3 = "DELETE FROM odpowiedz WHERE id = (SELECT odpowiedz_id FROM pytania WHERE id = $id)";
    mysqli_query($conn, $sql2);
    mysqli_query($conn , $sql3);
}

if (isset($_POST['save'])) {
    $id = $_POST['id'];
    $pytanie = $_POST['pytanie']; 
    $A = $_POST['A'];
    $B = $_POST['B'];
    $C = $_POST['C'];
    $D = $_POST['D'];
    $odp = $_POST['odp'];

 
    $sql = "UPDATE pytania SET zapytanie = '$pytanie', poprawna_odpowiedz = '$odp' WHERE id = $id";
    mysqli_query($conn, $sql);


    $sql = "UPDATE odpowiedz SET A = '$A', B = '$B', C = '$C', D = '$D' WHERE id = (SELECT odpowiedz_id FROM pytania WHERE id = $id)";
    mysqli_query($conn, $sql);
}

if (isset($_POST['add_question'])) {
    $new_pytanie = $_POST['new_pytanie'];
    $odp = $_POST['odp'];
    $A = "A. ".$_POST['A'];
    $B = "B. ".$_POST['B'];
    $C = "C. ".$_POST['C'];
    $D = "D. ".$_POST['D'];

    if(strlen($new_pytanie) > 10 && strlen($new_pytanie) < 255) {
        $sql4 = "INSERT INTO odpowiedz (A, B, C, D) VALUES ('$A', '$B', '$C', '$D')";
        if (mysqli_query($conn, $sql4)) {
            $last_answer_id = mysqli_insert_id($conn);
            $sql3 = "INSERT INTO pytania (zapytanie, poprawna_odpowiedz, odpowiedz_id) VALUES ('$new_pytanie', '$odp', '$last_answer_id')";
            mysqli_query($conn, $sql3);
        }
    } else {
        $error = "Too few characters"; 
    }
}

$sql_p = "SELECT pytania.*, odpowiedz.A, odpowiedz.B, odpowiedz.C, odpowiedz.D FROM pytania JOIN odpowiedz ON pytania.odpowiedz_id = odpowiedz.id";
$q = mysqli_query($conn, $sql_p);
mysqli_close($conn);
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
                <div class="link1"><a href="../admin/admin.php">Admin panel</a></div>
            </div>
        </nav>
    </header>
    <div class="main">
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th style="text-align: center;" scope="col">#</th>
                    <th scope="col">Pytanie</th>
                    <th scope="col">A</th>
                    <th scope="col">B</th>
                    <th scope="col">C</th>
                    <th scope="col">D</th>
                    <th scope="col">Answer</th>
                    <th style="text-align:center; width:20%;" scope="col">Akcje</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <form method="POST" action="admin.php">  
                        <td style="text-align: center;">
                        </td>
                        <td>
                            <input type="text" name="new_pytanie" class="form-control" placeholder="<?php echo !isset($error) ? 'Add new question' : 'Error - '.$error; ?>">
                        </td>
                        <td>
                            <input type="text" name="A" class="form-control" placeholder="Question A">
                        </td>
                        <td>
                            <input type="text" name="B" class="form-control" placeholder="Question B">
                        </td>
                        <td>
                            <input type="text" name="C" class="form-control" placeholder="Question C">
                        </td>
                        <td>
                            <input type="text" name="D" class="form-control" placeholder="Question D">
                        </td>
                        <td>
                            <select name="odp" class="form-select"> 
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                            </select>
                        </td>
                        <td style="text-align: center;">
                            <input type="submit" class="btn btn-success" value="Add Question" name="add_question">
                        </td>
                    </form>
                </tr>
                <?php
                require_once("funkcja.php");
                if (isset($_POST['edit'])) {
                    $editId = $_POST['id'];
                } else {
                    $editId = null;
                }
                renderTableRows($q, $editId);
                
                ?>
            </tbody>
        </table>
    </div>
    <script src="script.js"></script>
</body>
</html>
