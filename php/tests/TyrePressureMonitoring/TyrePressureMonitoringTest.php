<?php

declare(strict_types=1);

namespace Tests\TyrePressureMonitoring;

use PHPUnit\Framework\TestCase;
use RacingCar\TyrePressureMonitoring\Alarm;
use RacingCar\TyrePressureMonitoring\Sensor;

/*
 * TirePressureMonitoringSystem exercise: write the unit tests for the Alarm class. The Alarm class is designed to
 * monitor tire pressure and set an alarm if the pressure falls outside of the expected range. The Sensor class provided
 * for the exercise fakes the behaviour of a real tire sensor, providing random but realistic values.
 */
class TyrePressureMonitoringTest extends TestCase
{
    public function testAlarmIsOffByDefault(): void
    {
        $alarm = new Alarm();
        $this->assertFalse($alarm->isAlarmOn());
    }

    public function testTyrePressureMonitorCanCheckNextPsiValue(): void
    {
        $sensor = $this->createMock(Sensor::class);

        $sensor->expects($this->once())
            ->method('popNextPressurePsiValue');

        $alarm = new Alarm($sensor);
        $alarm->check();
    }

    public function testLowPsiValueTriggersTheAlarm(): void
    {
        $sensor = $this->createStub(Sensor::class);

        $normalValue = 17;
        $lowValue = 15;

        $sensor->method('popNextPressurePsiValue')
            ->will($this->onConsecutiveCalls($normalValue, $normalValue, $lowValue));

        $alarm = new Alarm($sensor);
        $alarm->check();
        $this->assertFalse($alarm->isAlarmOn());
        $alarm->check();
        $this->assertFalse($alarm->isAlarmOn());
        $alarm->check();
        $this->assertTrue($alarm->isAlarmOn(), 'Low value will trigger the alarm to true');
    }

    public function testHighPsiValueTriggersTheAlarm(): void
    {
        $sensor = $this->createStub(Sensor::class);

        $normalValue = 17;
        $highValue = 22;

        $sensor->method('popNextPressurePsiValue')
            ->will($this->onConsecutiveCalls($normalValue, $normalValue, $highValue));

        $alarm = new Alarm($sensor);
        $alarm->check();
        $this->assertFalse($alarm->isAlarmOn());
        $alarm->check();
        $this->assertFalse($alarm->isAlarmOn());
        $alarm->check();
        $this->assertTrue($alarm->isAlarmOn(), 'High value will trigger the alarm to true');
    }
}
