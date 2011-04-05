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

use Symfony\Component\DependencyResolver\Pool;
use Symfony\Component\DependencyResolver\ArrayRepository;
use Symfony\Component\DependencyResolver\MemoryPackage;

class PoolTest extends \PHPUnit_Framework_TestCase
{
    public function testPool()
    {
        $pool = new Pool;
        $repo = new ArrayRepository;
        $package = new MemoryPackage('foo', '1');

        $repo->addPackage($package);
        $pool->addRepository($repo);

        $this->assertEquals(array($package), $pool->whatProvides('foo'));
        $this->assertEquals(array($package), $pool->whatProvides('foo'));
    }
}
