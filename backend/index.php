<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
/* 
::PLEASE READ ME::
This is very basic implentation of a router which will works only if the AJAX calls from React are comming from localhost
To implent a propper API based backend (or microservices) which to be hosted on separate machine we need  signed requests
Please see external_api/index.php for such approach
I don't like the idea to have signed requests directly in React because this will expose private key. 
To archieve this in the right way we will need a nodejs proxy which to sign requests
I can deliver such implementation if you want to see it in action, but for now let do it in the simplest possible way 
*/

//load config and parent class
define("BASE_PATH", __DIR__);

include_once("inc/config.php");
include_once("models/common.php");

class Router extends commonModel
{

    private $controller = '';

    public function __construct()
    {

        //    parent::__construct();
        // if ($_SERVER['HTTP_HOST'] !== 'localhost:8020' || $_SERVER['REMOTE_ADDR'] !== '127.0.0.1') {
        //     header('HTTP/1.1 403 Forbidden');
        //     exit('Access Denied');
        // }

        if (preg_match('/^([A-Za-z0-9_])+$/', $_SERVER['QUERY_STRING'], $regs)) {
            $this->controller = $regs[0];
        } else {
            $this->retError("Missing or invalid method");
        }
    }




    public function parseRequest()
    {

        /*
        if we had a signed request we can check here if the client requested perticular method has rights for it 
        simple configuration {api_client=>[allowed method1,2,3]} will do the job
        */
        $requestedController = "controllers/" . $this->controller . ".php";
        if (!file_exists($requestedController)) {
            $this->retError("Unable to find the controller. " . $requestedController);
        }

        include_once($requestedController);
    }
}

$router = new Router();
$router->parseRequest();
