<?php

declare(strict_types=1);

namespace Tests\TelemetrySystem;

use PHPUnit\Framework\TestCase;
use RacingCar\TelemetrySystem\TelemetryClient;
use RacingCar\TelemetrySystem\TelemetryDiagnostics;
use Throwable;

/**
 * TelemetrySystem exercise: write the unit tests for the TelemetryDiagnosticControls class. The responsibility of the
 * TelemetryDiagnosticControls class is to establish a connection to the telemetry server (through the TelemetryClient),
 * send a diagnostic request and successfully receive the response that contains the diagnostic info. The
 * TelemetryClient class provided for the exercise fakes the behavior of the real TelemetryClient class, and can respond
 * with either the diagnostic information or a random sequence. The real TelemetryClient class would connect and
 * communicate with the telemetry server via tcp/ip.
 *
 * Class TelemetrySystemTest
 * @package Tests\TelemetrySystem
 */
class TelemetrySystemTest extends TestCase
{
    public function testTelemetryInfoCanBeRetrievedUsingTestOverride(): void
    {
        $telemetry = new TelemetryDiagnostics();
        $this->assertSame('', $telemetry->diagnosticInfo);
        $telemetry->telemetryClient->connect('test-override');
        $telemetry->telemetryClient->send('AT#UD');

        $message = $telemetry->telemetryClient->receive();

        $this->assertTrue($telemetry->telemetryClient->isOnline());
        $this->assertNotEmpty($message);
        $this->assertStringStartsWith('LAST TX rate......', $message);

        $telemetry->telemetryClient->disconnect();
        $this->assertFalse($telemetry->telemetryClient->isOnline());
    }

    public function testWhenOnLineTheClientWillSendAndReceiveDiagnosticMessage(): void
    {
        $telemetryClient = $this->createStub(TelemetryClient::class);
        $telemetryClient->method('isOnline')
            ->willReturn(true);

        $telemetry = new TelemetryDiagnostics($telemetryClient);

        try {
            $telemetry->checkTransmission();
        } catch (Throwable $e) {
            $this->fail('The client threw an unexpected Exception');
        }
        $this->assertSame('', $telemetry->diagnosticInfo, "Due to Mock the diagnosticInfo will remaining ''");
    }

    public function testWhenNotOnLineTheClientWillThrowAnException(): void
    {
        $telemetryClient = $this->createStub(TelemetryClient::class);
        $telemetryClient->method('isOnline')
            ->willReturn(false);

        $telemetry = new TelemetryDiagnostics($telemetryClient);
        $this->expectException('Exception');
        $this->expectExceptionMessage('Unable to connect.');
        $telemetry->checkTransmission();
        $this->fail('Client failed to throw an exception');
    }

    public function testClientCanConnectUsingADiagnosticConnectMessage(): void
    {
        $client = new TelemetryClient();
        $this->assertFalse($client->isOnline());

        $client->connect('*111#');

        $this->assertIsBool($client->isOnline(), 'Should be either false (80%) or true (20%)');
    }

    public function testClientCanTestedUsingConnectWithTestOverride(): void
    {
        $client = new TelemetryClient();
        $this->assertFalse($client->isOnline());

        $client->connect('test-override');

        $this->assertTrue($client->isOnline());
    }

    public function testWhenClientDisconnectsOnLineIsFalse(): void
    {
        $client = new TelemetryClient();
        $client->connect('test-override');
        $this->assertTrue($client->isOnline());
        $client->disconnect();
        $this->assertFalse($client->isOnline());
    }

    public function testClientCanSendUsingTheDiagnosticMessage(): void
    {
        $client = new TelemetryClient();
        $client->send('AT#UD');
        $client->connect('test-override');
        $this->assertTrue($client->isOnline());
    }

    public function testClientCanSendWithoutTheDiagnosticMessage(): void
    {
        $client = new TelemetryClient();
        $client->connect('test-override');
        $client->send('None diagnostic message');
        $this->assertTrue($client->isOnline());
    }

    public function testClientCanReceiveTheReport(): void
    {
        $client = new TelemetryClient();
        $client->connect('test-override');

        $message = $client->receive();

        $this->assertTrue($client->isOnline());
        $this->assertNotEmpty($message, "Random message wasn't generated");
    }

    public function testAfterTheDiagnosticMessageIsSentTheClientCanReceiveTheDiagnosticReport(): void
    {
        $client = new TelemetryClient();
        $client->connect('test-override');
        $client->send('AT#UD');

        $message = $client->receive();

        $this->assertTrue($client->isOnline());
        $this->assertNotEmpty($message);
        $this->assertStringStartsWith('LAST TX rate......', $message);
    }
}
