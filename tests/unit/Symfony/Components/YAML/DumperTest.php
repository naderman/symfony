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
use Symfony\Components\YAML\Parser;
use Symfony\Components\YAML\Dumper;

YAML::setSpecVersion('1.1');

$t = new LimeTest(150);

$parser = new Parser();
$dumper = new Dumper();

$path = __DIR__.'/../../../../fixtures/Symfony/Components/YAML';
$files = $parser->parse(file_get_contents($path.'/index.yml'));
foreach ($files as $file)
{
  $t->diag($file);

  $yamls = file_get_contents($path.'/'.$file.'.yml');

  // split YAMLs documents
  foreach (preg_split('/^---( %YAML\:1\.0)?/m', $yamls) as $yaml)
  {
    if (!$yaml)
    {
      continue;
    }

    $test = $parser->parse($yaml);
    if (isset($test['dump_skip']) && $test['dump_skip'])
    {
      continue;
    }
    else if (isset($test['todo']) && $test['todo'])
    {
      $t->todo($test['test']);
    }
    else
    {
      $expected = eval('return '.trim($test['php']).';');

      $t->is($parser->parse($dumper->dump($expected, 10)), $expected, $test['test']);
    }
  }
}

// inline level
$array = array(
  '' => 'bar',
  'foo' => '#bar',
  'foo\'bar' => array(),
  'bar' => array(1, 'foo'),
  'foobar' => array(
    'foo' => 'bar',
    'bar' => array(1, 'foo'),
    'foobar' => array(
      'foo' => 'bar',
      'bar' => array(1, 'foo'),
    ),
  ),
);

$expected = <<<EOF
{ '': bar, foo: '#bar', 'foo''bar': {  }, bar: [1, foo], foobar: { foo: bar, bar: [1, foo], foobar: { foo: bar, bar: [1, foo] } } }
EOF;
$t->is($dumper->dump($array, -10), $expected, '->dump() takes an inline level argument');
$t->is($dumper->dump($array, 0), $expected, '->dump() takes an inline level argument');

$expected = <<<EOF
'': bar
foo: '#bar'
'foo''bar': {  }
bar: [1, foo]
foobar: { foo: bar, bar: [1, foo], foobar: { foo: bar, bar: [1, foo] } }

EOF;
$t->is($dumper->dump($array, 1), $expected, '->dump() takes an inline level argument');

$expected = <<<EOF
'': bar
foo: '#bar'
'foo''bar': {  }
bar:
  - 1
  - foo
foobar:
  foo: bar
  bar: [1, foo]
  foobar: { foo: bar, bar: [1, foo] }

EOF;
$t->is($dumper->dump($array, 2), $expected, '->dump() takes an inline level argument');

$expected = <<<EOF
'': bar
foo: '#bar'
'foo''bar': {  }
bar:
  - 1
  - foo
foobar:
  foo: bar
  bar:
    - 1
    - foo
  foobar:
    foo: bar
    bar: [1, foo]

EOF;
$t->is($dumper->dump($array, 3), $expected, '->dump() takes an inline level argument');

$expected = <<<EOF
'': bar
foo: '#bar'
'foo''bar': {  }
bar:
  - 1
  - foo
foobar:
  foo: bar
  bar:
    - 1
    - foo
  foobar:
    foo: bar
    bar:
      - 1
      - foo

EOF;
$t->is($dumper->dump($array, 4), $expected, '->dump() takes an inline level argument');
$t->is($dumper->dump($array, 10), $expected, '->dump() takes an inline level argument');

// objects
$t->diag('Objects support');
class A
{
  public $a = 'foo';
}
$a = array('foo' => new A(), 'bar' => 1);
$t->is($dumper->dump($a), '{ foo: !!php/object:O:1:"A":1:{s:1:"a";s:3:"foo";}, bar: 1 }', '->dump() is able to dump objects');

$expected = <<<EOF

- foo

EOF;
file_put_contents('temp.yml.php', $dumper->dumpPHP(array('foo'), 1));

ob_start();
$actual = include('temp.yml.php');
$output = ob_get_clean();

unlink('temp.yml.php');

$t->is($actual, $expected, '->dumpPHP() generates YAML with valid prefixed PHP code');
$t->is($output, '', '->dumpPHP() generates a PHP file without output');
