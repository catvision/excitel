<?php
/*
If we are planning to use more than database or more than one API server to pull data 
it's better to put configs into Objects than to use standard approach and have list of constantants 
*/

//Database config(s)
$DB_MAIN = (object)array(
    "host" => 'mysql-excitel',
    "db_name"=> 'excitel',
    "user" => "test_user",
    "password"=>"testPassword!23^ee"
);

//API configs
$API_LIVE_DATA = (object)array(
    "URL" => "localhost/external_api/index.php",
    "PUBLIC_KEY"=>"excitel",
    "PRIVATE_KEY"=>"sdlk@g!23TYU"
);

$API_MOCK_DATA = (object)array(
    "URL" => "localhost/mockup_api/index.php",
    "PUBLIC_KEY"=>"excitel",
    "PRIVATE_KEY"=>"kjkjk&hsTT56"
);