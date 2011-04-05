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

use Symfony\Component\DependencyResolver\Literal;
use Symfony\Component\DependencyResolver\MemoryPackage;

class SolvableTest extends \PHPUnit_Framework_TestCase
{
    public function testSolvable()
    {
        $literal = new Literal(new MemoryPackage('foo', '1'), true);
    }
}
