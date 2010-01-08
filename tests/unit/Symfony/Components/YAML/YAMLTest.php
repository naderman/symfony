<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * (c) Nils Adermann <naderman@naderman.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../../bootstrap.php';

use Symfony\Components\YAML\YAML;

$t = new LimeTest(1);

$path = __DIR__.'/../../../../fixtures/Symfony/Components/YAML';
$file = $path.'/sequence.yml.php';

$t->diag($file);

$expected = array('apple', 'banana', 'carrot');

$t->is(YAML::load($file), $expected, 'YAML::load() can load yaml hidden in php files');
