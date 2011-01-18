<?php

namespace Symfony\Tests\Component\Serializer;

use Symfony\Component\Serializer\Manager;
use Symfony\Component\Serializer\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Serializer\ScalarSerializable;

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
        $result = $this->manager->serialize('foo', 'xml');
        $this->assertEquals('foo', $result);
    }

    public function testSerializeArrayOfScalars()
    {
        $data = array('foo', array(5, 3));
        $result = $this->manager->serialize($data, 'xml');
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
