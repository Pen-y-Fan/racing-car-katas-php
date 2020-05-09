<?php

declare(strict_types=1);

namespace Tests\TelemetrySystem;

use PHPUnit\Framework\TestCase;
use RacingCar\TelemetrySystem\TelemetryDiagnostics;

/**
 * TelemetrySystem exercise: write the unit tests for the TelemetryDiagnosticControls class. The responsibility of the
 * TelemetryDiagnosticControls class is to establish a connection to the telemetry server (through the TelemetryClient),
 * send a diagnostic request and successfully receive the response that contains the diagnostic info. The
 * TelemetryClient class provided for the exercise fakes the behavior of the real TelemetryClient class, and can respond
 * with either the diagnostic information or a random sequence. The real TelemetryClient class would connect and
 * communicate with the telemetry server via tcp/ip.

 * Class TelemetrySystemTest
 * @package Tests\TelemetrySystem
 */
class TelemetrySystemTest extends TestCase
{
    public function testCheckTransmissionShouldSendAndReceiveDiagnosticMessage(): void
    {
        $telemetry = new TelemetryDiagnostics();
        $this->assertTrue($telemetry instanceof TelemetryDiagnostics);
        // TODO: Add more assertions
    }
}
