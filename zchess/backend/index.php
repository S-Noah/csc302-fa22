<?php
  header('Access-Control-Allow-Origin: *');
  header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
  require_once("helpers.php");
  require_once("router.php");
  
  $creds = loadJson("../creds.json");

  if($creds == null){
    error("Server Misconfiguration: DATABASE credsentials", 500);
  }

  $conn = new mysqli($creds["host"], $creds["user"], $creds["pass"], $creds["database"]);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $conn->autocommit(false);

  $routes = [
    // Books.
    //makeRoute("POST", "#^/books/?(\?.*)?$#", "getBooks"),
    makeRoute("GET", "#^/books/?(\?.*)?$#", "getBooks"),
    makeRoute("GET", "#^/books/(\w+)/?(\?.*)?$#", "getBook"),

    // Patrons -- the handlers for these need to be re-vamped.
    makeRoute("POST", "#^/patrons/?(\?.*)?$#", "addPatron"),
    makeRoute("GET", "#^/patrons/?(\?.*)?$#", "getPatrons"),
    makeRoute("GET", "#^/patrons/(\w+)/?(\?.*)?$#", "getPatron"),
    makeRoute("OPTIONS", "##", "preflight")
  ];

  //handleRequest($routes, $_POST);
  echo json_encode($_POST);
  function getBooks(){
    success(json_encode(["TEST"=>"WORKING"]));
  }
  function preflight(){
    respond();
  }
  //echo json_encode($_SERVER);
?>