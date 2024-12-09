<?php

declare(strict_types=1);
namespace App\Models;

if (!defined("BASE_PATH")) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    exit;
}

include_once(BASE_PATH . '/models/get_plans.php');

$st = new GetPlans();

try {
    $res = $st->getStatusStats();
    echo json_encode($res);
} catch (\Exception $e) {
    echo json_encode((object)array(
        "status" => "error",
        "msg" => $e->getMessage()
    ));
}
