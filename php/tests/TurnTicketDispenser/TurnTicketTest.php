<?php

declare(strict_types=1);

namespace Tests\TurnTicketDispenser;

use PHPUnit\Framework\TestCase;
use RacingCar\TurnTicketDispenser\TicketDispenser;
use RacingCar\TurnTicketDispenser\TurnTicket;

/**
 * TicketDispenser exercise: write the unit tests for the TicketDispenser. The TicketDispenser class is designed to be
 * used to manage a queuing system in a shop. There may be more than one ticket dispenser but the same ticket should
 * not be issued to two different customers.
 *
 * Class TurnTicketTest
 * @package Tests\TurnTicketDispenser
 */
class TurnTicketTest extends TestCase
{
    public function testATicketIsAnInstanceOfTurnTicketClass(): void
    {
        $dispenser = new TicketDispenser();
        $ticket = $dispenser->getTurnTicket();
        $this->assertTrue($ticket instanceof TurnTicket);
    }
}
