<?php

declare(strict_types=1);

namespace Sbuerk\Bench;

class GuSim
{
    public function canOld(mixed $var): bool
    {
        if ($var === '' || is_object($var) || is_array($var)) {
            return false;
        }
        return (string)(int)$var === (string)$var;
    }

    public function canNew(mixed $var): bool
    {
        if (is_int($var)) {
            return true;
        }
        if ($var === '' || is_object($var) || is_array($var)) {
            return false;
        }
        return \preg_match('/^(?:-?[1-9][0-9]*|0)$/', (string)$var) === 1;
    }
}