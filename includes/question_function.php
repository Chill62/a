<?php 
function oblicz_procent($results , $array)
{
    foreach($results as $index => $row) {
        $Ratio = number_format($row['Ratio'], 2) . "%"; // Correctly formatting as a percentage
        echo "<tr>";
        echo '<th scope="row">' . htmlspecialchars($array[$index]) . '</th>'; 
        echo '<td>' . htmlspecialchars($Ratio) . '</td>';
        echo '<td>' . htmlspecialchars($row['zapytanie']) . '</td>';
        echo '</tr>';
    }
}
?>
