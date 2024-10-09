<?php 
$conn = mysqli_connect('localhost', 'root', '', 'egzamin');


if (!isset($_COOKIE['user_login'])) {
    header('Location: ../logowanie.php');
    exit(); 
}

if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $sql2 = "DELETE FROM pytania WHERE id = $id";
    $sql3 = "DELETE FROM odpowiedz WHERE id = $id";
    mysqli_query($conn, $sql2);
    mysqli_query($conn , $sql3);
}

if (isset($_POST['save'])) {
    $id = $_POST['id'];
    $pytanie = $_POST['pytanie']; 
    $sql = "UPDATE pytania SET zapytanie = '$pytanie' WHERE id = $id";
    mysqli_query($conn, $sql);
}
if (isset($_POST['add_question'])) {
    $new_pytanie = $_POST['new_pytanie'];
    $odp = $_POST['Odp'];
    $A = "A. ".$_POST['A'];
    $B = "B. ".$_POST['B'];
    $C = "C. ".$_POST['C'];
    $D = "D. ".$_POST['D'];

    if(strlen($new_pytanie) > 10) {
        $sql4 = "INSERT INTO odpowiedz (A,B,C,D) VALUES ('$A','$B','$C','$D')";
        if (mysqli_query($conn, $sql4)) {
            $last_answer_id = mysqli_insert_id($conn);
            $sql3 = "INSERT INTO pytania (zapytanie, poprawna_odpowiedz, odpowiedz_id) VALUES ('$new_pytanie', '$odp', '$last_answer_id')";
            mysqli_query($conn, $sql3);
        }
    } else {
        $error = "Too few characters"; 
    }
}


$sql = "SELECT * FROM pytania";
$q = mysqli_query($conn, $sql);
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
                <div class="link1"><a href="questions.php">Hardest questions</a></div>
                <div class="link1"><a href="../egzamin/egzamin.php">Exam</a></div>
                <div class="link1"><a href="admin_panel.php">Admin panel</a></div>
            </div>
        </nav>
    </header>
    <div class="main">
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th style="text-align: center;" scope="col">#</th>
                    <th scope="col">Pytanie</th> 
                    <th style="text-align:center; width:20%;" scope="col">Akcje</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <form method="POST" action="admin.php">  
                        <td style="text-align: center;">#</td>
                        <td style="display: flex;">
                            <input type="text" name="new_pytanie" class="form-control" style="width: 40%;" placeholder="<?php if(!isset($error)) {echo "add new question";}else {echo "Error - ".$error." need more than 10";} ?>">
                            <input type="text" name="A" class="form-control" style="width: 15%;" placeholder="Question A">
                            <input type="text" name="B" class="form-control" style="width: 15%;" placeholder="Question B">
                            <input type="text" name="C" class="form-control" style="width: 15%;" placeholder="Question C">
                            <input type="text" name="D" class="form-control" style="width: 15%;" placeholder="Question D">
                            <input type="text" name="Odp" class="form-control" style="width: 10%; text-align:center" placeholder="Answer">
                        </td>
                        <td style="text-align:center">
                            <input type="submit" class="btn btn-success" value="Add Question" name="add_question">
                        </td>
                    </form>
                </tr>

                <?php
                $editId = null;
                if (isset($_POST['edit'])) {
                    $editId = $_POST['id'];
                }
                while ($row = mysqli_fetch_array($q)) {
                    echo '<tr>';
                    echo '<th style="text-align:center" scope="row">' . $row['id'] . '</th>';
                    echo '<td>';
                    if ($editId == $row['id']) {
                        echo '<form method="POST" action="admin.php">';
                        echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
                        echo "<input type='text' style='width:40%;' name='pytanie' value='" . $row['zapytanie'] . "'>";
                    } else {
                        echo $row['zapytanie'];
                    }
                    echo '</td>';
                    echo '<td style="text-align:center">';
                    if ($editId == $row['id']) {
                        echo "<input type='submit' value='Save' name='save'>";
                        echo "<input type='submit' value='Delete' name='delete'>";
                        echo '</form>';  
                    } else {
                        echo '<form method="POST" action="admin.php">';
                        echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
                        echo "<input type='submit' value='Edit' name='edit'>";
                        echo "<input type='submit' value='Delete' name='delete'>";
                        echo '</form>';
                    }
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="script.js"></script>
</body>
</html>

<?php
mysqli_close($conn);
?>
