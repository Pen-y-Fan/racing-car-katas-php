<?php

declare(strict_types=1);

namespace RacingCar\TelemetrySystem;

use Exception;

class TelemetryClient
{
    public const DIAGNOSTIC_MESSAGE = 'AT#UD';

    private bool $onlineStatus = false;

    private bool $diagnosticMessageJustSent = false;

    public function connect(string $telemetryServerConnectionString): void
    {
        if ($telemetryServerConnectionString === 'test-override') {
            $this->onlineStatus = true;
            return;
        }
        $this->onlineStatus = $this->isFakeWith80pcChangesOfFailure();
    }

    public function disconnect(): void
    {
        $this->onlineStatus = false;
    }

    /**
     * @throws Exception
     */
    public function send(string $message): void
    {

        // The simulation of send() actually just remember
        // if the last message sent was a diagnostic
        // message.
        // This information will be used to simulate the
        // receive(). Indeed there is no real server
        // listening.
        if ($message === self::DIAGNOSTIC_MESSAGE) {
            $this->diagnosticMessageJustSent = true;
        } else {
            $this->diagnosticMessageJustSent = false;
        }
    }

    /**
     * @throws Exception
     */
    public function receive(): string
    {
        if ($this->diagnosticMessageJustSent) {
            # Simulate the reception of the diagnostic message
            $this->diagnosticMessageJustSent = false;
            return $this->generateDiagnosticMessage();
        }
        #  Simulate the reception of a response message returning a random message.
        return $this->generateRandomMessage();
    }

    public function isOnline(): bool
    {
        return $this->onlineStatus;
    }

    /**
     * @throws Exception
     */
    private function generateRandomMessage(): string
    {
        $message = '';
        $messageLength = random_int(0, 50) + 60;
        for ($i = $messageLength; $i >= 0; $i--) {
            $message .= chr(random_int(0, 40) + 86);
        }
        return $message;
    }

    private function generateDiagnosticMessage(): string
    {
        return <<<TAG
LAST TX rate................ 100 MBPS\r\n
HIGHEST TX rate............. 100 MBPS\r\n
LAST RX rate................ 100 MBPS\r\n
HIGHEST RX rate............. 100 MBPS\r\n
BIT RATE.................... 100000000\r\n
WORD LEN.................... 16\r\n
WORD/FRAME.................. 511\r\n
BITS/FRAME.................. 8192\r\n
MODULATION TYPE............. PCM/FM\r\n
TX Digital Los.............. 0.75\r\n
RX Digital Los.............. 0.10\r\n
BEP Test.................... -5\r\n
Local Rtrn Count............ 00\r\n
Remote Rtrn Count........... 00
TAG;
    }

    /**
     * @throws Exception
     */
    private function isFakeWith80pcChangesOfFailure(): bool
    {
        return random_int(1, 10) <= 2;
    }
}
