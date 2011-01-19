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
 * Defines the interface of the Manager
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
interface ManagerInterface
{
    function serialize($data, $format);
    function serializeObject($object, $format, $properties = null);
    function deserializeObject($data, $class, $format = null);

    function encode($data, $format);
    function decode($data, $format);
}
