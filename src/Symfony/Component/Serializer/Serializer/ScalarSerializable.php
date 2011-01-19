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

// TODO rename this, names needed:
// Normalizable ?

/**
 * Defines the most basic interface a class must implement to be serializable
 *
 * If a serializer is registered for the class and it doesn't implement
 * any of the *Serializable interfaces, the serializer will be used instead
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
interface ScalarSerializable
{
    function toScalar(SerializerInterface $serializer, $format, $properties = null);
    function fromScalar(SerializerInterface $serializer, $data, $format = null);
}
