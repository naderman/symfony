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
use Symfony\Component\DependencyResolver\DefaultPolicy;
use Symfony\Component\DependencyResolver\Pool;
use Symfony\Component\DependencyResolver\Request;
use Symfony\Component\DependencyResolver\MemoryPackage;
use Symfony\Component\DependencyResolver\PackageRelation;
use Symfony\Component\DependencyResolver\Solver;
use Symfony\Component\DependencyResolver\RelationConstraint\VersionConstraint;

class SolverTest extends \PHPUnit_Framework_TestCase
{
    public function testSolver()
    {
        $this->markTestIncomplete('incomplete');
        return;

        $pool = new Pool;

        $repoInstalled = new ArrayRepository;
        $repoInstalled->addPackage(new MemoryPackage('old', '1.0'));
        $repoInstalled->addPackage(new MemoryPackage('C', '1.0'));

        $repo = new ArrayRepository;
        $repo->addPackage($packageA = new MemoryPackage('A', '2.0'));
        $repo->addPackage($packageB = new MemoryPackage('B', '1.0'));
        $repo->addPackage($newPackageB = new MemoryPackage('B', '1.1'));
        $repo->addPackage($packageC = new MemoryPackage('C', '1.0'));
        $repo->addPackage($oldPackage = new MemoryPackage('old', '1.0'));
        $packageA->setRequires(array(new PackageRelation('A', 'B', new VersionConstraint('<', '1.1'), 'requires')));

        $pool->addRepository($repoInstalled);
        $pool->addRepository($repo);

        $request = new Request($pool);
        $request->install('A');
        $request->update('C');
        $request->remove('old');

        $policy = new DefaultPolicy;
        $solver = new Solver($policy, $pool, $repoInstalled);
        $result = $solver->solve($request);

        $this->assertTrue($result, 'Request could be solved');

        //$transaction = $solver->getTransaction();
        // assert ...
    }
}
