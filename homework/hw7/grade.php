<?php
    require "questions.php";
    $answers = $_POST["responses"];
    $responses = [];
    foreach($answers as $i => $response){
        $response["correct"] = $response["answer"] === $questions[$i]["answer"];
        array_push($responses, $response);
    }
    echo(json_encode($responses));
?>