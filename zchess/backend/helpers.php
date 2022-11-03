
<?php
/**
 * Emits a 200 response along with a JSON object with two fields:
 *   - success => true
 *   - data => the data that was passed in as `$data`
 * 
 * @param $data The value to assign to the `data` field of the output.
 */
function success($data){
    $response = ['success' => true];
    if($data){
        $response['data'] = $data;
    }
    die(json_encode($response));
  }
  
  /**
  * Emits a 201 Created response along with a JSON object with two fields:
  *   - success => true
  *   - data => the data that was passed in as `$data`
  * Sets the "Location" field of the header to the given URI.
  * 
  * @param $uri The URI of the created resource.
  * @param $data The value to assign to the `data` field of the output.
  */
  function created($uri, $data){
    http_response_code(201);
    header("Location: $uri");
    $response = ['success' => true];
    if($data){
        $response['data'] = $data;
    }
    die(json_encode($response));
  }
  
  /**
  * Emits a 500 response along with a JSON object with two fields:
  *   - success => false
  *   - error => an error message`
  * 
  * @param $error The value to assign to the `error` field of the output.
  */
  function error($error){
    http_response_code(500);
    die(json_encode([
        'success' => false,
        'error' => $error
    ]));
  }
  
  /**
  * Emits a 404 response along with a JSON object with two fields:
  *   - success => false
  *   - error => an error message`
  * 
  * @param $error The value to assign to the `error` field of the output.
  */
  function notFound($error){
    http_response_code(404);
    die(json_encode([
        'success' => false,
        'error' => $error
    ]));
  }

  function loadJson($filename){
    if(file_exists($filename)){
        $data = file_get_contents($filename);
        $array = json_decode($data, true);
        return $array;
    }
    return null;
  }
?>