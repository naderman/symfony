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

    public function serialize($object, $format)
    {
        return $object->toScalar($this, $format);
    }

    public function deserialize($data, $format = null)
    {
        return $object->fromScalar($this, $data, $format);
    }

    public function supports($object, $format = null)
    {
        return $object instanceof ScalarSerializable;
    }

    public function setManager($manager)
    {
        $this->manager = $manager;
    }

    public function getManager($manager)
    {
        return $this->manager;
    }
}
