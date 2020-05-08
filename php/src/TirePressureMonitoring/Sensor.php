<?php

declare(strict_types=1);

namespace RacingCar\TirePressureMonitoring;

class Sensor
{
    private const OFFSET = 16;

    public function popNextPressurePsiValue(): int
    {
        return self::OFFSET + self::getSamplePressure();
    }

    private static function getSamplePressure(): int
    {
        return (int) (6 * mt_rand() / mt_getrandmax() * mt_rand() / mt_getrandmax());
    }
}
