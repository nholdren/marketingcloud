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

        $method = new ReflectionMethod('ExactTarget', 'request');
        $num = $method->getNumberOfParameters();

        $this->assertEquals(3, $num, "has 3 function arguments");

        $this->assertTrue(method_exists('ExactTarget', 'request'), "retrieve method exists");


    }

    public function testRetrieveMethod()
    {

        $et = new ExactTarget("", "");

        $method = new ReflectionMethod('ExactTarget', 'retrieve');
        $num = $method->getNumberOfParameters();
        $this->assertEquals(2, $num, "has 2 function arguments");

        $this->assertTrue(method_exists('ExactTarget', 'retrieve'), "retrieve method exists");


    }

    public function testCreateMethod()
    {

        $et = new ExactTarget("", "");

        $method = new ReflectionMethod('ExactTarget', 'create');
        $num = $method->getNumberOfParameters();
        $this->assertEquals(1, $num, "has 1 function argument");

        $this->assertTrue(method_exists('ExactTarget', 'create'), "retrieve method exists");


    }

    public function testUpdateMethod()
    {

        $et = new ExactTarget("", "");

        $method = new ReflectionMethod('ExactTarget', 'update');
        $num = $method->getNumberOfParameters();
        $this->assertEquals(1, $num, "has 1 function argument");

        $this->assertTrue(method_exists('ExactTarget', 'update'), "retrieve method exists");


    }

    public function testSearchMethod()
    {

        $et = new ExactTarget("", "");

        $method = new ReflectionMethod('ExactTarget', 'search');
        $num = $method->getNumberOfParameters();
        $this->assertEquals(2, $num, "has 2 function argument");

        $this->assertTrue(method_exists('ExactTarget', 'search'), "retrieve method exists");


    }
}
?>
