<?php
  require_once("helpers.php");
  require_once("router.php");

  $cred = loadJson("../cred.json");

  if($cred == null){
    error("Server Misconfiguration: DATABASE CREDENTIALS", 500);
  }

  $conn = new mysqli($cred["host"], $cred["user"], $cred["pass"], $cred["database"]);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $conn->autocommit(false);
  
  $sql = "CREATE TABLE users (
    id BINARY(16) PRIMARY KEY,
    username VARCHAR(255),
    pass VARCHAR(255),
    fullname VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";

  $routes = [
    // Books.
    makeRoute("POST", "#^/books/?(\?.*)?$#", "addBook"),
    makeRoute("GET", "#^/books/?(\?.*)?$#", "getBooks"),
    makeRoute("GET", "#^/books/(\w+)/?(\?.*)?$#", "getBook"),

    // Patrons -- the handlers for these need to be re-vamped.
    makeRoute("POST", "#^/patrons/?(\?.*)?$#", "addPatron"),
    makeRoute("GET", "#^/patrons/?(\?.*)?$#", "getPatrons"),
    makeRoute("GET", "#^/patrons/(\w+)/?(\?.*)?$#", "getPatron"),
  ];

  //handleRequest($routes);

  function getBooks(){
   return ['test'=>'LOL'];
  }
  function queryTest($db, $query){
    $result = $db->query($query);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          echo json_encode($row) . "<br>";
        }
      } else {
        echo "0 results";
      }
  }
?>