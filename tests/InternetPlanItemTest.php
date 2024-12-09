<?php

use PHPUnit\Framework\TestCase;
use App\Models;
use App\Models\InternetPlanItem;



class InternetPlanItemTest extends TestCase
{
    private $internetPlanItem;

    protected function setUp(): void
    {
        // Create an instance of the InternetPlanItem before each test
        $this->internetPlanItem = new InternetPlanItem();
    }

    /**
     * Test if the InternetPlanItem object can be instantiated
     */
    public function testInternetPlanItemCanBeInstantiated()
    {
        $this->assertInstanceOf(InternetPlanItem::class, $this->internetPlanItem);
    }

    /**
     * Test default values of public properties
     */
    public function testDefaultValues()
    {
        $this->assertNull($this->internetPlanItem->id ?? null, 'ID should be null by default');
        $this->assertNull($this->internetPlanItem->_id ?? null, '_ID should be null by default');
        $this->assertNull($this->internetPlanItem->guid ?? null, 'GUID should be null by default');
        $this->assertNull($this->internetPlanItem->name ?? null, 'Name should be null by default');
        $this->assertNull($this->internetPlanItem->plan_status ?? null, 'Plan status should be null by default');
        $this->assertNull($this->internetPlanItem->price ?? null, 'Price should be null by default');
        $this->assertNull($this->internetPlanItem->plan_type ?? null, 'Plan type should be null by default');
        $this->assertNull($this->internetPlanItem->category ?? null, 'Category should be null by default');
        $this->assertNull($this->internetPlanItem->tags ?? null, 'Tags should be null by default');
        $this->assertNull($this->internetPlanItem->checksum ?? null, 'Checksum should be null by default');
        $this->assertNull($this->internetPlanItem->is_deleted ?? null, 'Is_deleted should be null by default');
    }

    /**
     * Test if properties can be set and retrieved
     */
    public function testPropertyAssignment()
    {
        $this->internetPlanItem->id = 123;
        $this->internetPlanItem->_id = 'abc123';
        $this->internetPlanItem->guid = '550e8400-e29b-41d4-a716-446655440000';
        $this->internetPlanItem->name = 'Premium Plan';
        $this->internetPlanItem->plan_status = 'active';
        $this->internetPlanItem->price = 49.99;
        $this->internetPlanItem->plan_type = 'monthly';
        $this->internetPlanItem->category = 'internet';
        $this->internetPlanItem->tags = 'fast,unlimited';
        $this->internetPlanItem->checksum = 'abc123xyz';
        $this->internetPlanItem->is_deleted = 'false';

        $this->assertEquals(123, $this->internetPlanItem->id);
        $this->assertEquals('abc123', $this->internetPlanItem->_id);
        $this->assertEquals('550e8400-e29b-41d4-a716-446655440000', $this->internetPlanItem->guid);
        $this->assertEquals('Premium Plan', $this->internetPlanItem->name);
        $this->assertEquals('active', $this->internetPlanItem->plan_status);
        $this->assertEquals(49.99, $this->internetPlanItem->price);
        $this->assertEquals('monthly', $this->internetPlanItem->plan_type);
        $this->assertEquals('internet', $this->internetPlanItem->category);
        $this->assertEquals('fast,unlimited', $this->internetPlanItem->tags);
        $this->assertEquals('abc123xyz', $this->internetPlanItem->checksum);
        $this->assertEquals('false', $this->internetPlanItem->is_deleted);
    }

    /**
     * Test enum-related private properties if applicable
     */
    public function testPrivateEnumPropertiesExist()
    {
        $reflection = new ReflectionClass($this->internetPlanItem);
        $this->assertTrue($reflection->hasProperty('enumStatuses'), 'enumStatuses property should exist');
        $this->assertTrue($reflection->hasProperty('enumTypes'), 'enumTypes property should exist');
        $this->assertTrue($reflection->hasProperty('enumCategories'), 'enumCategories property should exist');
    }

    /**
     * Test private properties cannot be accessed directly
     */
    // public function testPrivatePropertiesAreNotAccessible()
    // {
    //     $this->expectError();
    //     echo $this->internetPlanItem->enumStatuses;
    // }

    /**
     * Test that the InternetPlanItem inherits from CommonModel
     */
    public function testInheritanceFromCommonModel()
    {
        $this->assertInstanceOf(\App\Models\CommonModel::class, $this->internetPlanItem);
    }
}
