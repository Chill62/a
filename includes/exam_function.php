    <?php
    function display_all ($questions)
    {
        foreach($questions as $question_number)
        display_all($question_number);
    }
    function display_questions($question)
    {
        $ABCD = array('A', 'B', 'C', 'D');
        $question_title = $question['zapytanie'];
        echo "
            <div class='quiz'>
                <fieldset>
                    <legend>" . htmlspecialchars($question_title) . "</legend>";
        foreach ($ABCD as $LETTER) {
            echo " <div class='options'>
                        <label>
                            <input type='radio' name='question_" . $question['id'] . "' value='$LETTER'>
                            " . htmlspecialchars($question[$LETTER]) . "
                        </label>
                    </div>";
        }
        echo "</fieldset>";
        echo "</div>";
    }

    ?>

