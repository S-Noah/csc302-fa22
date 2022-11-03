<?php
function handleRequest($routes){
    // Initial request processing.
    // If this is being served from a public_html folder, find the prefix (e.g., 
    // /~jsmith/path/to/dir).
    $matches = [];
    preg_match('#^/~([^/]*)#', $_SERVER['REQUEST_URI'], $matches);
    if(count($matches) > 0){
        $matches = [];
        preg_match("#/home/([^/]+)/public_html/(.*$)#", dirname(__FILE__), $matches);
        $prefix = "/~". $matches[1] ."/". $matches[2];
        $uri = preg_replace("#^". $prefix ."/?#", "/", $_SERVER['REQUEST_URI']);
    } else {
        $prefix = "";
        $uri = $_SERVER['REQUEST_URI'];
    }
    // Get the request method; PHP doesn't handle non-GET or POST requests
    // well, so we'll mimic them with POST requests with a "_method" param
    // set to the method we want to use.
    $method = $_SERVER["REQUEST_METHOD"];
    $params = $_GET;
    if($method == "POST"){
        $params = $_POST;
        if(array_key_exists("_method", $_POST))
            $method = strtoupper($_POST["_method"]);
    } 
    // Parse the request and send it to the corresponding handler.
    $foundMatchingRoute = false;
    $match = [];
    foreach($routes as $route){
        if($method == $route["method"]){
            preg_match($route["pattern"], $uri, $match);
            if($match){
                success(json_encode($route["controller"]($uri, $match, $params)));
                $foundMatchingRoute = true;
            }
        }
    }
    if(!$foundMatchingRoute){
        error("No route found for: $method $uri");
    }
}
/**
*  Creates a map with three keys pointing the the arguments passed in:
*      - method => $method
*      - pattern => $pattern
*      - controller => $function
* 
* @param method The http method for this route.
* @param pattern The pattern the URI is matched against. Include groupings
*                around ids, etc.
* @param function The name of the function to call.
* @return A map with the key,value pairs described above.
*/
function makeRoute($method, $pattern, $function){
    return [
        "method" => $method,
        "pattern" => $pattern,
        "controller" => $function
    ];
  }
?>