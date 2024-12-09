<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Models\GetPlans;
use PDO;
use PDOStatement;

class GetPlansTest extends TestCase
{
    private $pdoMock;
    private $statementMock;
    private $getPlans;

    protected function setUp(): void
    {
        // Create a mock for the PDO class
        $this->pdoMock = $this->createMock(PDO::class);
        
        // Create a mock for the PDOStatement class
        $this->statementMock = $this->createMock(PDOStatement::class);
        
        // Set up the GetPlans instance with a mocked PDO object
        $this->getPlans = new GetPlans();
        $this->getPlans->db = $this->pdoMock; // Assuming `db` is a public property or accessible property
    }

    /**
     * Test for getStatusStats() when data is returned from the database
     */
    public function testGetStatusStatsReturnsValidData()
    {
        // Sample data to be returned from the fetch() call
        $expectedResult = (object) [
            'activeCount' => 10,
            'inactiveCount' => 5
        ];

        // Set up the mock statement to return the expected result
        $this->statementMock
            ->method('fetch')
            ->willReturn($expectedResult);

        // Set up the mock PDO to return the mock statement on prepare()
        $this->pdoMock
            ->method('prepare')
            ->willReturn($this->statementMock);

        // Call the method and assert the result
        $result = $this->getPlans->getStatusStats();

        $this->assertEquals($expectedResult, $result, 'getStatusStats() should return the correct data from the database.');
    }

    /**
     * Test for getStatusStats() when no data is returned from the database
     */
    public function testGetStatusStatsReturnsDefaultDataWhenNoResults()
    {
        // Set up the mock statement to return false (as if no row was found)
        $this->statementMock
            ->method('fetch')
            ->willReturn(false);

        // Set up the mock PDO to return the mock statement on prepare()
        $this->pdoMock
            ->method('prepare')
            ->willReturn($this->statementMock);

        // Call the method and assert the result
        $result = $this->getPlans->getStatusStats();

        // Expected result when no row is found
        $expectedResult = (object) [
            'activeCount' => 0,
            'inactiveCount' => 0
        ];

        $this->assertEquals($expectedResult, $result, 'getStatusStats() should return default data when no database rows are returned.');
    }

    /**
     * Test for getList() when it successfully returns data
     */
    // public function testGetListReturnsData()
    // {
    //     // Sample data to be returned from the fetchAll() call
    //     $expectedResult = [
    //         (object) ['plan_id' => 1, 'plan_name' => 'Plan A'],
    //         (object) ['plan_id' => 2, 'plan_name' => 'Plan B']
    //     ];

    //     // Set up the mock statement to return the expected result
    //     $this->statementMock
    //         ->method('fetchAll')
    //         ->willReturn($expectedResult);

    //     // Set up the mock PDO to return the mock statement on prepare()
    //     $this->pdoMock
    //         ->method('prepare')
    //         ->willReturn($this->statementMock);

    //     // Call the method with dummy filters (adjust based on the real method signature)
    //     $filters = ['status' => 'active']; // Replace with the actual filter parameters if required
    //     $result = $this->getPlans->getList($filters);

    //     $this->assertEquals($expectedResult, $result, 'getList() should return the correct data from the database.');
    // }

    /**
     * Test for getList() when no data is returned from the database
     */
    // public function testGetListReturnsEmptyArrayWhenNoResults()
    // {
    //     // Set up the mock statement to return an empty array
    //     $this->statementMock
    //         ->method('fetchAll')
    //         ->willReturn([]);

    //     // Set up the mock PDO to return the mock statement on prepare()
    //     $this->pdoMock
    //         ->method('prepare')
    //         ->willReturn($this->statementMock);

    //     // Call the method with dummy filters (adjust based on the real method signature)
    //     $filters = ['status' => 'inactive']; // Replace with the actual filter parameters if required
    //     $result = $this->getPlans->getList($filters);

    //     $this->assertEquals([], $result, 'getList() should return an empty array when no rows are found.');
    // }

    /**
     * Test for PDO Exceptions during database operations
     */
    public function testPDOExceptionIsThrown()
    {
        // Configure the PDO to throw an exception on prepare()
        $this->pdoMock
            ->method('prepare')
            ->will($this->throwException(new \PDOException('Database connection failed')));

        $this->expectException(\PDOException::class);
        $this->expectExceptionMessage('Database connection failed');

        // Call a method that will trigger the exception
        $this->getPlans->getStatusStats();
    }
}
