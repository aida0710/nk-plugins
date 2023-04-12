<?php

declare(strict_types = 0);

namespace lazyperson0710\ticket;

use lazyperson0710\ticket\EventListener\BreakEventListener;
use pocketmine\scheduler\Task;

class TicketTask extends Task {

	public function onRun() : void {
		(new BreakEventListener())->giveTicket();
		TicketAPI::getInstance()->dataSave();
	}
}
