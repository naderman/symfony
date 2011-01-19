<?php

namespace Symfony\Tests\Component\Serializer\Serializer;

use Symfony\Component\Serializer\Serializer\ScalarSerializer;
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

class ScalarSerializerTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->serializer = new ScalarSerializer;
        $this->serializer->setManager($this->getMock('Symfony\Component\Serializer\Manager'));
    }

    public function testSerialize()
    {
        $obj = new Dummy;
        $obj->foo = 'foo';
        $obj->xmlFoo = 'xml';
        $this->assertEquals('foo', $this->serializer->serialize($obj, 'json'));
        $this->assertEquals('xml', $this->serializer->serialize($obj, 'xml'));
    }

    public function testDeserialize()
    {
        $obj = $this->serializer->deserialize('foo', __NAMESPACE__.'\Dummy', 'xml');
        $this->assertEquals('foo', $obj->xmlFoo);
        $this->assertNull($obj->foo);

        $obj = $this->serializer->deserialize('foo', __NAMESPACE__.'\Dummy', 'json');
        $this->assertEquals('foo', $obj->foo);
        $this->assertNull($obj->xmlFoo);
    }

    public function testSupports()
    {
        $this->assertTrue($this->serializer->supports(new \ReflectionClass(get_class(new Dummy))));
        $this->assertFalse($this->serializer->supports(new \ReflectionClass('stdClass')));
    }
}

class Dummy implements ScalarSerializable
{
    public $foo;
    public $xmlFoo;

    public function toScalar(SerializerInterface $serializer, $format, $properties = null)
    {
        return $format === 'xml' ? $this->xmlFoo : $this->foo;
    }

    public function fromScalar(SerializerInterface $serializer, $data, $format = null)
    {
        if ($format === 'xml') {
            $this->xmlFoo = $data;
        } else {
            $this->foo = $data;
        }
    }
}