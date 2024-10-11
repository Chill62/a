<?php    
function Oblicz($correctAnswer , $selectedAnswer , $question)
{
    $ABCD = array('A','B','C','D');
    foreach ($ABCD as $LETTER)
    {
        $CHOICE = $question[$LETTER];
        if($correctAnswer == $LETTER) {
            $class = 'green';
        }elseif($selectedAnswer == $LETTER)
        {
            $class = 'red';
        }else {
            $class = 'not';
        }
        echo '<label  class="' . $class . "". '">' . htmlspecialchars($CHOICE) . '</label>';
    }
}
function _collor($selectedAnswer, $correctAnswer) {
    if (isset($selectedAnswer)) {
        if($selectedAnswer != $correctAnswer && $selectedAnswer != null) {
        echo "<div style='color:red'>Wybrałeś złą odpowiedź - $selectedAnswer</div>";                                
        }elseif ($selectedAnswer != null && $selectedAnswer == $correctAnswer) {
            echo "<div style='color:green'>Wybrałeś dobrą odpowiedź - $selectedAnswer</div>";
        }
        if($selectedAnswer == null)
        {
            echo "<div style='color:blue'>Nie wybrałeś odpowiedzi</div>";
        }
    } 
}
?>
