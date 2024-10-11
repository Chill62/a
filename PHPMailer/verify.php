<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php 
$conn = mysqli_connect('localhost', 'root', '', 'egzamin');

$email = $_GET['email'];
$v_code = $_GET['v_code'];

$sql = "SELECT email, verification_code, is_verified FROM logowanie WHERE email = '$email' AND verification_code = '$v_code'";
$q = mysqli_query($conn, $sql);

if ($q) {
    if (mysqli_num_rows($q) == 1) {
        $row = mysqli_fetch_array($q);

        if ($row['is_verified'] == 1) { 
            echo "<script>
            window.addEventListener('DOMContentLoaded', (event) => {
                Swal.fire({
                    title: 'Error!',
                    text: 'Your account is already verified.',
                    icon: 'error',
                    confirmButtonText: 'Okay'
                }).then(() => {
                    window.close();
                });
            });
            </script>";
        } else {
            $update = "UPDATE logowanie SET is_verified = 1 WHERE email = '$email'";
            $q_update = mysqli_query($conn, $update);
            if ($q_update) {
                echo "<script>
                window.addEventListener('DOMContentLoaded', (event) => {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Your account has been verified, you may log in now.',
                        icon: 'success',
                        confirmButtonText: 'Great'
                    }).then(() => {
                        window.location.href = '../logowanie.php';
                    });
                });
                </script>";
            }
        }
    } else {
        echo "<script>
        window.addEventListener('DOMContentLoaded', (event) => {
            Swal.fire({
                title: 'Error!',
                text: 'Account doesn\'t exist.',
                icon: 'error',
                confirmButtonText: 'Okay'
            }).then(() => {
                window.close();
            });
        });
        </script>";
    }
}
mysqli_close($conn);
?>

</body>
</html>
