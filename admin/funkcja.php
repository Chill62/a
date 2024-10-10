<?php
function renderTableRows($resultSet, $editId) {

    while ($row = mysqli_fetch_array($resultSet)) {
        echo '<tr>';
        echo '<th style="text-align:center" scope="row">' . $row['id'] . '</th>';
        echo '<td>';
        
        echo $editId == $row['id'] 
            ? "<form method='POST' action='admin.php'><input type='hidden' name='id' value='" . $row['id'] . "'><input type='text' style='width:100%;' name='pytanie' value='" . $row['zapytanie'] . "'>" 
            : $row['zapytanie'];
        
        echo '</td>';

        foreach (['A', 'B', 'C', 'D'] as $option) {
            echo '<td>';
            echo $editId == $row['id'] 
                ? "<input type='text' name='$option' value='" . $row[$option] . "'>" 
                : $row[$option];
            echo '</td>';
        }
        
        echo '<td>';

        echo $editId == $row['id'] 
            ? "<select name='odp'>
                    <option value='A' ".($row['poprawna_odpowiedz'] == 'A' ? 'selected' : '').">A</option>
                    <option value='B' ".($row['poprawna_odpowiedz'] == 'B' ? 'selected' : '').">B</option>
                    <option value='C' ".($row['poprawna_odpowiedz'] == 'C' ? 'selected' : '').">C</option>
                    <option value='D' ".($row['poprawna_odpowiedz'] == 'D' ? 'selected' : '').">D</option>
                </select>" 
            : $row['poprawna_odpowiedz'];
        
        echo '</td>';
        echo '<td style="text-align:center">';
        
        echo $editId == $row['id'] 
            ? "<input type='submit' value='Save' name='save'><input type='submit' value='Delete' name='delete'></form>" 
            : "<form method='POST' action='admin.php'><input type='hidden' name='id' value='" . $row['id'] . "'><input type='submit' value='Edit' name='edit'><input type='submit' value='Delete' name='delete'></form>";
        
        echo '</td>';
        echo '</tr>';
    }
}
?>
