<?php
declare(strict_types=1);
namespace App\Models;

class CommonModel {


    private static $_db = null;

    public function __construct(){
        //do nothing for now, but we must call it in children and must have it upfront
    }

    function __get(string $var){
        global $DB_MAIN;
        //define sigleton connection to prevent reconect in child classes
        if($var =="db"){
            if(self::$_db == null){
                self::$_db = new \PDO("mysql:host=$DB_MAIN->host;dbname=$DB_MAIN->db_name;charset=utf8mb4",$DB_MAIN->user,$DB_MAIN->password,[
                    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION, // Enable exceptions
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                    \PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            }
            return self::$_db;
        }
    }

    protected function retError(string $errText) : void {

        $err = (object)array("result"=>"error","data"=>$errText);
        echo json_encode($err);
        exit;
   
      }
   
    protected function retData($data) :void{
   
       $res = (object)array("result"=>"OK","data"=>$data);
       echo json_encode($res);
       exit;
   
     }
    
}