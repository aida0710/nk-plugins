<?php

declare(strict_types=1);
namespace lazyperson0710\ticket;

use pocketmine\scheduler\Task;

class SaveTask extends Task {

	public function onRun() : void {
		TicketAPI::getInstance()->dataSave();
	}
}
