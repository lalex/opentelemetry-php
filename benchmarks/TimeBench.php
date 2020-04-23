<?php

declare(strict_types=1);

namespace {

    use function TimeBench\getMonotonicTime;
    use function TimeBench\getWallTime;
    use TimeBench\SpanWithTimeArray;
    use TimeBench\SpanWithTimeObject;
    use TimeBench\Time;

    /**
     * @BeforeMethods({"init"})
     */
    class TimeBench
    {
        /**
         * @var array|SpanWithTimeArray[]
         */
        private $spansWithArray = [];

        /**
         * @var array|SpanWithTimeObject[]
         */
        private $spansWithObject = [];

        public function init()
        {
            for ($i = 0; $i < 100; $i++) {
                $this->spansWithArray[] = new SpanWithTimeArray([getWallTime(), getMonotonicTime()]);
                $this->spansWithObject[] = new SpanWithTimeObject(new Time(getWallTime(), getMonotonicTime()));
            }
        }

        /**
         * @Revs(1000)
         */
        public function benchMicrotimeFloat()
        {
            $time = (int) (microtime(true) * 1000000000);
        }

        /**
         * @Revs(1000)
         */
        public function benchMicrotimeStringSscanf()
        {
            \sscanf(\microtime(), '%d %d', $usec, $seconds);
            $time = $seconds * 1000000000 + $usec * 1000;
        }

        /**
         * @Revs(1000)
         */
        public function benchMicrotimeStringExplode()
        {
            [$usec, $seconds] = explode(' ', \microtime());
            $time = $seconds * 1000000000 + $usec * 1000;
        }

        /**
         * @Revs(1000)
         */
        public function benchConstructArray()
        {
            $times = [];
            for ($i = 0; $i < 1000; $i++) {
                $times[] = new SpanWithTimeArray([getWallTime(), getMonotonicTime()]);
            }
        }

        /**
         * @Revs(1000)
         */
        public function benchConstructObject()
        {
            $times = [];
            for ($i = 0; $i < 1000; $i++) {
                $times[] = new SpanWithTimeObject(new Time(getWallTime(), getMonotonicTime()));
            }
        }

        /**
         * @Revs(1000)
         */
        public function benchAccessArray()
        {
            foreach ($this->spansWithArray as $span) {
                $startTime = $span->getStartTime();
            }
        }

        /**
         * @Revs(1000)
         */
        public function benchAccessObject()
        {
            foreach ($this->spansWithObject as $span) {
                $startTime = $span->getStartTime();
            }
        }
    }
}

namespace TimeBench {
    class Time
    {
        private $wall;
        private $monotonic;

        public function __construct(int $wall, int $monotonic)
        {
            $this->wall = $wall;
            $this->monotonic = $monotonic;
        }

        public function getTimestamp(): int
        {
            return $this->wall;
        }
    }

    class SpanWithTimeObject
    {
        /** @var Time */
        private $startTime;

        public function __construct(Time $startTime)
        {
            $this->startTime = $startTime;
        }

        public function getStartTime(): int
        {
            return $this->startTime->getTimestamp();
        }
    }

    class SpanWithTimeArray
    {
        /** @var array|int[] */
        private $startTime = [];

        public function __construct(array $startTime)
        {
            $this->startTime = $startTime;
        }

        public function getStartTime(): int
        {
            return $this->startTime[0];
        }
    }

    /**
     * @return int Wall clock timestamp in nanoseconds
     */
    function getWallTime(): int
    {
        return (int) (microtime(true) * 1000000000);
    }

    /**
     * @return int Monotonic timestamp in nanoseconds
     */
    function getMonotonicTime(): int
    {
        return \hrtime(true);
    }
}
