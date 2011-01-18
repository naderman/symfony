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
}
