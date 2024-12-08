<?php
declare(strict_types=1);

if (!defined("BASE_PATH")){
  header('HTTP/1.0 403 Forbidden', true, 403);
 exit;
}


include_once(BASE_PATH."/models/internet_plan.php");
include_once(BASE_PATH."/models/api_synh.php");

$API = new APISynh();
$API->proceed();
/*
$x = '{
      "index": 18,
      "_id": "6751baff8aab6e5653de4a95",
      "guid": "b09e5d41-fb69-42e6-b88d-3a064574d446",
      "name": "Internet Plan #18",
      "status": "Inactive",
      "price": "$75.75",
      "type": "Lan",
      "category": "Quarterly",
      "tags": [
        "tag1",
        "tag5",
        "tag4"
      ]
    }';
$ent = json_decode($x);


$plan = InternetPlanItem::fromAPIEntry($ent);


echo "<pre>";
echo $plan->toJSON();
echo "<pre>";
*/