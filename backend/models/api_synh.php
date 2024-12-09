<?php
/*
::PLEASE READ ME::
After receiving the data from external API we will store it into temporary table with the same structre like the table contains current data
This would be the best way to compare two big sets of data. I believe that the idea to pull all the records every time (except deleted) 
is just for purpose of the test task
But will presume that similar situation could exists in real life e.g. we will assume that returned array can grow to tousands records.
Comparing item by item lead to few major problems: 
    1. We can't link item from our data and api data directly but have to convert both of them into associative arrays (using _id) 
    2. Comparing objects property by property is very time consuming 
    3. Mising records in one of the arrays can be found only looping the other array
My solution:
    1. Convert api data into array of InternetPlanItem objects. Appart of all validations and sanity this object has additional property "checksum"
    2. Create a temp table with the same structure like "plans" (the table containing current plans) and store api data in it. 
        This table has unique key on checksum field
    3. Use simple queries to compare both tables (using already calculated checksum) will give us records that must be updated, deleted or inserted     
*/

declare(strict_types=1);

include_once(BASE_PATH . "/models/internet_plan.php");

class APISynh extends commonModel
{
    private array $extData;

    private function getExternalData(): void
    {
        global $API_LIVE_DATA;

        $req = json_encode((object)array(
            "dt" => time(),
            "method" => "getList"
        ));

        $hash = hash_hmac('sha256', $req, $API_LIVE_DATA->PRIVATE_KEY);
        // Data to send
        $data = [
            'api_client' => $API_LIVE_DATA->PUBLIC_KEY,
            'hash'       => $hash,
            'req'        => $req
        ];
        // Initialize cURL
        $ch = curl_init($API_LIVE_DATA->URL);

        // Set cURL options
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // $streamVerboseHandle = fopen('php://temp', 'w+');
        // curl_setopt($ch, CURLOPT_STDERR, $streamVerboseHandle);
        // curl_setopt($ch, CURLOPT_VERBOSE, true);
        

        // Execute the request and fetch the response
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {

    //         curl_close($ch);
    //         rewind($streamVerboseHandle);
    //         $verboseLog = stream_get_contents($streamVerboseHandle);

    //         echo "cUrl verbose information:\n", 
    //  "<pre>", htmlspecialchars($verboseLog), "</pre>\n";

             $this->getMockupData();
             return;
        }

        // Close the cURL session
        curl_close($ch);

        //Expected format {"result":OK/error, "data":[]}
        $res = json_decode($response);
        if (!$res || !$res->result || $res->result == "error") {
            
             $this->getMockupData();
             return;
        }

        //this ensure we wouldn't use pointer
        $this->extData = json_decode(json_encode($res->data));
    }

    private function getMockupData(): void
    {
        /*
        ::PLEASE READ ME::
        I really don't understand the idea to have second mockup server. /external_api/ itself is a mockup server. 
        Maybe the idea was to use https://json-generator.com/ like an external API but I didn't found any way to do it
        So the code bellow just demonstrate switching to second mockup server if the first one fails 
        */
        global $API_MOCK_DATA;

        $req = json_encode((object)array(
            "dt" => time(),
            "method" => "getList"
        ));

        $hash = hash_hmac('sha256', $req, $API_MOCK_DATA->PRIVATE_KEY);
        // Data to send
        $data = [
            'api_client' => $API_MOCK_DATA->PUBLIC_KEY,
            'hash'       => $hash,
            'req'        => $req
        ];
        // Initialize cURL
        $ch = curl_init($API_MOCK_DATA->URL);

        // Set cURL options
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the request and fetch the response
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {

            throw new ErrorException("API service is down");
        }

        // Close the cURL session
        curl_close($ch);

        //Expected format {"result":OK/error, "data":[]}
        $res = json_decode($response);
        if (!$res || !$res->result || $res->result == "error") {
            throw new ErrorException("API service is down");
        }

        //this ensure we wouldn't use pointer
        $this->extData = json_decode(json_encode($res->data));
    }

    private function createTempTable()
    {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) AS table_count
                                   FROM information_schema.tables 
                                   WHERE table_schema = 'excitel' 
                                     AND table_name = 'tmp_plans'");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_OBJ);

            if ($result->table_count > 0) {
                $this->dropTempTable();
            }

            $createStmt = "CREATE TABLE tmp_plans LIKE plans;";
            $this->db->exec($createStmt);
        } catch (PDOException $e) {
            // Handle PDO exceptions (database errors)
            echo "Database Error: " . $e->getMessage();
        } catch (Exception $e) {
            // Handle general exceptions
            echo $e->getMessage();
        }
    }

    private function dropTempTable()
    {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) AS table_count
                                   FROM information_schema.tables 
                                   WHERE table_schema = 'excitel' 
                                     AND table_name = 'tmp_plans'");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_OBJ);

            if ($result->table_count < 1) {

                throw new Exception("Error: The table tmp_plans doesn't exists.");
            } else {

                $dropStmt = "DROP TABLE tmp_plans;";
                $this->db->exec($dropStmt);
            }
        } catch (PDOException $e) {
            // Handle PDO exceptions (database errors)
            echo "Database Error: " . $e->getMessage();
        } catch (Exception $e) {
            // Handle general exceptions
            echo $e->getMessage();
        }
    }

    private function insertIntoTempTable()
    {

        $sthIns = $this->db->prepare("
            INSERT INTO `tmp_plans` (`_id`, `guid`, `name`, `plan_status`, `price`, `plan_type`, `category`, `tags`, `checksum`) 
            VALUES (:_id, :guid, :name, :plan_status, :price, :plan_type, :category, :tags, :checksum)
        ");

        foreach ($this->extData as $itm) {
            try {
                $planItm = InternetPlanItem::fromAPIEntry($itm);
            } catch (Exception $e) {
                //TODO:: add in error log
            }
            $sthIns->bindValue(":_id", $planItm->_id);
            $sthIns->bindValue(":guid", $planItm->guid);
            $sthIns->bindValue(":name", $planItm->name);
            $sthIns->bindValue(":plan_status", $planItm->plan_status);
            $sthIns->bindValue(":price", $planItm->price);
            $sthIns->bindValue(":plan_type", $planItm->plan_type);
            $sthIns->bindValue(":category", $planItm->category);
            $sthIns->bindValue(":tags", $planItm->tags);
            $sthIns->bindValue(":checksum", $planItm->checksum);
            $sthIns->execute();
        }
    }

    private function insertMissingRecords()
    {

        $sthIns = $this->db->prepare("
            INSERT INTO plans (`_id`, `guid`, `name`, `plan_status`, `price`, `plan_type`, `category`, `tags`, `checksum`) 
                SELECT t2._id, t2.guid, t2.name, t2.plan_status, t2.price, t2.plan_type, t2.category, t2.tags, t2.checksum
                FROM tmp_plans t2
                LEFT JOIN plans t1 ON t1._id=t2._id
                WHERE t1.id is NULL
        ");

        $sthIns->execute();
    }

    private function updateChangedRecords()
    {

        $sth = $this->db->prepare("
            UPDATE plans p
            JOIN tmp_plans t ON p._id = t._id
            SET 
                p.guid = t.guid,
                p.name = t.name,
                p.plan_status = t.plan_status,
                p.price = t.price,
                p.plan_type = t.plan_type,
                p.category = t.category,
                p.tags = t.tags,
                p.checksum = t.checksum,
                p.is_deleted = 'N'
            WHERE 
                NOT (p.checksum <=> t.checksum);
        ");

        $sth->execute();
    }

    private function deleteRedundantRecords()
    {
        /*
        ::PLEASE READ ME::
        In the task is missing a case when some record is maked like deleted but later it is returned by API again.
        In this case insertMissingRecords wouldn't catch it. If data fields aren't changed then updateChangedRecords wouldn't trigger either
        The easiest way to prevent this is to simply change checksum during the deletion in this way updateChangedRecords will catch it
        Because we have unique key on this field we must update it with unique value. Considering the fact that checksum field is much longer than _id
        and _id is unique - we can just concatinate with sufix or prefix
        */

        $sth = $this->db->prepare("
            UPDATE plans p
            LEFT JOIN tmp_plans t ON p._id = t._id
            SET p.is_deleted = 'Y',
            p.checksum = CONCAT('del-',p._id)
            WHERE t._id IS NULL;
        ");

        $sth->execute();
    }


    public function proceed(): void
    {
        try {
            $this->getExternalData();
            // return $this->extData;
        } catch (ErrorException $e) {
            //TODO: mockup proxy
            echo $e;
        }
        $this->createTempTable();
        $this->insertIntoTempTable();
        $this->insertMissingRecords();
        $this->updateChangedRecords();
        $this->deleteRedundantRecords();
        $this->dropTempTable();
    }
}
