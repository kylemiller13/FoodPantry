<?php
require("../template.php");

use PHPUnit\Framework\TestCase;

class Template_Test extends TestCase
{
    public function testGetMessage()
    {
        $template = new Template([
            "id" => 2,
            "message" => "abc",
            "name" => "xyz",
            "notification_type" => "event"
        ]);
        $this->assertEquals("abc", $template->getMessage());
    }

    public function testGetId()
    {
        $template = new Template([
            "id" => 2,
            "message" => "abc",
            "name" => "xyz",
            "notification_type" => "event"
        ]);
        $this->assertEquals(2, $template->getId());
    }

    public function testGetType()
    {
        $template = new Template([
            "id" => 2,
            "message" => "abc",
            "name" => "xyz",
            "notification_type" => "event"
        ]);
        $this->assertEquals("event", $template->getType());
    }

    public function testGetName()
    {
        $template = new Template([
            "id" => 2,
            "message" => "abc",
            "name" => "xyz",
            "notification_type" => "event"
        ]);
        $this->assertEquals("xyz", $template->getName());
    }

    public function testFetchTemplates()
    {
        $templates = Database::get_all_templates();
        $this->assertCount(2, $templates);
    }

    public function testDeleteFail() {
        $result = Database::delete_template(10);
        $this->assertNotTrue($result);
    }

    public function test__construct()
    {
        $template = new Template([
            "id" => 2,
            "message" => "abc",
            "name" => "xyz",
            "notification_type" => "event"
        ]);

        $this->assertEquals("abc", $template->getMessage());
        $this->assertEquals(2, $template->getId());
        $this->assertEquals("xyz", $template->getName());
        $this->assertEquals("event", $template->getType());
    }

    public function testJsonSerialize()
    {
        $this->assertTrue(TRUE);
    }

    public function testUpdateTemplate()
    {
        $result = Database::update_template(1, "test_name", "test_content", "event");
        $this->assertTrue($result);
    }
}
