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
class Rule
{
    protected $disabled;
    protected $literals;

    protected $next1;
    protected $next2;

    public function __construct(array $literals, $reason, $reasonData)
    {
        // sort all packages ascending by id
        usort($literals, array($this, 'compareLiteralsById'));

        $this->literals = $literals;
        $this->reason = $reason;
        $this->reasonData = $reasonData;

        $this->disabled = false;

        $this->watch1 = (count($this->literals) > 0) ? $literals[0] : null;
        $this->watch2 = (count($this->literals) > 1) ? $literals[1] : null;
    }

    /**
     * Checks if this rule is equal to another one
     *
     * Ignores whether either of the rules is disabled.
     *
     * @param  Rule $rule The rule to check against
     * @return bool       Whether the rules are equal
     */
    public function equals(Rule $rule)
    {
        if (count($this->literals) != count($rule->literals)) {
            return false;
        }

        for ($i = 0, $n = count($this->literals); $i < $n; $i++) {
            if (! $this->literals[$i]->equals($rule->literals[$i])) {
                return false;
            }
        }

        return true;
    }

    public function disable()
    {
        $this->disabled = true;
    }

    public function enable()
    {
        $this->disabled = false;
    }

    public function isDisabled()
    {
        return $this->disabled;
    }

    public function isEnabled()
    {
        return ! $this->disabled;
    }

    public function getLiterals()
    {
        return $this->literals;
    }

    public function getFirstWatchedSolvable()
    {
        return $this->watch1;
    }

    public function getNext(Solvable $solvable)
    {
        if ($this->getFirstWatchedSolvable()->equals($solvable)) {
            return $this->next1;
        } else {
            return $this->next2;
        }
    }

    public function getOtherWatch(Solvable $solvable)
    {
        if ($this->watch1->equals($solvable)) {
            return $this->watch2;
        } else {
            return $this->watch1;
        }
    }

    /**
     * Formats a rule as a string of the format (Literal1|Literal2|...)
     *
     * @return string
     */
    public function __toString()
    {
        $result = '(';

        foreach ($this->literals as $i => $literal) {
            if ($i != 0) {
                $result .= '|';
            }
            $result .= $literal;
        }

        $result .= ')';

        return $result;
    }

    /**
     * Comparison function for sorting literals by their id
     *
     * @param  Literal $a
     * @param  Literal $b
     * @return int        0 if the literals are equal, 1 if b is larger than a, -1 else
     */
    private function compareLiteralsById(Literal $a, Literal $b)
    {
        if ($a->getId() === $b->getId()) {
            return 0;
        }
        return $a->getId() < $b->getId() ? -1 : 1;
    }
}
