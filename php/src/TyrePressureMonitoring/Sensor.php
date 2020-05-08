<?php

declare(strict_types=1);

namespace RacingCar\TyrePressureMonitoring;

class Sensor
{
    private const OFFSET = 16;

    public function popNextPressurePsiValue(): int
    {
        return self::OFFSET + $this->getSamplePressure();
    }

    private function getSamplePressure(): int
    {
        return (int) (6 * mt_rand() / mt_getrandmax() * mt_rand() / mt_getrandmax());
    }
}
