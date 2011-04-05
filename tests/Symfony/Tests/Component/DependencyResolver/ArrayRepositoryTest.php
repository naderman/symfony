<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Tests\Component\DependencyResolver;

use Symfony\Component\DependencyResolver\ArrayRepository;
use Symfony\Component\DependencyResolver\MemoryPackage;

class ArrayRepositoryTest extends \PHPUnit_Framework_TestCase
{
    public function testAddLiteral()
    {
        $repo = new ArrayRepository;
        $repo->addPackage(new MemoryPackage('foo', '1'));

        $this->assertEquals(1, count($repo));
    }
}
