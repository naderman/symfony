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
interface RepositoryInterface extends \Countable
{
    function getPackages();
    function contains(Package $package);
}
