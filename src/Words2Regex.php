<?php

namespace Words2Regex;

class Words2Regex
{
    /** @var bool */
    protected $terminated = false;

    /** @var Words2Regex[] */
    protected $trie = [];

    public function add($word)
    {
        if ('' === $word)
        {
            $this->terminated = true;
            return;
        }

        $char = mb_substr($word, 0, 1);

        if (!isset($this->trie[$char]))
        {
            $this->trie[$char] = new Words2Regex();
        }

        $this->trie[$char]->add(mb_substr($word, 1));
    }

    public function getRegex($delimiter = '/')
    {
        $regex = [];

        foreach ($this->trie as $char => $node)
        {
            $regex[] = preg_quote($char, $delimiter) . $node->getRegex();
        }

        if (empty($regex))
        {
            return '';
        }

        if (!$this->terminated && count($regex) === 1)
        {
            return $regex[0];
        }

        $optional = $this->terminated ? '?' : '';

        if (mb_strlen(implode('', $regex)) === count($regex))
        {
            if (count($regex) === 1)
            {
                return implode('', $regex) . $optional;
            }

            return '[' . implode('', $regex) . ']' . $optional;
        }

        foreach ($regex as $i => $curVal)
        {
            $begin = [mb_substr($curVal, 0, 1)];
            $end = mb_substr($curVal, 1);

            foreach ($regex as $j => $nextVal)
            {
                if ($i == $j)
                {
                    continue;
                }

                if ($end == mb_substr($nextVal, 1))
                {
                    $begin[] = mb_substr($nextVal, 0, 1);
                    unset($regex[$j]);
                }
            }

            if (count($begin) > 1)
            {
                $regex[$i] = '[' . implode('', $begin) . ']' . $end;
            }
        }

        return '(' . implode('|', $regex) . ')' . $optional;
    }
}