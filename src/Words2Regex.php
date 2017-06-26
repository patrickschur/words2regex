<?php

namespace Words2Regex;

class Words2Regex
{
    protected $endOfString = false;

    /** @var Words2Regex[] */
    protected $ast = [];

    public function add($word)
    {
        if (empty($word))
        {
            $this->endOfString = true;
            return;
        }

        $char = mb_substr($word, 0, 1);

        if (!isset($this->ast[$char]))
        {
            $this->ast[$char] = new Words2Regex();
        }

        $this->ast[$char]->add(mb_substr($word, 1));
    }

    public function getRegex()
    {
        $regex = [];

        foreach ($this->ast as $char => $ast)
        {
            $regex[] = $char . $ast->getRegex();
        }

        if (empty($regex))
        {
            return '';
        }

        if (!$this->endOfString && count($regex) == 1)
        {
            return $regex[0];
        }

        $optional = $this->endOfString ? '?' : '';

        if (mb_strlen(implode('', $regex)) == count($regex))
        {
            if (count($regex) == 1)
            {
                return implode('', $regex) . $optional;
            }

            return '[' . implode('', $regex) . ']' . $optional;
        }

        foreach ($regex as $i => $curVal)
        {
            $begin = mb_substr($curVal, 0, 1);
            $end = mb_substr($curVal, 1);

            foreach ($regex as $j => $nextVal)
            {
                if ($i == $j)
                {
                    continue;
                }

                if ($end == mb_substr($nextVal, 1))
                {
                    $begin .= mb_substr($nextVal, 0, 1);
                    unset($regex[$j]);
                }
            }

            if (mb_strlen($begin) > 1)
            {
                $regex[$i] = '[' . $begin . ']' . $end;
            }
        }

        return '(' . implode('|', $regex) . ')' . $optional;
    }
}