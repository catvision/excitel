<?php

declare(strict_types=1);
namespace App\Models;
use PDO;
include_once(BASE_PATH . "/models/internet_plan.php");

class GetPlans extends CommonModel
{
    public function getStatusStats()
    {
        $sth = $this->db->prepare("
            SELECT 
                SUM(
                    CASE 
                        WHEN plan_status='Active' then 1
                        ELSE 0
                    END
                ) AS activeCount,
                SUM(
                    CASE 
                        WHEN plan_status='Inactive' then 1
                        ELSE 0
                    END
                ) AS inactiveCount
            FROM plans
            WHERE is_deleted='N'
        ");
        $sth->execute();
        if ($res = $sth->fetch(\PDO::FETCH_OBJ)) {
            return $res;
        } else {
            return (object)array(
                "activeCount" => 0,
                "inactiveCount" => 0
            );
        }
    }

    public function getList(Filters $filts): \stdClass
    {

        $qf = "";
        if ($filts->filterType == "byName" && $filts->substr !== '') {
            $qf = "
                AND `name` like :filtString
           ";
        }
        if ($filts->filterType == "byCategory" && $filts->substr !== '') {
            $qf = "
                AND `category` like :filtString
           ";
        }


        $q = "SELECT 
                    *
                FROM plans
                WHERE is_deleted='N' 
                $qf
                LIMIT :page,5
                ";



        $sth = $this->db->prepare($q);
        if ($qf != "") {
            $sth->bindValue(':filtString', '%' . $filts->subString . '%', \PDO::PARAM_STR);
        }
        $sth->bindValue(":page", $filts->page);
        $sth->execute();
        $items = [];
        $sth->setFetchMode(PDO::FETCH_CLASS, 'App\Models\InternetPlanItem');
        while ($r = $sth->fetch()) {
            $items[] = $r->toStdCls();
        }

        // Calculate total amount with this filter
        $qCnt = "SELECT count(id) as recCount
                FROM plans
                WHERE is_deleted='N'
                $qf
                ";



        $sthCnt = $this->db->prepare($qCnt);
        if ($qf != "") {
            $sthCnt->bindValue(':filtString', '%' . $filts->subString . '%', \PDO::PARAM_STR);
        }
        $sthCnt->execute();
        $rowsCount = 0;
        if ($r = $sthCnt->fetch(\PDO::FETCH_OBJ)) {
            $rowsCount = $r->recCount;
        }

        return (object)array(
            "rowsCount" => $rowsCount,
            "items" => $items
        );
    }
}

class Filters extends CommonModel
{

    public int $page;
    public string $subString;
    public string $filterType;
    private array $enumFilterTypes;
    public function __construct()
    {
        parent::__construct();
        $this->enumFilterTypes = ["byName", "byCategory"];
    }

    public static function fromPOST(array $filters): Filters
    {

        $res = new self();
        if (!isset($filters["page"])) {
            $res->page = 0;
        } else {
            $res->page = intval($filters["page"]);
        }
        if (!isset($filters["filterType"])) {
            $res->filterType = "byName";
        } else {
            $res->filterType = $filters["filterType"];
        }

        if (!in_array($res->filterType, $res->enumFilterTypes)) {
            $res->retError("Invalid filter type");
        }
        if (!isset($filters["subString"])) {
            $res->subString = '';
        } else {
            $res->subString = trim($filters["subString"]);
        }


        return $res;
    }
}
