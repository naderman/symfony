<?php

namespace Symfony\Component\Serializer;

/*
 * This file is part of the Symfony framework.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * Serializer serializes and unserializes data
 *
 * objects are turned into arrays by serializers, and vice versa.
 * arrays are turned into various output formats by encoders
 *
 * $serializer->serialize('format', $obj, array('field','field2'))
 * $serializer->deserialize('format', $obj, array('field','field2'))
 *
 * TODO:
 * - Use Validator comp to check which fields are mandatory during deserialization (?)
 *   - Alternatively we could use .NET-style @serialize:NonSerialized and @serialize:OptionalField on properties
 * - Add a Deserializable interface implementing fromArray($array, $format)
 * - Add a Serializable interface implementing toArray($format)
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
interface ManagerInterface
{
    function serialize($object, $format);
    function deserialize($data, $format = null);
}
