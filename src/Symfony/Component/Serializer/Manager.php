<?php

namespace Symfony\Component\Serializer;

use Symfony\Component\Serializer\Serializer\SerializerInterface;

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
class Manager
{
    protected $serializers = array();
    protected $cache = array();

    public function serialize($data, $format)
    {
        if (!is_scalar($data)) {
            $data = $this->normalize($data, $format);
        }
        // TODO encode
        return $data;
    }

    public function serializeObject($object, $format)
    {
        $class = get_class($object);
        if (isset($this->cache[$class][$format])) {
            return $serializer->serialize($object, $format);
        }
        foreach ($this->serializers as $serializer) {
            if ($serializer->supports($class, $format)) {
                $this->cache[$class][$format] = $serializer;
                return $serializer->serialize($object, $format);
            }
        }
        throw new \UnexpectedValueException('Could not serialize object of type '.$class);
    }

    public function deserializeObject($data, $class, $format = null)
    {
        if (isset($this->cache[$class][$format])) {
            return $serializer->deserialize($data, $format);
        }
        $reflClass = new \ReflectionClass($class);
        foreach ($this->serializers as $serializer) {
            if ($serializer->supports($reflClass, $format)) {
                $this->cache[$class][$format] = $serializer;
                return $serializer->deserialize($data, $class, $format);
            }
        }
        throw new \UnexpectedValueException('Could not deserialize object of type '.$class);
    }

    protected function normalize($data, $format)
    {
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                $data[$key] = is_scalar($val) ? $val : $this->normalize($val, $format);
            }
            return $data;
        }
        if (is_object($data)) {
            return $this->serializeObject($data, $format);
        }
        throw new \UnexpectedValueException('An unexpected value could not be serialized: '.var_export($data, true));
    }

    public function add(SerializerInterface $serializer)
    {
        $this->serializers[] = $serializer;
        $serializer->setManager($this);
    }

    public function all()
    {
        return $this->serializers;
    }

    public function remove(SerializerInterface $serializer)
    {
        unset($this->serializers[array_search($serializer, $this->serializers, true)]);
    }
}
