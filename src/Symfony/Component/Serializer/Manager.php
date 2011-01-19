<?php

namespace Symfony\Component\Serializer;

use Symfony\Component\Serializer\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;

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
 * $serializer->serialize($obj, 'xml', array('field','field2'))
 * $serializer->deserialize($data, 'Foo\Bar', 'xml')
 *
 * TODO:
 * - Use Validator comp to check which fields are mandatory during deserialization (?)
 *   - Alternatively we could use .NET-style @serialize:NonSerialized and @serialize:OptionalField on properties
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
class Manager
{
    protected $serializers = array();
    protected $encoders = array();
    protected $serializerCache = array();

    public function serialize($data, $format)
    {
        if (!is_scalar($data)) {
            $data = $this->normalize($data, $format);
        }
        return $this->encode($data, $format);
    }

    public function serializeObject($object, $format, $properties = null)
    {
        $class = get_class($object);
        if (isset($this->serializercache[$class][$format])) {
            return $serializer->serialize($object, $format, $properties);
        }
        foreach ($this->serializers as $serializer) {
            if ($serializer->supports($class, $format)) {
                $this->serializercache[$class][$format] = $serializer;
                return $serializer->serialize($object, $format, $properties);
            }
        }
        throw new \UnexpectedValueException('Could not serialize object of type '.$class);
    }

    public function deserializeObject($data, $class, $format = null)
    {
        if (isset($this->serializercache[$class][$format])) {
            return $serializer->deserialize($data, $format);
        }
        $reflClass = new \ReflectionClass($class);
        foreach ($this->serializers as $serializer) {
            if ($serializer->supports($reflClass, $format)) {
                $this->serializercache[$class][$format] = $serializer;
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

    public function encode($data, $format)
    {
        if (!isset($this->encoders[$format])) {
            throw new \UnexpectedValueException('Could not find an encoder for the '.$format.' format');
        }
        return $this->encoders[$format]->encode($data);
    }

    public function decode($data, $format = null)
    {
        if (null === $format) {
            $format = $this->guessFormat($data);
        }
        if (!isset($this->encoders[$format])) {
            throw new \UnexpectedValueException('Could not find a decoder for the '.$format.' format');
        }
        return $this->encoders[$format]->decode($data);
    }

    public function guessFormat($data)
    {
        foreach ($this->encoders as $format => $encoder) {
            if ($encoder->supports($data)) {
                return $format;
            }
        }
        throw new \UnexpectedValueException('The format could not be determined');
    }

    public function addSerializer(SerializerInterface $serializer)
    {
        $this->serializers[] = $serializer;
        $serializer->setManager($this);
    }

    public function getSerializers()
    {
        return $this->serializers;
    }

    public function removeSerializer(SerializerInterface $serializer)
    {
        unset($this->serializers[array_search($serializer, $this->serializers, true)]);
    }

    public function addEncoder($format, EncoderInterface $encoder)
    {
        $this->encoders[$format] = $encoder;
        $encoder->setManager($this);
    }

    public function getEncoders()
    {
        return $this->encoders;
    }

    public function removeEncoder($format)
    {
        unset($this->encoders[$format]);
    }
}
