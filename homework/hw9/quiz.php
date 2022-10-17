<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDO demo</title>
    <style>
        table, tr, td, th {
            border: 1px solid gray;
        }
    </style>
</head>
<body>

<?php
// For debugging:
error_reporting(E_ALL);
ini_set('display_errors', '1');

// TODO Change this as needed. SQLite will look for a file with this name, or
// create one if it can't find it.
$dbName = 'data.db';

// Leave this alone. It checks if you have a directory named www-data in
// you home directory (on a *nix server). If so, the database file is
// sought/created there. Otherwise, it uses the current directory.
// The former works on digdug where I've set up the www-data folder for you;
// the latter should work on your computer.
$matches = [];
preg_match('#^/~([^/]*)#', $_SERVER['REQUEST_URI'], $matches);
$homeDir = count($matches) > 1 ? $matches[1] : '';
$dataDir = "/home/$homeDir/www-data";
if(!file_exists($dataDir)){
    $dataDir = __DIR__;
}
$dbh = new PDO("sqlite:$dataDir/$dbName")   ;
// Set our PDO instance to raise exceptions when errors are encountered.
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Put your other code here.

// Create the Books table.
try{
    $dbh->exec('create table if not exists Results('. 
        'id integer primary key autoincrement, '. 
        'response text, correct boolean)');
} catch(PDOException $e){
    echo "There was an error creating the Results table: $e";
}

// Grade questions.
if(array_key_exists('response', $_POST)){
    try {
        $get_statement = $dbh->prepare("select * from Questions");
        $post_statement = $dbh->prepare('insert into Results(question, answer) '.
            'values (:question, :answer, :correct)');

        $get_statement->execute();
        
        while($row = $get_statement->fetch(PDO::FETCH_ASSOC)){
            $user_response = $_POST['responses'];
            $post_statement->execute([
                ':response' => $user_response,
                ':correct' => $user_response == $row["answer"]
            ]);
        }
        
    } catch(PDOException $e){
        echo "There was an error adding a book: $e";
    }
}

?>
    <h1>Quiz</h1>
    <table>
        <tr><th>question</th><th>answer</th>

        <?php
        try{
            $statement = $dbh->prepare("select * from Questions");
            $statement->execute();
            $columns = ['question'];
            while($row = $statement->fetch(PDO::FETCH_ASSOC)){
                echo "<tr>";
                foreach($columns as $col){
                    echo "<td>${row[$col]}</td>";
                }
                echo "<td><input name=\"response\" type=\"text\"</td>";
                echo "</tr>";
            }
            echo "</table>";
        } catch(PDOException $e){
            echo "There was an error fetching rows from Books.";
        }
        ?>
    </table>

</body>
</html>