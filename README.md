# TYPO3 `MathUtility::canBeInterpretedAsInteger()` benchmarking

## Briefing

TYPO3 `MathUtility::canBeInterpretedAsInteger()` required changes to avoid
PHP 8.5.0 warnings and some developer was not happy with the chosen `preg_match()`
variant raising performance concerns and the reason why this repository has been
started using `phpbenchmark` to make measures for the changed method.

Additionally work needed to be done to resolve additional warnings and
the benchmarking has been extended to benchmark different working code
implementation to have a founded base to make decisions.

Further, a plain php-script provided by a community developer has also
been added as `plain-benchmark.php` as additional source and to compare
the benchmark implementation & results.

For both benchmarking implementation it has been ensured to generate a range
of values **upfront** and having them outside the time measurement frame and
also removed any influencing things like `slow` range building or callback
function calls (closures) to have both variants as close as possible free of
external influences and comparable.

## php-benchmark

### Setup

```bash
composer install
```

### Execute

```bash
vendor/bin/phpbench run tests/Benchmark/MathUtilityCanBeInterpretedAsIntBench.php \
  --report=aggregate --php-config='{"opcache.enable_cli": 1}'
```

## plain-benchmark.php

### Setup

* not required

### Execute

```bash
php plain-benchmark.php
```

## Some stats from benchmarking (make your own executings !!!)

**PHP8.2**

```
$ php8.4 -d opcache.enable_cli=1 plain-benchmark.php
original [SUFFIX: ]: 1.2154870033264 [START: 2025-10-23 14:34:21 END: 2025-10-23 14:34:22]
original [SUFFIX: a]: 1.8060410022736 [START: 2025-10-23 14:34:22 END: 2025-10-23 14:34:24]
pregMatchChange91188 [SUFFIX: ]: 0.54867100715637 [START: 2025-10-23 14:34:24 END: 2025-10-23 14:34:25]
pregMatchChange91188 [SUFFIX: a]: 2.3849029541016 [START: 2025-10-23 14:34:25 END: 2025-10-23 14:34:27]
pregMatchChange91188AndNanProtection [SUFFIX: ]: 0.59091091156006 [START: 2025-10-23 14:34:27 END: 2025-10-23 14:34:28]
pregMatchChange91188AndNanProtection [SUFFIX: a]: 2.4980080127716 [START: 2025-10-23 14:34:28 END: 2025-10-23 14:34:30]
variantTacklingAllWarningsAndTryingToKeepPerformance [SUFFIX: ]: 0.93352890014648 [START: 2025-10-23 14:34:30 END: 2025-10-23 14:34:31]
variantTacklingAllWarningsAndTryingToKeepPerformance [SUFFIX: a]: 2.106498003006 [START: 2025-10-23 14:34:31 END: 2025-10-23 14:34:33]

$ php8.4 vendor/bin/phpbench run tests/Benchmark/MathUtilityCanBeInterpretedAsIntBench.php \
 --report=aggregate --php-config='{"opcache.enable_cli": 1}'
 PHPBench (1.4.1) running benchmarks... #standwithukraine
with configuration file: /var/www/work/bench/phpbench.json
with PHP version 8.4.13, xdebug ❌, opcache ✔

\Sbuerk\Bench\Tests\Benchmark\MathUtilityCanBeInterpretedAsIntBench

    benchOriginal # 0.......................I4 - Mo759.290ms (±1.97%)
    benchOriginal # 1.......................I4 - Mo12.662s (±1.09%)
    benchPregMatchGerritChange91188 # 0.....I4 - Mo828.307ms (±0.78%)
    benchPregMatchGerritChange91188 # 1.....I4 - Mo13.333s (±1.07%)
    benchPregMatchChange91188AndNanProtecti.I4 - Mo820.434ms (±0.51%)
    benchPregMatchChange91188AndNanProtecti.I4 - Mo13.483s (±0.51%)
    benchVariantTacklingAllWarningsAndTryin.I4 - Mo742.543ms (±0.57%)
    benchVariantTacklingAllWarningsAndTryin.I4 - Mo12.580s (±0.67%)

Subjects: 4, Assertions: 0, Failures: 0, Errors: 0
+---------------------------------------+-----------------------------------------------------------+-----+------+-----+----------+-----------+--------+
| benchmark                             | subject                                                   | set | revs | its | mem_peak | mode      | rstdev |
+---------------------------------------+-----------------------------------------------------------+-----+------+-----+----------+-----------+--------+
| MathUtilityCanBeInterpretedAsIntBench | benchOriginal                                             | 0   | 1    | 5   | 4.029gb  | 759.290ms | ±1.97% |
| MathUtilityCanBeInterpretedAsIntBench | benchOriginal                                             | 1   | 1    | 5   | 4.029gb  | 12.662s   | ±1.09% |
| MathUtilityCanBeInterpretedAsIntBench | benchPregMatchGerritChange91188                           | 0   | 1    | 5   | 4.029gb  | 828.307ms | ±0.78% |
| MathUtilityCanBeInterpretedAsIntBench | benchPregMatchGerritChange91188                           | 1   | 1    | 5   | 4.029gb  | 13.333s   | ±1.07% |
| MathUtilityCanBeInterpretedAsIntBench | benchPregMatchChange91188AndNanProtection                 | 0   | 1    | 5   | 4.029gb  | 820.434ms | ±0.51% |
| MathUtilityCanBeInterpretedAsIntBench | benchPregMatchChange91188AndNanProtection                 | 1   | 1    | 5   | 4.029gb  | 13.483s   | ±0.51% |
| MathUtilityCanBeInterpretedAsIntBench | benchVariantTacklingAllWarningsAndTryingToKeepPerformance | 0   | 1    | 5   | 4.029gb  | 742.543ms | ±0.57% |
| MathUtilityCanBeInterpretedAsIntBench | benchVariantTacklingAllWarningsAndTryingToKeepPerformance | 1   | 1    | 5   | 4.029gb  | 12.580s   | ±0.67% |
+---------------------------------------+-----------------------------------------------------------+-----+------+-----+----------+-----------+--------+

$ php8.4 vendor/bin/phpbench run tests/Benchmark/MathUtilityCanBeInterpretedAsIntBench.php \
 --report=default --php-config='{"opcache.enable_cli": 1}'
PHPBench (1.4.1) running benchmarks... #standwithukraine
with configuration file: /var/www/work/bench/phpbench.json
with PHP version 8.4.13, xdebug ❌, opcache ✔

\Sbuerk\Bench\Tests\Benchmark\MathUtilityCanBeInterpretedAsIntBench

    benchOriginal # 0.......................I4 - Mo755.298ms (±0.64%)
    benchOriginal # 1.......................I4 - Mo12.613s (±0.66%)
    benchPregMatchGerritChange91188 # 0.....I4 - Mo831.882ms (±0.85%)
    benchPregMatchGerritChange91188 # 1.....I4 - Mo13.411s (±0.93%)
    benchPregMatchChange91188AndNanProtecti.I4 - Mo837.832ms (±0.51%)
    benchPregMatchChange91188AndNanProtecti.I4 - Mo13.526s (±1.49%)
    benchVariantTacklingAllWarningsAndTryin.I4 - Mo742.140ms (±0.42%)
    benchVariantTacklingAllWarningsAndTryin.I4 - Mo12.610s (±1.13%)

+------+---------------------------------------+-----------------------------------------------------------+-----+------+----------------+------------------+--------------+----------------+
| iter | benchmark                             | subject                                                   | set | revs | mem_peak       | time_avg         | comp_z_value | comp_deviation |
+------+---------------------------------------+-----------------------------------------------------------+-----+------+----------------+------------------+--------------+----------------+
| 0    | MathUtilityCanBeInterpretedAsIntBench | benchOriginal                                             | 0   | 1    | 4,028,837,168b | 765,821.000μs    | +1.77σ       | +1.12%         |
| 1    | MathUtilityCanBeInterpretedAsIntBench | benchOriginal                                             | 0   | 1    | 4,028,837,168b | 751,926.000μs    | -1.12σ       | -0.71%         |
| 2    | MathUtilityCanBeInterpretedAsIntBench | benchOriginal                                             | 0   | 1    | 4,028,837,168b | 756,568.000μs    | -0.15σ       | -0.10%         |
| 3    | MathUtilityCanBeInterpretedAsIntBench | benchOriginal                                             | 0   | 1    | 4,028,837,168b | 753,778.000μs    | -0.73σ       | -0.47%         |
| 4    | MathUtilityCanBeInterpretedAsIntBench | benchOriginal                                             | 0   | 1    | 4,028,837,168b | 758,435.000μs    | +0.23σ       | +0.15%         |
| 0    | MathUtilityCanBeInterpretedAsIntBench | benchOriginal                                             | 1   | 1    | 4,028,837,232b | 12,499,413.000μs | -1.44σ       | -0.96%         |
| 1    | MathUtilityCanBeInterpretedAsIntBench | benchOriginal                                             | 1   | 1    | 4,028,837,232b | 12,661,195.000μs | +0.49σ       | +0.32%         |
| 2    | MathUtilityCanBeInterpretedAsIntBench | benchOriginal                                             | 1   | 1    | 4,028,837,232b | 12,573,227.000μs | -0.56σ       | -0.37%         |
| 3    | MathUtilityCanBeInterpretedAsIntBench | benchOriginal                                             | 1   | 1    | 4,028,837,232b | 12,618,865.000μs | -0.02σ       | -0.01%         |
| 4    | MathUtilityCanBeInterpretedAsIntBench | benchOriginal                                             | 1   | 1    | 4,028,837,232b | 12,749,543.000μs | +1.54σ       | +1.02%         |
| 0    | MathUtilityCanBeInterpretedAsIntBench | benchPregMatchGerritChange91188                           | 0   | 1    | 4,028,837,264b | 833,093.000μs    | -0.49σ       | -0.42%         |
| 1    | MathUtilityCanBeInterpretedAsIntBench | benchPregMatchGerritChange91188                           | 0   | 1    | 4,028,837,264b | 847,347.000μs    | +1.51σ       | +1.29%         |
| 2    | MathUtilityCanBeInterpretedAsIntBench | benchPregMatchGerritChange91188                           | 0   | 1    | 4,028,837,264b | 842,528.000μs    | +0.84σ       | +0.71%         |
| 3    | MathUtilityCanBeInterpretedAsIntBench | benchPregMatchGerritChange91188                           | 0   | 1    | 4,028,837,264b | 830,805.000μs    | -0.81σ       | -0.69%         |
| 4    | MathUtilityCanBeInterpretedAsIntBench | benchPregMatchGerritChange91188                           | 0   | 1    | 4,028,837,264b | 829,054.000μs    | -1.06σ       | -0.90%         |
| 0    | MathUtilityCanBeInterpretedAsIntBench | benchPregMatchGerritChange91188                           | 1   | 1    | 4,028,837,328b | 13,338,821.000μs | -1.20σ       | -1.12%         |
| 1    | MathUtilityCanBeInterpretedAsIntBench | benchPregMatchGerritChange91188                           | 1   | 1    | 4,028,837,328b | 13,402,976.000μs | -0.69σ       | -0.65%         |
| 2    | MathUtilityCanBeInterpretedAsIntBench | benchPregMatchGerritChange91188                           | 1   | 1    | 4,028,837,328b | 13,669,014.000μs | +1.42σ       | +1.33%         |
| 3    | MathUtilityCanBeInterpretedAsIntBench | benchPregMatchGerritChange91188                           | 1   | 1    | 4,028,837,328b | 13,606,442.000μs | +0.92σ       | +0.86%         |
| 4    | MathUtilityCanBeInterpretedAsIntBench | benchPregMatchGerritChange91188                           | 1   | 1    | 4,028,837,328b | 13,434,014.000μs | -0.45σ       | -0.42%         |
| 0    | MathUtilityCanBeInterpretedAsIntBench | benchPregMatchChange91188AndNanProtection                 | 0   | 1    | 4,028,837,408b | 837,487.000μs    | -0.65σ       | -0.33%         |
| 1    | MathUtilityCanBeInterpretedAsIntBench | benchPregMatchChange91188AndNanProtection                 | 0   | 1    | 4,028,837,408b | 847,826.000μs    | +1.75σ       | +0.90%         |
| 2    | MathUtilityCanBeInterpretedAsIntBench | benchPregMatchChange91188AndNanProtection                 | 0   | 1    | 4,028,837,408b | 836,219.000μs    | -0.94σ       | -0.48%         |
| 3    | MathUtilityCanBeInterpretedAsIntBench | benchPregMatchChange91188AndNanProtection                 | 0   | 1    | 4,028,837,408b | 837,494.000μs    | -0.64σ       | -0.33%         |
| 4    | MathUtilityCanBeInterpretedAsIntBench | benchPregMatchChange91188AndNanProtection                 | 0   | 1    | 4,028,837,408b | 842,373.000μs    | +0.48σ       | +0.25%         |
| 0    | MathUtilityCanBeInterpretedAsIntBench | benchPregMatchChange91188AndNanProtection                 | 1   | 1    | 4,028,837,472b | 13,520,010.000μs | -0.40σ       | -0.60%         |
| 1    | MathUtilityCanBeInterpretedAsIntBench | benchPregMatchChange91188AndNanProtection                 | 1   | 1    | 4,028,837,472b | 13,312,247.000μs | -1.42σ       | -2.13%         |
| 2    | MathUtilityCanBeInterpretedAsIntBench | benchPregMatchChange91188AndNanProtection                 | 1   | 1    | 4,028,837,472b | 13,869,635.000μs | +1.32σ       | +1.97%         |
| 3    | MathUtilityCanBeInterpretedAsIntBench | benchPregMatchChange91188AndNanProtection                 | 1   | 1    | 4,028,837,472b | 13,793,040.000μs | +0.94σ       | +1.41%         |
| 4    | MathUtilityCanBeInterpretedAsIntBench | benchPregMatchChange91188AndNanProtection                 | 1   | 1    | 4,028,837,472b | 13,513,010.000μs | -0.44σ       | -0.65%         |
| 0    | MathUtilityCanBeInterpretedAsIntBench | benchVariantTacklingAllWarningsAndTryingToKeepPerformance | 0   | 1    | 4,028,837,504b | 741,298.000μs    | -0.45σ       | -0.19%         |
| 1    | MathUtilityCanBeInterpretedAsIntBench | benchVariantTacklingAllWarningsAndTryingToKeepPerformance | 0   | 1    | 4,028,837,504b | 742,389.000μs    | -0.10σ       | -0.04%         |
| 2    | MathUtilityCanBeInterpretedAsIntBench | benchVariantTacklingAllWarningsAndTryingToKeepPerformance | 0   | 1    | 4,028,837,504b | 743,722.000μs    | +0.33σ       | +0.14%         |
| 3    | MathUtilityCanBeInterpretedAsIntBench | benchVariantTacklingAllWarningsAndTryingToKeepPerformance | 0   | 1    | 4,028,837,504b | 747,783.000μs    | +1.64σ       | +0.68%         |
| 4    | MathUtilityCanBeInterpretedAsIntBench | benchVariantTacklingAllWarningsAndTryingToKeepPerformance | 0   | 1    | 4,028,837,504b | 738,297.000μs    | -1.42σ       | -0.59%         |
| 0    | MathUtilityCanBeInterpretedAsIntBench | benchVariantTacklingAllWarningsAndTryingToKeepPerformance | 1   | 1    | 4,028,837,568b | 12,615,215.000μs | -0.42σ       | -0.47%         |
| 1    | MathUtilityCanBeInterpretedAsIntBench | benchVariantTacklingAllWarningsAndTryingToKeepPerformance | 1   | 1    | 4,028,837,568b | 12,617,354.000μs | -0.40σ       | -0.45%         |
| 2    | MathUtilityCanBeInterpretedAsIntBench | benchVariantTacklingAllWarningsAndTryingToKeepPerformance | 1   | 1    | 4,028,837,568b | 12,536,839.000μs | -0.96σ       | -1.09%         |
| 3    | MathUtilityCanBeInterpretedAsIntBench | benchVariantTacklingAllWarningsAndTryingToKeepPerformance | 1   | 1    | 4,028,837,568b | 12,654,077.000μs | -0.15σ       | -0.16%         |
| 4    | MathUtilityCanBeInterpretedAsIntBench | benchVariantTacklingAllWarningsAndTryingToKeepPerformance | 1   | 1    | 4,028,837,568b | 12,950,922.000μs | +1.93σ       | +2.18%         |
+------+---------------------------------------+-----------------------------------------------------------+-----+------+----------------+------------------+--------------+----------------+

```