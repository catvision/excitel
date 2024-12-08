<?php

class API {

   private $clients ;

   public function __construct() {
    $this->clients =  [
        "excitel" => "sdlk@g!23TYU",
        "other_client" => "some_private_key"
    ];
   }

   private function retError($errText){

     $err = (object)array("result"=>"error","data"=>$errText);
     echo json_encode($err);
     exit;

   }
    private function returnData(){

        $filename = 'data.json';

        if (!file_exists($filename)) {
          $this->retError("Unable to read the file.");  
        }

        
        $jsonData = file_get_contents($filename);

        
        if ($jsonData === false) {
            $this->retError("Unable to read the file."); 
        }

       
        $data = json_decode($jsonData, true);

        
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->retError("Unable to read the file."); 
        }

        //return the formatted result and ensure that nothing more will be returned
        $res = (object)array("result"=>"OK","data"=>$data);
        echo json_encode($res);
        exit;

    }
    
    public function parseRequest(){
        //first check the validity of the request
        if(!isset($_POST)||!isset($_POST["api_client"])||!isset($_POST["hash"])||!isset($_POST["req"]))
        {
            $this->retError("Invalid request");
        }
        $apiClient= $_POST["api_client"];
        $providedHash= $_POST["hash"];
        $req = json_decode($_POST["req"]);
        //check if the client exists
        if(!isset($this->clients[$apiClient])){
            $this->retError("Invalid API client");
        }

        $expectedHash = hash_hmac('sha256', $_POST["req"], $this->clients[$apiClient]);
        if($expectedHash!=$providedHash){
            $this->retError("Invalid hash");
        }

        //usually an API have more than one method, so we will parse such param even it's not needed in particular task
        if(!isset($req->method))
        {
            $this->retError("Invalid method");
        }

        //if all good will proceed with data delivery
        switch($req->method)
        {
            case "getList":             
                $this->returnData();
                break;
            default:
                $this->retError("Invalid method requested");    
        }
    }
}

$api = new API();
$api->parseRequest();
