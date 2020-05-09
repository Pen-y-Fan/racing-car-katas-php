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

    public function testATicketCanBeDispensed(): void
    {
        $dispenser = new TicketDispenser();
        $ticket = $dispenser->getTurnTicket();
        $this->assertSame(1, $ticket->getTurnNumber());
    }

    public function testTheSameTicketCannotBeDispensedTwice(): void
    {
        $dispenser = new TicketDispenser();
        $ticket1 = $dispenser->getTurnTicket();
        $ticket2 = $dispenser->getTurnTicket();
        $this->assertNotSame($ticket1, $ticket2);
        $this->assertNotSame($ticket1->getTurnNumber(), $ticket2->getTurnNumber());
    }

    public function testMultipleDispensersCannotDispensedTheSameTicket(): void
    {
        $dispenser1 = new TicketDispenser();
        $dispenser2 = new TicketDispenser();
        $ticket1 = $dispenser1->getTurnTicket();
        $ticket2 = $dispenser2->getTurnTicket();
        $this->assertNotSame($ticket1, $ticket2);
        $this->assertNotSame($ticket1->getTurnNumber(), $ticket2->getTurnNumber());
    }
}
