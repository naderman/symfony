<?php

namespace Symfony\Tests\Component\Serializer;

use Symfony\Component\Serializer\Manager;
use Symfony\Component\Serializer\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Serializer\ScalarSerializable;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

/*
 * This file is part of the Symfony framework.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

class ManagerTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->manager = new Manager();
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testSerializeObjectNoMatch()
    {
        $this->manager->serializeObject(new \stdClass, 'xml');
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testDeserializeObjectNoMatch()
    {
        $this->manager->deserializeObject('foo', 'stdClass');
    }

    public function testSerializeScalar()
    {
        $this->manager->addEncoder('json', new JsonEncoder());
        $result = $this->manager->serialize('foo', 'json');
        $this->assertEquals('"foo"', $result);
    }

    public function testSerializeArrayOfScalars()
    {
        $this->manager->addEncoder('json', new JsonEncoder());
        $data = array('foo', array(5, 3));
        $result = $this->manager->serialize($data, 'json');
        $this->assertEquals(json_encode($data), $result);
    }

    public function testEncode()
    {
        $this->manager->addEncoder('json', new JsonEncoder());
        $data = array('foo', array(5, 3));
        $result = $this->manager->encode($data, 'json');
        $this->assertEquals(json_encode($data), $result);
    }

    public function testDecode()
    {
        $this->manager->addEncoder('json', new JsonEncoder());
        $data = array('foo', array(5, 3));
        $result = $this->manager->decode(json_encode($data), 'json');
        $this->assertEquals($data, $result);
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testSerializeNoMatchObject()
    {
        $this->manager->serialize(new \stdClass, 'xml');
    }
}
