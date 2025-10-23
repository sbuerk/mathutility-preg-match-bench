<?php

declare(strict_types=1);

namespace Sbuerk\Bench;

class MathUtilityCanBeInterpretedAsInt
{
    /**
     * Original implementation before changing it
     */
    public function original(mixed $var): bool
    {
        if ($var === '' || is_object($var) || is_array($var)) {
            return false;
        }
        return (string)(int)$var === (string)$var;
    }

    /**
     * `preg_match` variant introduced with
     * @see https://review.typo3.org/c/Packages/TYPO3.CMS/+/91188/7/typo3/sysext/core/Classes/Utility/MathUtility.php
     */
    public function pregMatchChange91188(mixed $var): bool
    {
        if (is_int($var)) {
            return true;
        }
        if ($var === '' || is_object($var) || is_array($var)) {
            return false;
        }
        return \preg_match('/^(?:-?[1-9][0-9]*|0)$/', (string)$var) === 1;
    }

    /**
     * `preg_match` variant introduced with
     * @see https://review.typo3.org/c/Packages/TYPO3.CMS/+/91188/7/typo3/sysext/core/Classes/Utility/MathUtility.php
     * plus adding a float&nan check to early return false to avoid
     *
     *   unexpected NAN value was coerced to string
     *
     * php warning
     */
    public function pregMatchChange91188AndNanProtection(mixed $var): bool
    {
        if (is_int($var)) {
            return true;
        }
        if ($var === '' || is_object($var) || is_array($var) || (is_float($var) && is_nan($var))) {
            return false;
        }
        return \preg_match('/^(?:-?[1-9][0-9]*|0)$/', (string)$var) === 1;
    }

    /**
     * Reworked and streamline implementation:
     *
     * - Avoiding `preg_match()`
     * - Reducing type checks by using `gettype()` once within the method
     * - Use simply returns where possible and otherwise optimized checks.
     *
     * Following goals has been kept in mind:
     *
     * * Try to stay performant and nearly on the original implementation
     *   OR better.
     * * Avoid new PHP8.5.0 warnings like:
     *   * The float <X> is not representable as an int, cast occurred
     *   * The float NAN is not representable as an int, cast occurred
     * * Satisfy all unit and functional tests along with recently added
     *   cased for the first `preg_mach()` variant.
     */
    public function variantTacklingAllWarningsAndTryingToKeepPerformance(mixed $var): bool
    {
        $type = gettype($var);
        return match($type) {
            'integer' => true,
            'array', 'enum', 'object' => false,
            'string' => (string)@(int)$var === $var,
            // float -> double
            'double' => !is_nan($var) && (string)@(int)$var === (string)$var,
            default => (string)@(int)$var === @(string)$var,
        };
    }
}