<?php

declare(strict_types=1);

namespace RacingCar\TelemetrySystem;

use Exception;

class TelemetryDiagnostics
{
    public const DIAGNOSTIC_CHANNEL_CONNECTION_STRING = '*111#';

    public string $diagnosticInfo = '';

    private TelemetryClient $telemetryClient;

    public function __construct()
    {
        $this->telemetryClient = new TelemetryClient();
    }

    /**
     * @throws Exception
     */
    public function checkTransmission(): void
    {
        $this->diagnosticInfo = '';
        $this->telemetryClient->disconnect();

        $retryLeft = 3;
        while ($this->telemetryClient->isOnline() === false and $retryLeft > 0) {
            $this->telemetryClient->connect(self::DIAGNOSTIC_CHANNEL_CONNECTION_STRING);
            --$retryLeft;
        }

        if ($this->telemetryClient->isOnline() === false) {
            throw new Exception('Unable to connect.');
        }

        $this->telemetryClient->send(TelemetryClient::DIAGNOSTIC_MESSAGE);
        $this->diagnosticInfo = $this->telemetryClient->receive();
    }
}
