<?php

declare(strict_types=1);

namespace Sbuerk\Bench\Tests\Benchmark;

use Sbuerk\Bench\GuSim;

class GuSimBench
{
    /**
     * @Revs(10000000)
     * @Iterations(5)
     */
    public function benchConsumeOld()
    {
        (new GuSim())->canOld('12312313123213123131231312');
    }

    /**
     * @Revs(10000000)
     * @Iterations(5)
     */
    public function benchConsumeNew()
    {
        (new GuSim())->canNew('12312313123213123131231312');
    }
}