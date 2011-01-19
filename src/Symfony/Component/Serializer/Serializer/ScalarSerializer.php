<?php

namespace Symfony\Component\Serializer\Serializer;

/*
 * This file is part of the Symfony framework.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
class ScalarSerializer implements SerializerInterface
{
    protected $manager;

    public function serialize($object, $format, $properties = null)
    {
        return $object->toScalar($this, $format, $properties);
    }

    public function deserialize($data, $class, $format = null)
    {
        $object = new $class;
        $object->fromScalar($this, $data, $format);
        return $object;
    }

    public function supports(\ReflectionClass $class, $format = null)
    {
        return $class->implementsInterface('Symfony\Component\Serializer\Serializer\ScalarSerializable');
    }

    public function setManager($manager)
    {
        $this->manager = $manager;
    }

    public function getManager()
    {
        return $this->manager;
    }
}
