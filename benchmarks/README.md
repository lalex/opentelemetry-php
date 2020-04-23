## TimeBench

### Run

```
git clone https://github.com/lalex/opentelemetry-php.git
cd opentelemetry-php
git checkout time_bench
composer install
./vendor/bin/phpbench run benchmarks/TimeBench.php --report=default
``` 

### Results

#### Time struct

A benchmark to check memory consumption and access time to timestamp values.

When time is an object, it uses 20-30% less memory to construct. 

When time is an array, it takes 25% less time to access a value.

#### Microtime() use

Also it benchmarks three methods of `microtime()` to `int` conversion.

The best way is to cast `float` to `int`.

#### Report

```
+-----------+-----------------------------+-----+------+------+----------+-----------+--------------+----------------+
| benchmark | subject                     | set | revs | iter | mem_peak | time_rev  | comp_z_value | comp_deviation |
+-----------+-----------------------------+-----+------+------+----------+-----------+--------------+----------------+
| TimeBench | benchMicrotimeFloat         | 0   | 1000 | 0    | 506,792b | 0.193μs   | 0.00σ        | 0.00%          |
| TimeBench | benchMicrotimeStringSscanf  | 0   | 1000 | 0    | 507,088b | 1.134μs   | 0.00σ        | 0.00%          |
| TimeBench | benchMicrotimeStringExplode | 0   | 1000 | 0    | 507,480b | 0.751μs   | 0.00σ        | 0.00%          |
| TimeBench | benchConstructArray         | 0   | 1000 | 0    | 983,872b | 312.037μs | 0.00σ        | 0.00%          |
| TimeBench | benchConstructObject        | 0   | 1000 | 0    | 704,256b | 386.080μs | 0.00σ        | 0.00%          |
| TimeBench | benchAccessArray            | 0   | 1000 | 0    | 506,792b | 4.952μs   | 0.00σ        | 0.00%          |
| TimeBench | benchAccessObject           | 0   | 1000 | 0    | 506,792b | 6.727μs   | 0.00σ        | 0.00%          |
+-----------+-----------------------------+-----+------+------+----------+-----------+--------------+----------------+
```