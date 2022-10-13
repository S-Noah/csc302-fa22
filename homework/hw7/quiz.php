<?php
require "questions.php";

$responses = null;
$correct = null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">""
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> 
    <script src="quizzer.js"></script>
    <script>
        let data = JSON.parse('<?= addslashes(json_encode($_POST["responses"])) ?>');
        questions = JSON.parse(data);
        console.log(questions)
    </script>
    <title>Quizzer</title>

    <style>
        .correct {
            background-color: greenyellow;
        }

        .incorrect {
            background-color: pink;
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <h1>Quizzer</h1>

    <div id="quiz-panel">
        <h2>Quiz</h2>
        <span id="score">
            <?php
            if($correct !== null){
                echo "Score: ". ($correct/count($questions)) ."($correct/". count($questions) .")";
            }
            ?>
        </span>
        
        <ol id="quiz">
            <?php

            $counter = 0;
            foreach($questions as $question){
                $correctnessClass = "";
                if(key_exists("correct", $question)){
                    $correctnessClass = $question["correct"] ? "correct" : "incorrect";
                }
                print "<li data-id=\"${counter}\" class=\"$correctnessClass\">${question['question']}<br/>".
                    "<textarea rows=\"3\" class=\"response\">"; 
                print "</textarea></li>";
                $counter += 1;
            }
            ?>
        </ol>

        <button id="check-quiz">Check</button>
        <button id="reset-quiz">Reset</button>
    </div>

    <form id="response-submission-form" class="hidden" method="post" action="quiz.php">
        <input type="text" name="responses"/>
    </form>
    
</body>
</html>