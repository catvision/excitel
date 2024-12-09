<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
use App\Models\InternetPlanItem;
require_once __DIR__ . '/backend/models/common.php';
require_once __DIR__ . '/backend/models/internet_plan.php';
$internetPlanItem = new InternetPlanItem();
$internetPlanItem->enumStatuses="aaaaa";