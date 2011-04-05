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

/**
 * @author Nils Adermann <naderman@naderman.de>
 */
class Request
{
    protected $jobs;
    protected $pool;

    public function __construct(Pool $pool)
    {
        $this->pool = $pool;
    }

    public function install($packageName)
    {
        $this->addJob($packageName, 'install');
    }

    public function update($packageName)
    {
        $this->addJob($packageName, 'update');
    }

    public function remove($packageName)
    {
        $this->addJob($packageName, 'remove');
    }

    protected function addJob($packageName, $cmd)
    {
        $packages = $this->pool->whatProvides($packageName);

        $this->jobs[] = array(
            'packages' => $packages,
            'cmd' => $cmd,
            'packageName' => $packageName,
        );
    }

    public function getJobs()
    {
        return $this->jobs;
    }
}
