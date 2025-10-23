<?php

declare(strict_types=1);

namespace Sbuerk\Bench\Tests\Benchmark;

use Sbuerk\Bench\MathUtilityCanBeInterpretedAsInt;

class MathUtilityCanBeInterpretedAsIntBench
{
    private MathUtilityCanBeInterpretedAsInt $subject;
    private array $values = [];

    public function __construct()
    {
        $this->subject = new MathUtilityCanBeInterpretedAsInt();
        for($i=0;$i<10_000_000;$i++) {
            $this->values[] = ['value' => $i];
        }
    }

    /**
     * @Revs(1)
     * @Iterations(5)
     * @ParamProviders({
     *     "provideSuffix"
     * })
     */
    public function benchOriginal(array $params): void
    {
        $value = $params['value'];
        foreach ($this->values as $value) {
            if (($params['suffix'] ?? null) !== null) {
                $value .= $params['suffix'];
            }
            $this->subject->original($value);
        }
    }

    /**
     * @Revs(1)
     * @Iterations(5)
     * @ParamProviders({
     *     "provideSuffix"
     * })
     */
    public function benchPregMatchGerritChange91188(array $params): void
    {
        $value = $params['value'];
        foreach ($this->values as $value) {
            if (($params['suffix'] ?? null) !== null) {
                $value .= $params['suffix'];
            }
            $this->subject->pregMatchChange91188($value);
        }
    }

    /**
     * @Revs(1)
     * @Iterations(5)
     * @ParamProviders({
     *     "provideSuffix"
     * })
     */
    public function benchPregMatchChange91188AndNanProtection(array $params): void
    {
        $value = $params['value'];
        foreach ($this->values as $value) {
            if (($params['suffix'] ?? null) !== null) {
                $value .= $params['suffix'];
            }
            $this->subject->pregMatchChange91188AndNanProtection($value);
        }
    }

    /**
     * @Revs(1)
     * @Iterations(5)
     * @ParamProviders({
     *     "provideSuffix"
     * })
     */
    public function benchVariantTacklingAllWarningsAndTryingToKeepPerformance(array $params): void
    {
        $value = $params['value'];
        foreach ($this->values as $value) {
            if (($params['suffix'] ?? null) !== null) {
                $value .= $params['suffix'];
            }
            $this->subject->variantTacklingAllWarningsAndTryingToKeepPerformance($value);
        }
    }

    public function provideSuffix(): array
    {
        return [
            ['suffix' => null,],
            ['suffix' => 'a',],
        ];
    }
}