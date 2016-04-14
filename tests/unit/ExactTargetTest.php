<?php

require_once("././ExactTarget.php");
use Codeception\Util\Stub;

class ExactTargetTest extends \Codeception\TestCase\Test
{

    public function testConstructor()
    {

        $et = new ExactTarget("testUsername", "testPassword");

        $this->assertTrue(method_exists('ExactTarget', '__construct'), "retrieve method exists");

        $method = new ReflectionMethod('ExactTarget', '__construct');
        $num = $method->getNumberOfParameters();

        $this->assertEquals(4, $num, "has 4 function arguments");

        $this->assertEquals('testUsername', $et->username, "username is set correctly");
        $this->assertEquals('testPassword', $et->password, "password is set correctly");

    }

    public function testClientProperty()
    {

        $et = new ExactTarget("", "");

        $this->assertTrue(property_exists('ExactTarget', 'client'), "client property exists");

        $this->assertTrue(is_object($et->client), "client property is an object");

        $et = new ExactTarget("testUsername", "testPassword");
        $this->assertEquals('testUsername', $et->client->username, "client username is set correctly");
        $this->assertEquals('testPassword', $et->client->password, "client password is set correctly");

    }

    public function testMIDProperty()
    {

        $et = new ExactTarget("", "");

        $this->assertTrue(property_exists('ExactTarget', 'mid'), "mid property exists");
        $this->assertEquals(null, $et->mid, "mid is null when not provided in constructor");

        $et = new ExactTarget("", "", "default", '123456' );
        $this->assertEquals('123456', $et->mid, "mid is not null when not provided in constructor");
    }

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

        $this->assertTrue(method_exists('ExactTarget', 'request'), "request method exists");

        $method = new ReflectionMethod('ExactTarget', 'request');
        $num = $method->getNumberOfParameters();

        $this->assertEquals(3, $num, "has 3 function arguments");

    }

    public function testRetrieveMethod()
    {

        $et = new ExactTarget("", "");

        $this->assertTrue(method_exists('ExactTarget', 'retrieve'), "retrieve method exists");

        $method = new ReflectionMethod('ExactTarget', 'retrieve');
        $num = $method->getNumberOfParameters();
        $this->assertEquals(2, $num, "has 2 function arguments");

    }

    public function testCreateMethod()
    {

        $et = new ExactTarget("", "");

        $this->assertTrue(method_exists('ExactTarget', 'create'), "create method exists");

        $method = new ReflectionMethod('ExactTarget', 'create');
        $num = $method->getNumberOfParameters();
        $this->assertEquals(1, $num, "has 1 function argument");

    }

    public function testUpdateMethod()
    {

        $et = new ExactTarget("", "");

        $this->assertTrue(method_exists('ExactTarget', 'update'), "update method exists");

        $method = new ReflectionMethod('ExactTarget', 'update');
        $num = $method->getNumberOfParameters();
        $this->assertEquals(1, $num, "has 1 function argument");

    }

    public function testSearchMethod()
    {

        $et = new ExactTarget("", "");

        $this->assertTrue(method_exists('ExactTarget', 'search'), "search method exists");

        $method = new ReflectionMethod('ExactTarget', 'search');
        $num = $method->getNumberOfParameters();
        $this->assertEquals(2, $num, "has 2 function argument");

    }
}
?>
