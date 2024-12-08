<?php

declare(strict_types=1);
class InternetPlanItem extends commonModel
{

    public int $id;
    public string $_id;
    public string $guid;
    public string $name;
    public string $plan_status;
    public float $price;
    public string $plan_type;
    public string $category;
    public string $tags;
    public string $checksum;
    public string $is_deleted;

    //private properties
    private array $enumStatuses;
    private array $enumTypes;
    private array $enumCategories;

    public function __construct()
    {

        parent::__construct();

        $this->enumTypes = ['Lan', 'Fiber'];
        $this->enumStatuses = ['Active', 'Inactive'];
        $this->enumCategories = ['Weekly', 'Monthly', 'Quarterly'];
    }

    public function __get(string $var)
    {

        return parent::__get($var);
    }

    public function __set(string $var, $val): void {}

    public static function fromAPIEntry(stdClass $entry): InternetPlanItem
    {

        $res = new self();
        $res->id = -1; //this is generated by auto increment into db here is ignored
        $res->_id = $entry->_id;
        $res->guid = $entry->guid;
        $res->name = $entry->name;

        //validate plan status
        if (!in_array($entry->status, $res->enumStatuses)) {
            throw new \ErrorException("Invalid status", 0, E_USER_ERROR);
        }
        $res->plan_status = $entry->status;

        //convert price
        $cleaned = preg_replace('/[^\d.]/', '', $entry->price);
        $res->price = round((float)$cleaned, 2);

        //validate plan type
        if (!in_array($entry->type, $res->enumTypes)) {
            throw new \ErrorException("Invalid type", 0, E_USER_ERROR);
        }
        $res->plan_type = $entry->type;

        //validate plan category
        if (!in_array($entry->category, $res->enumCategories)) {
            throw new \ErrorException("Invalid category", 0, E_USER_ERROR);
        }
        $res->category = $entry->category;

        //we will sort tags before to store them because different order will give a different checksum
        if (!is_array($entry->tags)) {
            $entry->tags = [];
        }
        sort($entry->tags);
        $res->tags = implode(",", $entry->tags);

        $res->is_deleted = 'N';

        $res->checksum = md5($res->_id . $res->guid . $res->name . $res->plan_status . $res->price . $res->plan_type . $res->category . $res->tags . $res->is_deleted);

        return $res;
    }

    public function toJSON(): string
    {

        $res = (object)array(
            'id'          => $this->id,
            '_id'         => $this->_id,
            'guid'        => $this->guid,
            'name'        => $this->name,
            'plan_status' => $this->plan_status,
            'price'       => $this->price,
            'plan_type'   => $this->plan_type,
            'category'    => $this->category,
            'tags'        => $this->tags
        );

        return json_encode($res) ;
    }

    public function toStdCls(): stdClass
    {

        $res = (object)array(
            'id'          => $this->id,
            '_id'         => $this->_id,
            'guid'        => $this->guid,
            'name'        => $this->name,
            'plan_status' => $this->plan_status,
            'price'       => $this->price,
            'plan_type'   => $this->plan_type,
            'category'    => $this->category,
            'tags'        => $this->tags
        );

        return $res ;
    }
}