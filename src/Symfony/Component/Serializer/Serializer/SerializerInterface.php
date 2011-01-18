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
 * Defines the interface of serializers
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
interface SerializerInterface
{
    function serialize($object, $format);
    function deserialize($data, $class, $format = null);
    function supports(\ReflectionClass $class, $format = null);
    function setManager($manager);
    function getManager();
}
