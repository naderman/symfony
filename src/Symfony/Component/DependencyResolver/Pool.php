<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyResolver;

use Symfony\Component\DependencyResolver\RelationConstraint\RelationConstraintInterface;

/**
 * A package pool contains repositories that provide packages.
 *
 * @author Nils Adermann <naderman@naderman.de>
 */
class Pool
{
    protected $repositories = array();
    protected $packages = array();
    protected $packageByName = array();

    /**
     * Adds a repository and its packages to this package pool
     *
     * @param RepositoryInterface $repo A package repository
     */
    public function addRepository(RepositoryInterface $repo)
    {
        $this->repositories[] = $repo;

        foreach ($repo->getPackages() as $package) {
            $this->packages[$package->getId()] = $package;

            foreach ($package->getNames() as $name) {
                if (!isset($this->packageByName[$name])) {
                    $this->packageByNameByName[$name] = array();
                }

                $this->packageByName[$name][] = $package;
            }
        }
    }

    /**
     * Searches all packages providing the given package name and match the constraint
     *
     * @param string                     $name        The package name to be searched for
     * @param RelationConstraintInterface $constraint A constraint that all returned
     *                                                packages must match or null to return all
     * @return array                                  A set of packages
     */
    public function whatProvides($name, RelationConstraintInterface $constraint = null)
    {
        if (!isset($this->packageByName[$name])) {
            return array();
        }

        $candidates = $this->packageByName[$name];

        if (null === $constraint) {
            return $candidates;
        }

        $result = array();

        foreach ($candidates as $candidate) {
            if ($candidate->matches($name, $constraint)) {
                $result[] = $candidate;
            }
        }

        return $result;
    }
}
