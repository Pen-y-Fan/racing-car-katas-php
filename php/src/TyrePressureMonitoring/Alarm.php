<?php

declare(strict_types=1);

namespace RacingCar\TyrePressureMonitoring;

class Alarm
{
    private const LOW_PRESSURE_THRESHOLD = 17;

    private const HIGH_PRESSURE_THRESHOLD = 21;

    private bool $alarmOn = false;

    private Sensor $sensor;

    public function __construct(?Sensor $sensor = null)
    {
        $sensor === null ? ($this->sensor = new Sensor()) : $this->sensor = $sensor;
    }

    public function check(): void
    {
        if ($this->isTyrePressureOutOfRange()) {
            $this->turnAlarmOn();
        }
    }

    public function isAlarmOn(): bool
    {
        return $this->alarmOn;
    }

    private function isTyrePressureOutOfRange(): bool
    {
        $psiPressureValue = $this->sensor->popNextPressurePsiValue();
        return $psiPressureValue < self::LOW_PRESSURE_THRESHOLD || $psiPressureValue > self::HIGH_PRESSURE_THRESHOLD;
    }

    private function turnAlarmOn(): void
    {
        $this->alarmOn = true;
    }
}
