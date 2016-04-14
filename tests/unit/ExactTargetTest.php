<?php

require_once("././ExactTarget.php");
use Codeception\Util\Stub;

class ExactTargetTest extends \Codeception\TestCase\Test
{
    public function testIstancesProperty()
    {

        $et = new ExactTarget("", "");

        $this->assertTrue(property_exists('ExactTarget', 'instances'), "instances property exists");

        $this->assertTrue(is_array($et::$instances), "instances property is an array");

        $this->assertEquals(5, sizeof($et::$instances), "the number of server instances is 5");
    }

    public function testMethodsProperty()
    {

        $et = new ExactTarget("", "");

        $this->assertTrue(property_exists('ExactTarget', 'methods'), "methods property exists");

        $this->assertTrue(is_array($et::$methods), "methods property is an array");

        $this->assertEquals(5, sizeof($et::$methods), "the number of methods is 5");
    }


    public function testRequestMethod()
    {

        $et = new ExactTarget("", "");

        $this->assertTrue(method_exists('ExactTarget', 'request'), "retrieve method exists");


    }

    public function testRetrieveMethod()
    {

        $et = new ExactTarget("", "");

        $this->assertTrue(method_exists('ExactTarget', 'retrieve'), "retrieve method exists");


    }
}
?>
